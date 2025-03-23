<?php

require_once './Database.php';

session_start();

function selectUserCards($pdo, $user_id)
{
    $sql = "SELECT * FROM card_inventory WHERE user_id = :user_id";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $cardList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $cardList;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();
    $user_id = $_SESSION['user']['user_id'];
    $cardList = selectUserCards($pdo, $user_id);
    echo $cardList ? json_encode(['status' => true, 'cardList' => $cardList]) : json_encode(['status' => false]);
    exit();
}
