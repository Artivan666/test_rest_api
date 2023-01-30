<?php

// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение базы данных и файл, содержащий объекты
include_once "../config/database.php";
include_once "../objects/user.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// инициализируем объект
$user = new User($db);
 
// запрашиваем пользователей
$stmt = $user->read();
$num = $stmt->rowCount();

// проверка, найдено ли больше 0 записей
if ($num > 0) {
    // массив пользователей
    $users_arr = array();
    $users_arr["records"] = array();

    // получаем содержимое нашей таблицы
    // fetch() быстрее, чем fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // извлекаем строку
        extract($row);
        $user_item = array(
            "id" => $id,
            "name" => $name,
            "role_id" => $role_id,
            "role_name" => $role_name
        );
        array_push($users_arr["records"], $user_item);
    }

    // устанавливаем код ответа - 200 OK
    http_response_code(200);

    // выводим данные о пользователе в формате JSON
    echo json_encode($users_arr);
} else {
  // установим код ответа - 404 Не найдено
  http_response_code(404);

  // выводим сообщение:
  echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
}