<?php

require_once '../core/Database.php';

session_start();

function updateUserItems($pdo, $user_id, $item_id, $amount)
{
    $sql = "UPDATE users_inventory SET amount = amount + :amount WHERE user_id = :user_id AND item_id = :item_id;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':item_id', $item_id);
        $smtp->bindParam(':amount', $amount);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function updateUserLvl($pdo, $user_id, $amount)
{
    $sql = "UPDATE users_info SET user_exp = user_exp + :amount WHERE user_id = :user_id;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':amount', $amount);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();
    $user_id = $_SESSION['user']['user_id'];

    $updateLvl = updateUserLvl($pdo, $user_id, 200);
    $updateGems = updateUserItems($pdo, $user_id, 1, 250);
    $updateTickets = updateUserItems($pdo, $user_id, 3, 100);

    echo $updateLvl && $updateGems && $updateTickets ? json_encode(['status' => true]) : json_encode(['status' => false]);
    exit();
}
