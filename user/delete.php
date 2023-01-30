<?php

// HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключим файл для соединения с базой и объектом Product
include_once "../config/database.php";
include_once "../objects/user.php";

// получаем соединение с БД
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$user = new User($db);

// получаем id пользователя
$data = json_decode(file_get_contents("php://input"));

// установим id пользователя для удаления
$user->id = $data->id;

// удаление пользователя
if ($user->delete()) {
    // код ответа - 200 ok
    http_response_code(200);

    // выводим сообщение:
    echo json_encode(array("message" => "Пользователь был удалён"), JSON_UNESCAPED_UNICODE);
}
// если не удается удалить пользователя
else {
    // код ответа - 503 Сервис не доступен
    http_response_code(503);

    // выводим сообщение:
    echo json_encode(array("message" => "Не удалось удалить пользователя"));
}