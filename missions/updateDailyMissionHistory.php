<?php

require_once '../core/Database.php';

session_start();

function selectAllMissionsInfo($pdo, $user_id, $today)
{
    $sql = "SELECT 
                m.mission_id,
                m.mission_text,
                m.mission_type,
                m.complete_num,
                m.exec_type,
                m.reward_item_id,
                m.reward_amount,
                h.mission_history_id,
                h.is_complete,
                hr.is_received,
                h.add_date,
                h.add_num,
                hr.received_date,
                0 AS is_group_mission,
                NULL AS m_mission_daily_rewards_id,
                NULL AS mission_daily_text,
                NULL AS group_reward_amount,
                NULL AS group_reward_id,
                NULL AS all_cleared,
                NULL AS clear_count,
                NULL AS daily_received
            FROM m_mission_info m
            LEFT JOIN mission_history h
                ON h.mission_id = m.mission_id
                AND h.user_id = :user_id1
                AND DATE_FORMAT(h.add_date, '%Y-%m-%d') = :today1
                AND h.add_date = (
                    SELECT MAX(his.add_date)
                    FROM mission_history his
                    WHERE his.mission_id = h.mission_id
                    AND his.user_id = h.user_id
                    AND DATE_FORMAT(his.add_date, '%Y-%m-%d') = :today1
                )
            LEFT JOIN mission_history_received hr
                ON hr.mission_id = m.mission_id
                AND hr.user_id = :user_id9
                AND DATE_FORMAT(hr.received_date, '%Y-%m-%d') = :today9
            WHERE m.exec_type = 1
            UNION

            SELECT 
                NULL AS mission_id,
                NULL AS mission_text,
                NULL AS mission_type,
                NULL AS complete_num,
                NULL AS exec_type,
                NULL AS reward_item_id,
                NULL AS reward_amount,
                NULL AS mission_history_id,
                NULL AS is_complete,
                NULL AS is_received,
                NULL AS add_date,
                NULL AS add_num,
                NULL AS received_date,
                1 AS is_group_mission,
                d.m_mission_daily_rewards_id,
                d.mission_daily_text,
                d.reward_amount AS group_reward_amount,
                d.reward_item_id AS group_reward_id,
                CASE
                    WHEN COALESCE(h1.is_complete, 0) = 1 
                    AND COALESCE(h2.is_complete, 0) = 1 
                    AND COALESCE(h3.is_complete, 0) = 1 THEN 1
                    ELSE 0
                END AS all_cleared,
                (CASE WHEN h1.is_complete = 1 THEN 1 ELSE 0 END) +
                (CASE WHEN h2.is_complete = 1 THEN 1 ELSE 0 END) +
                (CASE WHEN h3.is_complete = 1 THEN 1 ELSE 0 END) AS clear_count,
                daily_hr.received_date AS daily_received
            FROM m_mission_daily_rewards d
            LEFT JOIN mission_history h1
                ON h1.mission_id = d.mission_id1 AND h1.user_id = :user_id2 AND DATE_FORMAT(h1.add_date, '%Y-%m-%d') = :today2
            LEFT JOIN mission_history h2
                ON h2.mission_id = d.mission_id2 AND h2.user_id = :user_id3 AND DATE_FORMAT(h2.add_date, '%Y-%m-%d') = :today3
            LEFT JOIN mission_history h3
                ON h3.mission_id = d.mission_id3 AND h3.user_id = :user_id4 AND DATE_FORMAT(h3.add_date, '%Y-%m-%d') = :today4
            LEFT JOIN mission_history_received hr1
                ON hr1.mission_id = d.mission_id1 AND hr1.user_id = :user_id5 AND DATE_FORMAT(hr1.received_date, '%Y-%m-%d') = :today5
            LEFT JOIN mission_history_received hr2
                ON hr2.mission_id = d.mission_id2 AND hr2.user_id = :user_id6 AND DATE_FORMAT(hr2.received_date, '%Y-%m-%d') = :today6
            LEFT JOIN mission_history_received hr3
                ON hr3.mission_id = d.mission_id3 AND hr3.user_id = :user_id7 AND DATE_FORMAT(hr3.received_date, '%Y-%m-%d') = :today7
            LEFT JOIN mission_history_received daily_hr
                ON daily_hr.mission_daily_id = d.m_mission_daily_rewards_id AND daily_hr.user_id = :user_id8 AND DATE_FORMAT(daily_hr.received_date, '%Y-%m-%d') = :today8;
            ";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id1', $user_id);
        $stmt->bindParam(':today1', $today);
        $stmt->bindParam(':user_id2', $user_id);
        $stmt->bindParam(':today2', $today);
        $stmt->bindParam(':user_id3', $user_id);
        $stmt->bindParam(':today3', $today);
        $stmt->bindParam(':user_id4', $user_id);
        $stmt->bindParam(':today4', $today);
        $stmt->bindParam(':user_id5', $user_id);
        $stmt->bindParam(':today5', $today);
        $stmt->bindParam(':user_id6', $user_id);
        $stmt->bindParam(':today6', $today);
        $stmt->bindParam(':user_id7', $user_id);
        $stmt->bindParam(':today7', $today);
        $stmt->bindParam(':user_id8', $user_id);
        $stmt->bindParam(':today8', $today);
        $stmt->bindParam(':user_id9', $user_id);
        $stmt->bindParam(':today9', $today);
        $stmt->execute();
        $missionsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $missionsList;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function updateMissionHistoryComplete($pdo, $user_id, $mission_id, $is_complete, $add_num, $today)
{
    $sql =
        "INSERT INTO mission_history (user_id, mission_id, is_complete, add_date, add_num)
        SELECT :user_id, :mission_id, :is_complete, :add_date, :add_num
        FROM DUAL
        WHERE NOT EXISTS (
            SELECT 1 FROM mission_history
            WHERE user_id = :user_id1
            AND mission_id = :mission_id1
            AND DATE_FORMAT(add_date, '%Y-%m-%d') = DATE_FORMAT(:today, '%Y-%m-%d')
            AND is_complete = 1 
        );";

    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':mission_id', $mission_id);
        $smtp->bindParam(':is_complete', $is_complete);
        $smtp->bindParam(':add_date', $today);
        $smtp->bindParam(':add_num', $add_num);
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
    if (!isset($_SESSION['user']['user_id'])) {
        http_response_code(401);
        echo json_encode(['status' => false, 'error' => '不正']);
        exit();
    } else {
        $user_id = $_SESSION['user']['user_id'];
    }

    date_default_timezone_set('Asia/Tokyo');
    $today = date("Y-m-d");
    $todayFull = date("Y-m-d H:i:s");

    $mission_id = $_POST['mission_id'];
    $add_num = $_POST['mission_num'];
    $is_daily = $_POST['is_daily'];
    $is_receiving = $_POST['is_receiving'] ?? 0;

    $missions_info = selectAllMissionsInfo($pdo, $user_id, $today);
    $mission_key = !$is_daily ?
        array_search($mission_id, array_column($missions_info, 'mission_id')) :
        array_search($mission_id, array_column($missions_info, 'm_mission_daily_rewards_id'));

    $is_complete = $missions_info[$mission_key]['is_complete'];
    $is_received = $missions_info[$mission_key]['is_received'];
    $curr_num = $missions_info[$mission_key]['add_num'] ?? 0;
    $comp_num = $missions_info[$mission_key]['complete_num'];

    $updateRes = false;

    if ($is_complete == 0 && !$is_daily) {
        if ($add_num + $curr_num  < $comp_num) {
            $updateRes = updateMissionHistoryComplete($pdo, $user_id, $mission_id, 0, $add_num, $todayFull);
        } else {
            $updateRes = updateMissionHistoryComplete($pdo, $user_id, $mission_id, 1, $add_num, $todayFull);
        }
    } else if ($is_received == 0 && $is_receiving) {
        if (!$is_daily) {
            $reward_item_id = $missions_info[$mission_key]['reward_item_id'];
            $reward_amount = $missions_info[$mission_key]['reward_amount'];
            $updateRes = updateMissionHistoryReceived($pdo, $user_id, $mission_id, null, 1, $todayFull);
        } else {
            $reward_item_id = $missions_info[$mission_key]['group_reward_id'];
            $reward_amount = $missions_info[$mission_key]['group_reward_amount'];
            $updateRes = updateMissionHistoryReceived($pdo, $user_id, null, $mission_id, 1, $todayFull);
        }
        if ($updateRes) {
            updateUserInventory($pdo, $user_id, $reward_item_id, $reward_amount);
        }
    }
    echo $updateRes ? json_encode(['status' => true]) : json_encode(['status' => false]);
    exit();
}
