<?php

// установим HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов
include_once "../config/core.php";
include_once "../shared/utilities.php";
include_once "../config/database.php";
include_once "../objects/user.php";

// utilities
$utilities = new Utilities();

// создание подключения
$database = new Database();
$db = $database->getConnection();

// инициализация объекта
$user = new User($db);

// запрос пользователей
$stmt = $user->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// если больше 0 записей
if ($num > 0) {

    // массив пользователей
    $users_arr = array();
    $users_arr["records"] = array();
    $users_arr["paging"] = array();

    // получаем содержимое нашей таблицы
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        // извлечение строки
        extract($row);
        $user_item = array(
            "id" => $id,
            "name" => $name,
            "role_id" => $role_id,
            "role_name" => $role_name
        );
        array_push($roles_arr["records"], $role_item);
    }

    // подключим пагинацию
    $total_rows = $user->count();
    $page_url = "{$home_url}user/read_paging.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $users_arr["paging"] = $paging;

    // установим код ответа - 200 OK
    http_response_code(200);

    // вывод в json-формате
    echo json_encode($users_arr);
} else {

    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // выводим сообщение:
    echo json_encode(array("message" => "Товары не найдены"), JSON_UNESCAPED_UNICODE);
}