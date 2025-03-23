<?php
require_once '../core/Database.php';

session_start();

function changeUsername($pdo, $user_id, $newUsername)
{
    $sql = "UPDATE users_info SET username = :username WHERE user_id = :user_id;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':username', $newUsername);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();
    $user_id = $_SESSION['user']['user_id'];
    $newUsername = $_POST['newUsername'];
    $result = changeUsername($pdo, $user_id, $newUsername);
    echo $result ? json_encode(['status' => true]) : json_encode(['status' => false]);
}
