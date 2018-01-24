<?php
/**
 * Database PDO Wrapper
 * Provides a database wrapper around the PDO service to help reduce the effort
 * to interact with a RDBMS such as SQLite, MySQL, or PostgreSQL.
 *
 * @link https://www.webdevlabs.com
 * @link https://github.com/Xeoncross/DByte
 */

/**
 * 	DB::$c = new PDO($dsn);.
 *
 * Examples:
 * Result: single column
 * $count = DB::column('SELECT COUNT(*) FROM `user`);
 *
 * Result: an array(key => value) results (i.e. for making a selectbox)
 * $pairs = DB::pairs('SELECT `id`, `username` FROM `user`);
 *
 * Result: a single row result
 * $user = DB::row('SELECT * FROM `user` WHERE `id` = ?', array($user_id));
 *
 * Result: a single row result
 * $user = DB::row('SELECT * FROM `user` WHERE `id` = :varname', array(":varname"=>"some variable"));
 *
 * Result: an array of results
 * $banned_users = DB::fetch('SELECT * FROM `user` WHERE `banned` = ?', array(TRUE));
 */

namespace System;

class DB
{
    public static $q;
    public static $c;
    public static $p;
    public static $i = '`';

    /**
     * Fetch a column offset from the result set (COUNT() queries).
     *
     * @param string $query  query string
     * @param array  $params query parameters
     * @param int    $key    index of column offset
     *
     * @return array|null
     */
    public static function column($query, $params = null, $key = 0)
    {
        if ($statement = self::query($query, $params)) {
            return $statement->fetchColumn($key);
        }
    }

    /**
     * Fetch a single query result row.
     *
     * @param string $query  query string
     * @param array  $params query parameters
     *
     * @return mixed
     */
    public static function row($query, $params = null)
    {
        if ($statement = self::query($query, $params)) {
            return $statement->fetch();
        }
    }

    /**
     * Fetches an associative array of all rows as key-value pairs (first
     * column is the key, second column is the value).
     *
     * @param string $query  query string
     * @param array  $params query parameters
     *
     * @return array
     */
    public static function pairs($query, $params = null)
    {
        $data = [];
        if ($statement = self::query($query, $params)) {
            while ($row = $statement->fetch(\PDO::FETCH_NUM)) {
                $data[$row[0]] = $row[1];
            }

            return $data;
        }
    }

    /**
     * Fetch all query result rows.
     *
     * @param string $query  query string
     * @param array  $params query parameters
     * @param int    $column the optional column to return
     *
     * @return array
     */
    public static function fetch($query, $params = null, $column = null)
    {
        if (!$statement = self::query($query, $params)) {
            return;
        }

        // Return an array of records
        if ($column === null) {
            return $statement->fetchAll();
        }

        // Fetch a certain column from all rows
        return $statement->fetchAll(\PDO::FETCH_COLUMN, $column);
    }

    /**
     * Prepare and send a query returning the PDOStatement.
     *
     * @param string $query  query string
     * @param array  $params query parameters
     *
     * @return object|null
     */
    public static function query($query, $params = null)
    {
        $statement = static::$c->prepare(self::$q[] = strtr($query, '`', self::$i));
        $statement->execute($params);

        return $statement;
    }

    /**
     * Insert a row into the database.
     *
     * @param string $table name
     * @param array  $data
     *
     * @return int|null
     */
    public static function insert($table, array $data)
    {
        $query = "INSERT INTO `$table` (`".implode('`, `', array_keys($data)).'`) VALUES ('.rtrim(str_repeat('?, ', count($data = array_values($data))), ', ').')';

        return self::$p ? self::column($query.' RETURNING `id`', $data) : (self::query($query, $data) ? static::$c->lastInsertId() : null);
    }

    /**
     * Update a database row.
     *
     * @param string $table name
     * @param array  $data
     * @param array  $w     where conditions
     *
     * @return int|null
     */
    public static function update($table, $data, $value, $column = 'id')
    {
        $keys = implode('`=?,`', array_keys($data));
        if ($statement = self::query("UPDATE `$table` SET `$keys` = ? WHERE `$column` = ?", array_values($data + [$value]))) {
            return $statement->rowCount();
        }
    }

    /**
     * Prefix array values for update function.
     *
     * @param array $arr
     *
     * @return array
     */
    public static function array_keys_prefix($arr)
    {
        $rarr = [];
        foreach ($arr as $key => $val) {
            $rarr["$key=VALUES($key)"] = $val;
        }

        return $rarr;
    }
}
