<?php

require_once '../core/Database.php';

session_start();

function updateMissionComplete($pdo, $user_id, $mission_id, $is_complete, $today)
{
    $sql = "UPDATE mission_history SET is_complete = :is_complete WHERE user_id = :user_id AND mission_id = :mission_id AND add_date = :today;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':mission_id', $mission_id);
        $smtp->bindParam(':is_complete', $is_complete);
        $smtp->bindParam(':today', $today);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function updateMissionReceived($pdo, $user_id, $mission_id, $is_received, $today)
{
    $sql = "UPDATE mission_history SET is_received = :is_received WHERE user_id = :user_id AND mission_id = :mission_id AND add_date = :today;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':is_complete', $is_received);
        $smtp->bindParam(':mission_id', $mission_id);
        $smtp->bindParam(':today', $today);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();

    date_default_timezone_set('Asia/Tokyo');
    $today = date("Y-m-d");

    $mission_id = $_POST['mission_id'];
    $is_received = $_POST['is_received'];
    $user_id = $_SESSION['user']['user_id'];
    $receivedRes = updateMissionReceived($pdo, $user_id, $mission_id, $is_received, $today);
    if ($receivedRes) {
        echo $receivedRes ? json_encode(['status' => true]) : json_encode(['status' => false]);
    }
    exit();
}
