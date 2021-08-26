<?php declare(strict_types=1);

namespace Shedule\Router;

use Schedule\Api\ApiController;

class Router {
    public function __construct(private ApiController $api) {}

    public function route(string $url) {
        $path = parse_url($url, PHP_URL_PATH);
        $path = explode('/', $path);
        $path = array_values(array_filter($path, function ($segm) { return $segm != ''; }));

        header('Content-Type: application/json');
        header('Access-Control-Allow-Headers: *', false);
        header('Access-Control-Allow-Origin: *', false);
        header('Access-Control-Allow-Methods: *', false);

        if ($path[0] == 'api') {
            $this->route_api($path);
        }
    }

    private function route_api(array $path) {
        if (!empty($path[1]) && empty($path[2])) {
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                echo $this->api->getAll($path[1]);
            } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo $this->api->create($path[1], file_get_contents('php://input'));
            }
        } else if (!empty($path[1]) && !empty($path[2])) {
            $id = intval($path[2]);

            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                echo $this->api->getById($path[1], $id);
            } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
                $this->api->delete($path[1], $id);
            } else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
                echo $this->api->update($path[1], $id, file_get_contents('php://input'));
            }
        }
    }
}


