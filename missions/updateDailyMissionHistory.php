<?php

require_once '../core/Database.php';

session_start();

function updateMissionHistoryComplete($pdo, $user_id, $mission_id, $is_complete, $today)
{
    $sql = "INSERT INTO mission_history (user_id, mission_id, is_complete, add_date, add_num)
        SELECT :user_id, :mission_id, :is_complete, :add_date, :add_num
        WHERE NOT EXISTS (
            SELECT 1 FROM mission_history
            WHERE user_id = :user_id1
            AND mission_id = :mission_id1
            AND DATE_FORMAT(add_date, '%Y-%m-%d') = DATE_FORMAT(:today, '%Y-%m-%d')
            AND is_complete = 1 
            complete_num = current_num
        )";
    try {
        $add_def = 1;

        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':mission_id', $mission_id);
        $smtp->bindParam(':is_complete', $is_complete);
        $smtp->bindParam(':add_date', $today);
        $smtp->bindParam(':add_num', $add_def);
        $smtp->bindParam(':user_id1', $user_id);
        $smtp->bindParam(':mission_id1', $mission_id);
        $smtp->bindParam(':today', $today);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function updateMissionHistoryReceived($pdo, $user_id, $mission_id, $mission_daily_id, $is_received, $today)
{
    $sql = "INSERT INTO mission_history_received (user_id, mission_id, mission_daily_id, is_received, received_date) 
    VALUES (:user_id, :mission_id, :mission_daily_id, :is_received, :received_date)";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':mission_id', $mission_id);
        $smtp->bindParam(':mission_daily_id', $mission_daily_id);
        $smtp->bindParam(':is_received', $is_received);
        $smtp->bindParam(':received_date', $today);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function updateUserInventory($pdo, $user_id, $item_id, $item_amount)
{
    $sql = "UPDATE users_inventory SET amount = amount + :item_amount WHERE item_id = :item_id AND user_id = :user_id;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':item_id', $item_id);
        $smtp->bindParam(':item_amount', $item_amount);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();

    date_default_timezone_set('Asia/Tokyo');
    $todayFull = date("Y-m-d H:i:s");

    $mission_id = $_POST['mission_id'];
    $is_complete = $_POST['is_complete'];
    $is_daily = $_POST['is_daily'];
    $reward_item_id = isset($_POST['reward_item_id']) ? $_POST['reward_item_id'] : null;
    $reward_amount = isset($_POST['reward_amount']) ? $_POST['reward_amount'] : null;

    $user_id = $_SESSION['user']['user_id'];
    if ($is_complete == 0) {
        $updateRes = updateMissionHistoryComplete($pdo, $user_id, $mission_id, 1, $todayFull);
    } else {
        $updateRes = (!$is_daily) ?
            updateMissionHistoryReceived($pdo, $user_id, $mission_id, null, 1, $todayFull) :
            updateMissionHistoryReceived($pdo, $user_id, null, $mission_id, 1, $todayFull);

        if ($updateRes) {
            updateUserInventory($pdo, $user_id, $reward_item_id, $reward_amount);
        }
    }
    echo $updateRes ? json_encode(['status' => true]) : json_encode(['status' => false]);
    exit();
}
