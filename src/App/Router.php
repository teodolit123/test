<?php
// Thanks for great codes: https://github.com/noahbuscher/macaw

namespace App;

use Controllers\API\ItemController;

/**
 * Class Router
 * @package App
 *
 * @method static Router get(string $route, Callable $callback)
 * @method static Router post(string $route, Callable $callback)
 * @method static Router put(string $route, Callable $callback)
 * @method static Router patch(string $route, Callable $callback)
 * @method static Router delete(string $route, Callable $callback)
 * @method static Router options(string $route, Callable $callback)
 * @method static Router head(string $route, Callable $callback)
 */
class Router
{
    public static bool $halts = false;
    public static array $routes = [];
    public static array $methods = [];
    public static array $callbacks = [];
    public static array $maps = [];
    public static array $patterns =
        [
            ':any' => '[^/]+',
            ':num' => '[0-9]+',
            ':all' => '.*'
        ];
    public static mixed $error_callback;

    /**
     * Defines a route w/ callback and method
     *
     * @param string $method
     * @param array $params
     * @return void
     */
    public static function __callstatic(string $method, array $params)
    {
        if ($method === 'map') {
            $maps = array_map('strtoupper', $params[0]);
            $uri = str_starts_with($params[1], '/') ? $params[1] : '/' . $params[1];
            $callback = $params[2];
        } else {
            $maps = null;
            $uri = str_starts_with($params[0], '/') ? $params[0] : '/' . $params[0];
            $callback = $params[1];
        }
        self::$maps[] = $maps;
        self::$routes[] = $uri;
        self::$methods[] = strtoupper($method);
        self::$callbacks[] = $callback;
    }

    /**
     * Defines callback if route is not found
     *
     * @param $callback
     * @return void
     */
    public static function error($callback): void
    {
        self::$error_callback = $callback;
    }

    /**
     * Halt matched methods
     *
     * @param boolean $flag
     * @return void
     */
    public static function haltOnMatch(bool $flag = true): void
    {
        self::$halts = $flag;
    }

    /**
     * Runs the callback for the given request
     *
     * @return void
     */
    public static function dispatch(): void
    {
        /**
         * Adding CONTROLLER_FOLDER to all callbacks
         */
        self::$callbacks = array_map(fn($v) => CONTROLLER_FOLDER . $v, self::$callbacks);

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $searches = array_keys(static::$patterns);
        $replaces = array_values(static::$patterns);
        $found_route = false;
        $matched = [];

        self::$routes = (array)preg_replace('/\/+/', '/', self::$routes);


        /**
         * Check if route is defined without regex
         */
        if (in_array($uri, self::$routes)) {

            $route_pos = array_keys(self::$routes, $uri);
            foreach ($route_pos as $route) {

                /**
                 * Using an ANY option to match both GET and POST requests
                 */
                if (self::$methods[$route] == $method || self::$methods[$route] == 'ANY' || (!empty(self::$maps[$route]) && in_array($method, self::$maps[$route]))) {
                    $found_route = true;

                    /**
                     * If route is not an object
                     */
                    if (!is_object(self::$callbacks[$route])) {
                        $parts = explode('/', self::$callbacks[$route]);
                        $last = end($parts);
                        $segments = explode('@', $last);
//                        $controller = new $segments[0]();
                        $controller = new ItemController();
                        $controller->{$segments[1]}();
                    } else {
                        call_user_func(self::$callbacks[$route]);
                    }
                    if (self::$halts) return;
                }
            }
        } else {

            /**
             * Check if defined with regex
             */
            $pos = 0;
            foreach (self::$routes as $route) {
                if (str_contains($route, ':')) {
                    $route = str_replace($searches, $replaces, $route);
                }

                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    if (self::$methods[$pos] == $method || self::$methods[$pos] == 'ANY' || (!empty(self::$maps[$pos]) && in_array($method, self::$maps[$pos]))) {
                        $found_route = true;
                        array_shift($matched);
                        if (!is_object(self::$callbacks[$pos])) {
                            $parts = explode('/', self::$callbacks[$pos]);
                            $last = end($parts);
                            $segments = explode('@', $last);
                            $controller = new $segments[0]();
                            if (!method_exists($controller, $segments[1])) {
                                echo "controller and action not found";
                            } else {
                                call_user_func_array([$controller, $segments[1]], $matched);
                            }
                        } else {
                            call_user_func_array(self::$callbacks[$pos], $matched);
                        }
                        if (self::$halts) return;
                    }
                }
                $pos++;
            }
        }

        /**
         * Run the error callback if the route was not found
         */
        if (!$found_route) {
            if (!self::$error_callback) {
                self::$error_callback = function () {
                    header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
                    echo '404 Page not found';
                    exit();
                };
            } elseif (is_string(self::$error_callback)) {
                self::get($_SERVER['REQUEST_URI'], self::$error_callback);
                self::$error_callback = null;
                self::dispatch();
                return;
            }
            call_user_func(self::$error_callback);
        }
    }
}
