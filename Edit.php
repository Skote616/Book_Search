<?php
// Подключение к базе данных
$servername = "127.0.0.1"; // или ваш сервер 
$username = "root"; // замените на ваше имя пользователя 
$password = ""; // замените на ваш пароль 
$dbname = "CollageBD"; // имя вашей базы данных

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Обновление данных, если форма отправлена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['Пары'] as $id => $pair) {
        $subject = $conn->real_escape_string($pair['subject']);
        $teacher = $conn->real_escape_string($pair['teacher']);
        $group = $conn->real_escape_string($pair['group']);
        
        $sql = "UPDATE Пары SET Предмет='$subject', Преподаватель='$teacher', Группа='$group' WHERE IDU='$id'";
        $conn->query($sql);
    }
}

// Получение данных из базы данных
$sql = "SELECT IDU, Предмет, Преподаватель, Группа FROM Пары";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование пар</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-container {
            width: 900px;
            padding: 50px;
            border: 5px solid #ccc;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Редактирование пар</h1>
        <form action="" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Предмет</th>
                        <th>Преподаватель</th>
                        <th>Группа</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><input type='text' name='Пары[{$row['IDU']}][subject]' value='{$row['Предмет']}'></td>";
                            echo "<td><input type='text' name='Пары[{$row['IDU']}][teacher]' value='{$row['Преподаватель']}'></td>";
                            echo "<td><input type='text' name='Пары[{$row['IDU']}][group]' value='{$row['Группа']}'></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Нет данных</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit">Сохранить изменения</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>