<?php
// Подключение к базе данных
$servername = "mysql";
$username = "counter";
$password = "cntrpassword";
$dbname = "counter_db";

$memcached = new Memcached();
$memcached->addServer('memcached', 11211);
$cacheKey = 'queue';
$tickets = $memcached->get($cacheKey);

if ($tickets === false) {
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

        $memcached->set($cacheKey, $tickets, 300); // Сохраняем в кеш на 5 минут
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Ошибка SQL: ' . $conn->error]);
    }

    $conn->close();

    error_log("Queue retrieved from database."); // Запись в лог
} else {
    error_log("Queue retrieved from cache."); // Запись в лог
}

// Возвращаем результат в формате JSON
header('Content-Type: application/json');
echo json_encode(['tickets' => $tickets]);
?>
