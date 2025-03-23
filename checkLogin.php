<?php
require_once './core/Database.php';

session_start();

function selectUserData($pdo, $email)
{
    $sql = "SELECT * FROM users_info WHERE email = :email";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = selectUserData($pdo, $email);
    if (!$user) {
        $_SESSION['error'] = '入力されたメールアドレスが見つかりませんでした。</br>もう一度やり直してください。';
        header("Location: ./index.php");
        exit;
    } else if ($user && !password_verify($password, $user['password'])) {
        $_SESSION['error'] = 'パスワードが違います。</br>もう一度やり直してください。';
        header("Location: ./index.php");
        exit;
    } else {
        $_SESSION['user'] = $user;
        header("Location: ./homepage/homepage.php");
        exit;
    }
}
