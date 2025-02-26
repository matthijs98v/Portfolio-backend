<?php

/**
 * This is a query class witch execute queries save
 *
 * @author     Matthijs Verheijen <info@matthijsverheijen.com>
 */

namespace Lib\Database;

use PDO;
use PDOStatement;

class Query extends Database
{
    /***
     * To only execute to query
     *
     * @param       $query
     * @param array $params
     *
     * @return false|PDOStatement|array
     */
    public function run($query, array $params = []): false|PDOStatement|array
    {
        $query = $this->connect()->prepare($query);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute($params);

        return $query;
    }

    public function check()
    {
    }

    /***
     * To do a fetch
     *
     * @param       $query
     * @param array $params
     *
     * @return false|PDOStatement|array
     */
    public function fetch($query, array $params = []): false|PDOStatement|array
    {
        $query = $this->connect()->prepare($query);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute($params);

        return $query->fetch();
    }

    /***
     * To do a fetch all
     *
     * @param       $query
     * @param array $params
     *
     * @return false|PDOStatement|array
     */
    public function fetchAll($query, array $params = []): false|PDOStatement|array
    {
        $query = $this->connect()->prepare($query);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute($params);

        return $query->fetchAll();
    }
}
