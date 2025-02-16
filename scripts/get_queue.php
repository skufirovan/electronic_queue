<?php
// Подключение к базе данных
$servername = "mysql";
$username = "counter";
$password = "cntrpassword";
$dbname = "counter_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Нет подключения к БД']);
    exit;
}

$sql = "SELECT Code, (`Number`) From Tickets ORDER BY id DESC LIMIT 5";
$result = $conn->query($sql);

if ($result) {
    $tickets = [];

    while ($row = $result->fetch_assoc()) {
        $ticket = $row['Code'] . $row['Number'];
        $tickets[] = $ticket;
    }

    // Возвращаем результат в формате JSON
    header('Content-Type: application/json');
    echo json_encode(['tickets' => $tickets]);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Ошибка SQL: ' . $conn->error]);
}

$conn->close();
?>
