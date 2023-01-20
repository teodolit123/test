<?php

namespace Models;

use App\Database;
use Traits\DB as db;

/**
 * Class Currency
 * @package Models
 */
class Currency
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
            $query = "SELECT * FROM Currency " . $whereParams . "ORDER BY id DESC";
        } else {
            $query = "SELECT * FROM Currency " . $whereParams . "ORDER BY id DESC LIMIT :limit";
        }
        Database::query($query);

        foreach ($whereValues as $placeholder => $value) {
            Database::bind($placeholder, $value);
        }

        Database::bind(':limit', $limit);

        return Database::fetchAll();
    }
}
