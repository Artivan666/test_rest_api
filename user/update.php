<?php

// HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключаем файл для работы с БД и объектом User
include_once "../config/database.php";
include_once "../objects/user.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$user = new User($db);

// получаем id товара для редактирования
$data = json_decode(file_get_contents("php://input"));

// установим id свойства пользователя для редактирования
$user->id = $data->id;

// установим значения свойств пользователя
$user->name = $data->name;
$user->role_id = $data->role_id;

// обновление пользователя
if ($user->update()) {
    // установим код ответа - 200 ok
    http_response_code(200);

    // выводим сообщение:
    echo json_encode(array("message" => "Пользователь был обновлён"), JSON_UNESCAPED_UNICODE);
}
// если не удается обновить пользователя, выводим сообщение:
else {
    // код ответа - 503 Сервис не доступен
    http_response_code(503);

    // выводим сообщение:
    echo json_encode(array("message" => "Невозможно обновить пользователя"), JSON_UNESCAPED_UNICODE);
}
