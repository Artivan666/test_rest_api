<?php

class Role
{
    // соединение с БД и таблицей "roles"
    private $conn;
    private $table_name = "roles";

    // свойства объекта
    public $id;
    public $name;
    public $description;
    public $created;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // метод для получения всех ролей пользователей
public function readAll()
{
    $query = "SELECT
                id, name, description
            FROM
                " . $this->table_name . "
            ORDER BY
                name";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
}
}
