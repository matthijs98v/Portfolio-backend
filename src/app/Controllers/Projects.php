<?php

namespace app\Controllers;

use lib\Router\Request;
use lib\Database\Query;

class Projects {
    /*
     * Returns all projects
     */
    public static function getAll( Request $r): array {
        $Query = new Query();
        $Data = $Query->fetchAll("SELECT * FROM projects");
    
        return [
            'projects' => $Data
        ];
    }

    /*
     * Returns project by id
     */
    public static function getById( Request $r): array {
        $Query = new Query();
        $Data = $Query->fetchAll("SELECT * FROM projects WHERE id = ?", [$r->params('id')]);
    
        return [
            'projects' => $Data
        ];
    }
}