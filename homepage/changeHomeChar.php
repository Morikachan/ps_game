<?php
require_once '../core/Database.php';

session_start();

function changeHomeChar($pdo, $user_id, $card_id)
{
    $sql = "UPDATE user_home SET home_card_id = :home_card_id WHERE user_id = :user_id;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':home_card_id', $card_id);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();
    $user_id = $_SESSION['user']['user_id'];
    $card_id = $_POST['cardId'];
    $result = changeHomeChar($pdo, $user_id, $card_id);
    echo $result ? json_encode(['status' => true]) : json_encode(['status' => false]);
}
