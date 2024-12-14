<?php
// Подключение к базе данных
$servername = "mysql";
$username = "counter";
$password = "cntrpassword";
$dbname = "counter_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Запрос для получения последнего номера талона
$sql = "SELECT MAX(ticket) as last_ticket FROM tickets";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    $lastTicket = ($row["last_ticket"] === null) ? 0 : $row["last_ticket"];
    $newTicket = $lastTicket + 1;


    // Записываем новый номер талона в базу данных
    $insertSql = "INSERT INTO tickets (ticket) VALUES ($newTicket)";
    $insertResult = $conn->query($insertSql);

    if ($insertResult) {
        header('Content-Type: application/json');
        echo json_encode(["last_ticket" => $newTicket]); // Возвращаем НОВЫЙ номер
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Ошибка записи в БД: ' . $conn->error]);
    }


} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Ошибка SQL: ' . $conn->error]);
}

$conn->close();
?>