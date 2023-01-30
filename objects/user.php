<?php

class User
{
    // подключение к базе данных и таблице "users"
    private $conn;
    private $table_name = "users";

    // свойства объекта
    public $id;
    public $name;
    public $role_id;
    public $role_name;
    public $created;

    // конструктор для соединения с базой данных
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // метод для получения пользователей
function read()
{
    // выбираем все записи
    $query = "SELECT
        c.name as role_name, p.id, p.name, p.role_id, p.created
    FROM
        " . $this->table_name . " p
        LEFT JOIN
            roles c
                ON p.role_id = c.id
    ORDER BY
        p.created DESC";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // выполняем запрос
    $stmt->execute();
    return $stmt;
}
// метод для создания пользователя
function create()
{
    // запрос для вставки (создания) записей
    $query = "INSERT INTO
            " . $this->table_name . "
        SET
            name=:name, role_id=:role_id, created=:created";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // очистка
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->role_id = htmlspecialchars(strip_tags($this->role_id));
    $this->created = htmlspecialchars(strip_tags($this->created));

    // привязка значений
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":role_id", $this->role_id);
    $stmt->bindParam(":created", $this->created);

    // выполняем запрос
    if ($stmt->execute()) {
        return true;
    }
    return false;
}
// метод для получения конкретного пользователя по ID
function readOne()
{
    // запрос для чтения одной записи (пользователя)
    $query = "SELECT
            c.name as role_name, p.id, p.name, p.role_id, p.created
        FROM
            " . $this->table_name . " p
            LEFT JOIN
                roles c
                    ON p.role_id = c.id
        WHERE
            p.id = ?
        LIMIT
            0,1";
            
    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // привязываем id пользователя, который будет получен
    $stmt->bindParam(1, $this->id);

    // выполняем запрос
    $stmt->execute();

    // получаем извлеченную строку
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // установим значения свойств объекта
    $this->name = $row["name"];
    $this->role_id = $row["role_id"];
    $this->role_name = $row["role_name"];
}
// метод для обновления пользователя
function update()
{
    // запрос для обновления записи (пользователя)
    $query = "UPDATE
            " . $this->table_name . "
        SET
            name = :name,
            role_id = :role_id
        WHERE
            id = :id";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // очистка
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->role_id = htmlspecialchars(strip_tags($this->role_id));
    $this->id = htmlspecialchars(strip_tags($this->id));

    // привязываем значения
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":role_id", $this->role_id);
    $stmt->bindParam(":id", $this->id);

    // выполняем запрос
    if ($stmt->execute()) {
        return true;
    }
    return false;
}
// метод для удаления пользователя
function delete()
{
    // запрос для удаления записи (пользователя)
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // очистка
    $this->id = htmlspecialchars(strip_tags($this->id));

    // привязываем id записи для удаления
    $stmt->bindParam(1, $this->id);

    // выполняем запрос
    if ($stmt->execute()) {
        return true;
    }
    return false;
}
// метод для поиска пользователя
function search($keywords)
{
    // поиск записей (товаров) по "названию товара", "описанию товара", "названию категории"
    $query = "SELECT
            c.name as role_name, p.id, p.name, p.role_id, p.created
        FROM
            " . $this->table_name . " p
            LEFT JOIN
                roles c
                    ON p.role_id = c.id
        WHERE
            p.name LIKE ? OR c.name LIKE ?
        ORDER BY
            p.created DESC";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // очистка
    $keywords = htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // привязка
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);

    // выполняем запрос
    $stmt->execute();

    return $stmt;
}
// получение пользователей с пагинацией
public function readPaging($from_record_num, $records_per_page)
{
    // выборка
    $query = "SELECT
            c.name as role_name, p.id, p.name, p.role_id, p.created
        FROM
            " . $this->table_name . " p
            LEFT JOIN
                roles c
                    ON p.role_id = c.id
        ORDER BY p.created DESC
        LIMIT ?, ?";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // свяжем значения переменных
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

    // выполняем запрос
    $stmt->execute();

    // вернём значения из базы данных
    return $stmt;
}
// данный метод возвращает кол-во пользователей
public function count()
{
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row["total_rows"];
}
}