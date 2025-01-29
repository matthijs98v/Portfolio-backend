<?php
/***
 * You can define your api routes here
 */

use lib\Router\Router;
use app\Controllers\Projects;
use app\Controllers\Test;

Router::get('api/projects', [Projects::class, 'getAll']);
Router::get('api/projects/[id]', [Projects::class, 'getById']);
Router::get('api/test', [Test::class, 'test']);
