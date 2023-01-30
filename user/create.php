<?php

// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// получаем соединение с базой данных
include_once "../config/database.php";

// создание объекта пользователя
include_once "../objects/user.php";
$database = new Database();
$db = $database->getConnection();
$user = new User($db);

// получаем отправленные данные
$data = json_decode(file_get_contents("php://input"));

// убеждаемся, что данные не пусты
if (
    !empty($data->name) &&
    !empty($data->role_id)
) {
    // устанавливаем значения свойств пользователя
    $user->name = $data->name;
    $user->role_id = $data->role_id;
    $user->created = date("Y-m-d H:i:s");

    // создание пользователя
    if ($user->create()) {
        // установим код ответа - 201 создано
        http_response_code(201);

        // выводим сообщение:
        echo json_encode(array("message" => "Пользователь был создан."), JSON_UNESCAPED_UNICODE);
    }
    // если не удается создать пользователя, выводим сообщение:
    else {
        // установим код ответа - 503 сервис недоступен
        http_response_code(503);

        // выводим сообщение:
        echo json_encode(array("message" => "Невозможно создать пользователя."), JSON_UNESCAPED_UNICODE);
    }
}
// выводим сообщение: что данные неполные
else {
    // установим код ответа - 400 неверный запрос
    http_response_code(400);

    // выводим сообщение:
    echo json_encode(array("message" => "Невозможно создать пользователя. Данные неполные."), JSON_UNESCAPED_UNICODE);
}