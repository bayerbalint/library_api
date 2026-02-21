<?php

namespace App\Http;

use App\Repositories\BaseRepository;
use App\Repositories\BookRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\PublisherRepository;
use App\Repositories\WriterRepository;

class Request
{
    static array $acceptedRoutes = [
        'POST' => [
            // '/users/login',
            // '/users/logout',
            '/books',
            '/categories',
            '/publishers',
            '/writers',
        ],
        'GET' => [
            '/books',
            '/books/{id}',
            '/categories',
            '/categories/{id}',
            '/publishers',
            '/publishers/{id}',
            '/writers',
            '/writers/{id}',
            '/categories/{category}/books',
            '/publishers/{publisher}/books',
            '/writers/{writer}/books'
        ],
        'PUT' => [
            '/books/{id}',
            '/categories/{id}',
            '/publishers/{id}',
            '/writers/{id}',
        ],
        'DELETE' => [
            '/books/{id}',
            '/categories/{id}',
            '/publishers/{id}',
            '/writers/{id}',
        ],
    ];

        static function handle()
    {
        // Lekérjük a HTTP metódust és az URI-t
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        // Ellenőrizzük, hogy a kérés engedélyezett route-ra mutat-e
        if (!self::isRouteAllowed($requestMethod, $requestUri, self::$acceptedRoutes)) {
            return Response::error("Route not allowed 400", 400);
        }

        // Feldolgozzuk az URI-t és az adatokat
        $requestData = self::getRequestData();
        $arrUri = self::requestUriToArray($_SERVER['REQUEST_URI']);
        $resourceName = self::getResourceName($arrUri);
        $resourceId = self::getResourceId($arrUri);
        $childResourceName = self::getChildResourceName($arrUri);
        $childResourceId = self::getChildResourceId($arrUri);

        if ($requestData === null && $requestMethod != "GET"){
            Response::error("Syntax error", 400);
        }


        // A metódus alapján meghívjuk a megfelelő függvényt
        switch ($requestMethod){
            case "POST":
                self::postRequest($resourceName, $requestData, $childResourceName);
                break;
            case "PUT":
                self::putRequest($resourceName, $resourceId, $requestData, $childResourceName, $childResourceId);
                break;
            case "GET":
                self::getRequest($resourceName, $resourceId, $childResourceName);
                break;
            case "DELETE":
                self::deleteRequest($resourceName, $resourceId, $childResourceName, $childResourceId);
                break;
            default:
                echo 'Unknown request type';
                break;
        }
    }

    private static function getResourceName($arrUri): ?string
    {
        return $arrUri['resourceName'] ?? null;
    }

    private static function getResourceId($arrUri): ?int
    {
        return $arrUri['resourceId'] ?? null;
    }

    private static function getChildResourceName($arrUri): ?string
    {
        return $arrUri['childResourceName'] ?? null;
    }

    private static function getChildResourceId($arrUri): ?int
    {
        return $arrUri['childResourceId'] ?? null;
    }

    private static function getRequestData(): ?array
    {
        return json_decode(trim(file_get_contents("php://input"), '\n'), true);
    }

    private static function requestUriToArray($uri): array
    {
        $arrUri = explode("/", trim($uri, "/"));
        return [
            'resourceName' => $arrUri[0] ?? null,
            'resourceId' => !empty($arrUri[1]) ? (int)$arrUri[1] :  null,
            'childResourceName' => $arrUri[2] ?? null,
            'childResourceId' => !empty($arrUri[3]) ? (int)$arrUri[3] : null,
        ];
    }

    private static function isRouteMatch($route, $uri): bool
    {
        $routeParts = explode('/', trim($route, '/'));
        $uriParts = explode('/', trim($uri, '/'));

        if (count($routeParts) !== count($uriParts)) {
            return false;
        }

        foreach ($routeParts as $index => $routePart) {
            if (preg_match('/^{.*}$/', $routePart)) {
                continue; // Paraméter placeholder, bármilyen értéket elfogad
            }
            if ($routePart !== $uriParts[$index]) {
                return false;
            }
        }

        return true;
    }

    private static function isRouteAllowed($method, $uri, $routes): bool
    {
        if (!isset($routes[$method])) {
            return false;
        }

        foreach ($routes[$method] as $route) {
            if (self::isRouteMatch($route, $uri)) {
                return true;
            }
        }

        return false;
    }

    private static function getRepository($resourceName): ?BaseRepository
    {
        switch ($resourceName) {
            case 'books':
                return new BookRepository();
            case 'categories':
                return new CategoryRepository();
            case 'publishers':
                return new PublisherRepository();
            case 'writers':
                return new WriterRepository();
            default:
                return null;
        }
    }

    private static function postRequest($resourceName, $requestData, $childResourceName)
    {
        $repository = self::getRepository($resourceName);
        if (!$repository) {
            return Response::error("400 Error");
        }

        $newId = $repository->create($requestData);
        $code = 400; // Bad Request alapértelmezés
        if ($newId) {
            $code = 201; // Created
        }

        Response::created(['id' => $newId]);
    }

    private static function putRequest($resourceName, $resourceId, $requestData, $childResourceName, $childResourceId)
    {
        $repository = self::getRepository($resourceName);
        $code = 404;
        $entity = $repository->find($resourceId);
        if ($entity) {
            $data = [];
            foreach ($requestData as $key => $value) {
                $data[$key] = $value;
            }
            $result = $repository->update($resourceId, $data);
            if ($result) {
                $code = 202;

            }
        }
        Response::updated("updated", $code);
    }
    private static function getRequest($resourceName, $resourceId = null, $childResourceName = null)
    {
        if ($childResourceName) {
            $repository = self::getRepository($childResourceName);
            if ($resourceId) {
                if ($childResourceName == "books" && $resourceName == "publishers"){
                    $entities = $repository->getByPublisher($resourceId);
                    Response::ok($entities);
                    return;
                }
                if ($childResourceName == "books" && $resourceName == "writers"){
                    $entities = $repository->getByWriter($resourceId);
                    Response::ok($entities);
                    return;
                }
                if ($childResourceName == "books" && $resourceName == "categories"){
                    $entities = $repository->getByCategory($resourceId);
                    Response::ok($entities);
                    return;
                }
            }
        }
        $repository = self::getRepository($resourceName);
        if ($resourceId) {
            $entity = $repository->find($resourceId);
            if (!$entity) {
                Response::error("Error 404", 404);
                return;
            }
            Response::ok([$entity]);
            return;
        }
        $entities = $repository->getAll();
        Response::ok($entities);
    }
    
    private static function deleteRequest($resourceName, $resourceId, $childResourceName)
    {
        $repository = self::getRepository($resourceName);
        $result = $repository->delete($resourceId);
        if ($result) {
            $code = 204;
        }
        Response::deleted();
    }

    
}