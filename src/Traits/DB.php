<?php
namespace Traits;

trait DB{
    public function createWhereClause($array): array|string
    {
        $where = '';
        if (empty($array)) {
            return $where;
        }
        $where .= ' WHERE ';
        $type = 'AND';
        if (isset($array['type']) && in_array(strtoupper($array['type']), array('AND', 'OR'))) {
            $type = strtoupper($array['type']);
            unset($array['type']);
        }
        $first = true;
        $placeholders = array();
        foreach($array as $key => $value) {
            if (is_array($value)) {
                $subWhere = self::createWhereClause($value);
                $placeholders = array_merge($placeholders, $subWhere['placeholders']);
                if($first) {
                    $where .= $subWhere['where'];
                    $first = false;
                } else {
                    $where .= ' ' . $type . ' ' . $subWhere['where'];
                }
            } else {
                if($first) {
                    $first = false;
                } else {
                    $where .= ' ' . $type . ' ';
                }
                $placeholder = ':' . $key;
                $where .= $key . ' = ' . $placeholder;
                $placeholders[$placeholder] = $value;
            }
        }
        return array('where' => $where, 'placeholders' => $placeholders);
    }
}
