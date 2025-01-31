<?php
/***
 * You can define your api routes here
 */

use Lib\Router\Router;
use App\Controllers\Projects;
use App\Controllers\Test;

Router::get('api/projects', [Projects::class, 'getAll']);
Router::get('api/projects/[id]', [Projects::class, 'getById']);
Router::get('api/test', [Test::class, 'test']);
