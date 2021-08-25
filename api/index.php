<?php declare(strict_types=1);

namespace Schedule;

require 'Router.php';
require 'ApiController.php';

use PDO;
use PDOException;
use Schedule\Api\ApiController;
use Shedule\Router\Router;

try {
    $pdo = new PDO("pgsql:host=localhost;dbname=aud_dist", "postgres", "qwerty");
} catch (PDOException $e) {
    die($e->getMessage());
}

$api = new ApiController($pdo);
$router = new Router($api);

$router->route($_SERVER['REQUEST_URI']);




