<?php
$servername = "127.0.0.1"; // Адрес сервера базы данных
$username = "root"; // Имя пользователя базы данных
$password = ""; // Пароль пользователя базы данных
$dbname = "CollageBD"; // Имя базы данных

// Создаем соединение с базой данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверяем, был ли отправлен POST-запрос
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $firstname = $_POST['name'];
    $lastname = $_POST['surname'];
    $login = $_POST['login'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хешируем пароль

    // Определяем роль пользователя
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    // Вставляем данные в таблицу Авторизация
    $sql = "INSERT INTO Авторизация (Логин, Пароль, Роль) VALUES ('$login', '$password', '$role')";
    
    if ($conn->query($sql) === TRUE) {
        // Получаем ID последней вставленной записи
        $last_id = $conn->insert_id;

        // Вставляем данные в соответствующую таблицу в зависимости от роли
        if ($role == "student") {
            $sql = "INSERT INTO `Студенты` (`Фамилия`, `Имя`, `Логин`, `Пароль`) 
                    VALUES ('$lastname', '$firstname', '$login', '$password')";
        } elseif ($role == "teacher") {
            $sql = "INSERT INTO `Преподаватели` (`Фамилия`, `Имя`, `Логин`, `Пароль`, `Роль`) 
                    VALUES ('$lastname', '$firstname', '$login', '$password', '$role')";
        }

        // Выполняем запрос на вставку данных в таблицу студентов или преподавателей
        if ($conn->query($sql) === TRUE) {
            echo "<script> 
            alert('Регистрация прошла успешно!'); 
            window.location.href = 'login.html';
            exit();			
            </script>";
        } else {
            echo "<script> 
            alert('Ошибка при вставке в таблицу Данные пользователя: " . $conn->error . "'); 
            window.location.href = 'register.html'; 
            </script>";
        }
    } else {
        echo "Ошибка при вставке в таблицу Авторизация: " . $conn->error;
    }
}

// Закрываем соединение с базой данных
$conn->close();
?>