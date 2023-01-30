<?php

// установим HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов для соединения с БД и файл с объектом Role
include_once "../config/database.php";
include_once "../objects/role.php";

// создание подключения к базе данных
$database = new Database();
$db = $database->getConnection();

// инициализация объекта
$role = new Role($db);

// получаем роли
$stmt = $role->readAll();
$num = $stmt->rowCount();

// проверяем, найдено ли больше 0 записей
if ($num > 0) {

    // массив для записей
    $roles_arr = array();
    $roles_arr["records"] = array();

    // получим содержимое нашей таблицы
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        // извлекаем строку
        extract($row);
        $role_item = array(
            "id" => $id,
            "name" => $name,
            "description" => html_entity_decode($description)
        );
        array_push($roles_arr["records"], $role_item);
    }
    // код ответа - 200 OK
    http_response_code(200);

    // покажем данные ролей в формате json
    echo json_encode($roles_arr);
} else {

    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // сообщим пользователю, что роли не найдены
    echo json_encode(array("message" => "Роли не найдены"), JSON_UNESCAPED_UNICODE);
}