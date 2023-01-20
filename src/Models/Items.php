<?php

namespace Models;

use App\Database;
use Traits\DB as db;
use typeEnum;


/**
 * Class Currency
 * @package Models
 */
class Items
{
    use db;

    /**
     * READ all
     *
     * @param array $params
     * @param int $limit
     * @return array
     */
    public static function get(array $params = [], int $limit = 0): array
    {
        if (!empty($params)){
            $whereParams = self::createWhereClause($params)['where'];
            $whereValues = self::createWhereClause($params)['placeholders'];
        } else {
            $whereParams = '';
            $whereValues = '';
        }

        if ($limit === 0) {
            $query = "SELECT * FROM Items " . $whereParams . "ORDER BY id DESC";
        } else {
            $query = "SELECT * FROM Items " . $whereParams . "ORDER BY id DESC LIMIT :limit";
        }
        Database::query($query);

        foreach ($whereValues as $placeholder => $value) {
            Database::bind($placeholder, $value);
        }

        Database::bind(':limit', $limit);

        return Database::fetchAll();
    }

    /**
     * CREATE
     *
     * @param object $request
     * @return bool
     */
    public static function create(object $request): bool
    {
        Database::query("INSERT INTO Items (
            `name`,
            `size`,
            `price`,
            `currencyId`,
            `typeId`,
        ) VALUES (:name, :size, :price, :currencyId, :typeId)");


        Database::bind([
            ':name' => $request->name,
            ':size' => $request->size,
            ':price' => $request->price,
            ':currencyId' => $request->currencyId,
            ':typeId' => $request->typeId,
            ':model' => $request->model
        ]);

        Database::execute();

        if($request->typeId === typeEnum::SHOES) {
            self::update(
                [
                    'price' =>$request->price
                ],
                [
                    "typeId" => 1,
                    "model" => $request->model
                ]
            );
        }


        return false;
    }

    /**
     * UPDATE
     *
     * @param object $request
     * @param $where
     * @return bool
     */
    public static function update(object $request,array $where = []): bool
    {
        $sql = "";
        foreach($request as $key => $value) {
            $sql .= "$key = :$key";
        }

        $whereSql = '';
        foreach($where as $key => $value) {
            $whereSql .= "$key = :$key";
        }

        Database::query("UPDATE Items SET {$sql} WHERE {$whereSql}");

        foreach($request as $key => $value) {
            Database::bind(":$key", $value);
        }

        foreach($where as $key => $value) {
            Database::bind(":$key", $value);
        }

        if (Database::execute()) return true;
        return false;
    }
}
