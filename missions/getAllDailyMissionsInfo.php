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

function updateDailyMissionHistoryComplete($pdo, $user_id, $mission_id, $is_received, $today)
{
    $sql = "INSERT INTO mission_history_received (user_id, mission_daily_id, is_received, received_date) 
            VALUES (:user_id, :mission_daily_id, :is_received, :received_date)";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':mission_daily_id', $mission_id);
        $smtp->bindParam(':is_received', $is_received);
        $smtp->bindParam(':received_date', $today);
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
    $todayFull = date("Y-m-d H:i:s");
    $user_id = $_SESSION['user']['user_id'];
    $missionsList = selectAllMissionsInfo($pdo, $user_id, $today);
    foreach ($missionsList as $mission) {
        if ($mission['is_group_mission'] && $mission['all_cleared'] && !$mission['is_received']) {
            // updateDailyMissionHistoryComplete($pdo, $user_id, $mission['m_mission_daily_rewards_id'], 1, $todayFull);
        }
    }
    echo $missionsList ? json_encode(['status' => true, 'missionsList' => $missionsList]) : json_encode(['status' => false]);
}
