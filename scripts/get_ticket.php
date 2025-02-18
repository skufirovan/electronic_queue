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

// Получение данных из POST-запроса
$data = json_decode(file_get_contents('php://input'), true);
$serviceCode = $data['serviceCode'] ?? null;

if (!$serviceCode || !in_array($serviceCode, ['Д', 'Р', 'З'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Некорректный код услуги']);
    exit;
}


$sql = "SELECT MAX(`Number`) AS max_number FROM Tickets WHERE `Code` = '$serviceCode'";
$result = $conn->query($sql);

if ($result) {
    // Получение последнего номера талона для указанной услуги
    $row = $result->fetch_assoc();
    $lastNumber = ($row["max_number"] === null) ? 0 : $row["max_number"];
    $newNumber = $lastNumber + 1;

    // Запись нового талона в базу данных
    $insertSql = "INSERT INTO Tickets (`Code`, `Number`, `Date`) VALUES ('$serviceCode', '$newNumber', NOW())";
    $insertResult = $conn->query($insertSql);

    // Возврат нового номера
    if ($insertResult) {
        // Сброс кеша после успешной записи
        $memcached = new Memcached();
        $memcached->addServer('memcached', 11211);
        $cacheKey = 'queue';
        $memcached->delete($cacheKey);

        header('Content-Type: application/json');
        echo json_encode(['ticket' => $serviceCode . $newNumber]);
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
