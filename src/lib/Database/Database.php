<?php

/**
 * This is a database class witch handles the connection
 *
 * @author     Matthijs Verheijen <info@matthijsverheijen.com>
 */

namespace Lib\Database;

use PDO;

class Database
{
    private ?PDO $dbh = null;

    public function __construct()
    {
        $this->dbh = new PDO(
            'pgsql:host=' . DB_HOST . ';dbname=' . DB_DATABASE,
            DB_USER,
            DB_PASS
        );
    }

    protected function connect(): ?PDO
    {
        return $this->dbh;
    }

    public function __destruct()
    {
        $this->dbh = null;
    }
}
