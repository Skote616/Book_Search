<?php 
$servername = "127.0.0.1"; // Адрес сервера базы данных
$username = "root"; // Имя пользователя базы данных
$password = ""; // Пароль пользователя базы данных
$dbname = "CollageBD"; // Имя базы данных 

// Создаем соединение
$conn = new mysqli($servername, $username, $password, $dbname); 

// Проверяем соединение
if ($conn->connect_error) { 
    die("Ошибка подключения: " . $conn->connect_error); 
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $login = $_POST['login']; 
    $inputPassword = $_POST['password']; // Получаем введенный пароль

    // Подготовленный запрос для получения пароля и роли
    $stmt = $conn->prepare("SELECT `Пароль`, `Роль` FROM `Авторизация` WHERE `Логин` = ?");
    $stmt->bind_param("s", $login); // Привязываем параметр

    // Выполняем запрос
    $stmt->execute();
    $result = $stmt->get_result(); 

    if ($result->num_rows > 0) { 
        $row = $result->fetch_assoc(); 
        // Проверяем пароль
        if (password_verify($inputPassword, $row['Пароль'])) { 
            // Перенаправляем на соответствующую страницу в зависимости от роли
            if ($row['Роль'] === 'teacher') {
                header("Location: Edit.php"); // Для преподавателей
            } elseif ($row['Роль'] === 'student') {
                header("Location: View.html"); // Для студентов
            }
            exit(); 
        } else { 
            echo "<script>alert('Неправильный пароль'); window.location.href = 'login.html';</script>"; 
        } 
    } else { 
        echo "<script>alert('Такого логина нет, зарегистрируйтесь'); window.location.href = 'login.html';</script>"; 
    } 

    // Закрываем подготовленный запрос
    $stmt->close();
} 

// Закрываем соединение
$conn->close();
?>