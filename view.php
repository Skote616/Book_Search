
<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "CollageBD";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// SQL-запрос для получения данных
$sql = "SELECT IDU, Предмет, Преподаватель, Группа FROM Пары";
$result = $conn->query($sql);

// Проверка наличия результатов и вывод данных в таблицу
if ($result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>IDU</th>
                <th>Предмет</th>
                <th>Преподаватель</th>
                <th>Группа</th>
            </tr>";
    
    // Вывод данных построчно
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["IDU"] . "</td>
                <td>" . $row["Предмет"] . "</td>
                <td>" . $row["Преподаватель"] . "</td>
                <td>" . $row["Группа"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Нет результатов</p>";
}

// Закрытие соединения
$conn->close();
?>