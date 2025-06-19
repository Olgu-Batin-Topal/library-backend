<?php
require_once __DIR__ . '/../app/controllers/AuthorController.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$apiPrefix = "/api";
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if (strpos($uri, $apiPrefix . '/authors') === 0) {
    $authorController = new AuthorController();

    switch ($method) {
        case 'GET':
            $uriParts = explode('/', trim($uri, '/'));
            $resource = array_pop($uriParts);

            if ($resource === 'authors') {
                echo $authorController->index();
            } else {
                echo $authorController->show($resource);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            echo $authorController->store($data);
            break;

        default:
            // http_response_code(405);
            echo json_encode(['message' => 'Method Not Allowed']);
            break;
    }
} else {
    http_response_code(404);
    die(json_encode(['message' => 'Not Found']));
}
