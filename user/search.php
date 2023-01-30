<?php

// HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение необходимых файлов
include_once "../config/core.php";
include_once "../config/database.php";
include_once "../objects/user.php";

// создание подключения к БД
$database = new Database();
$db = $database->getConnection();

// инициализируем объект
$user = new User($db);

// получаем ключевые слова
$keywords = isset($_GET["s"]) ? $_GET["s"] : "";

// запрос пользователя
$stmt = $user->search($keywords);
$num = $stmt->rowCount();

// проверяем, найдено ли больше 0 записей
if ($num > 0) {
    // массив пользователей
    $users_arr = array();
    $users_arr["records"] = array();

    // получаем содержимое нашей таблицы
    // fetch() быстрее чем fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        // извлечём строку
        extract($row);
        $user_item = array(
            "id" => $id,
            "name" => $name,
            "role_id" => $role_id,
            "role_name" => $role_name
        );
        array_push($roles_arr["records"], $role_item);
    }
    // код ответа - 200 OK
    http_response_code(200);

    // покажем пользователей
    echo json_encode($users_arr);
} else {
    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // выводим сообщение:
    echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
}