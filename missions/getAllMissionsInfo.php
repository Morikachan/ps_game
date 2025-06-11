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
                h.is_received,
                h.add_date,
                FALSE AS is_group_mission,
                NULL AS m_mission_daily_rewards_id,
                NULL AS mission_daily_text,
                NULL AS group_reward_amount,
                NULL AS all_cleared
            FROM m_mission_daily_rewards d
            JOIN m_mission_info m
                ON m.mission_id IN (d.mission_id1, d.mission_id2, d.mission_id3)
            LEFT JOIN mission_history h
                ON h.mission_id = m.mission_id
                AND h.user_id = :user_id
                AND DATE(h.add_date) = DATE(:today)

            UNION

            SELECT 
                NULL AS mission_id,
                NULL AS mission_text,
                NULL AS mission_type,
                NULL AS complete_num,
                NULL AS exec_type,
                d.reward_item_id,
                d.reward_amount,
                NULL AS mission_history_id,
                NULL AS is_complete,
                NULL AS is_received,
                NULL AS add_date,
                TRUE AS is_group_mission,
                d.m_mission_daily_rewards_id,
                d.mission_daily_text,
                d.reward_amount AS group_reward_amount,

                CASE
                    WHEN h1.is_complete = TRUE AND h2.is_complete = TRUE AND h3.is_complete = TRUE THEN TRUE
                    ELSE FALSE
                END AS all_cleared

            FROM m_mission_daily_rewards d
            LEFT JOIN mission_history h1
                ON h1.mission_id = d.mission_id1 AND h1.user_id = :user_id AND DATE_FORMAT(h1.add_date,'%y-%m-%d') = :today
            LEFT JOIN mission_history h2
                ON h2.mission_id = d.mission_id2 AND h2.user_id = :user_id AND DATE_FORMAT(h2.add_date,'%y-%m-%d') = :today
            LEFT JOIN mission_history h3
                ON h3.mission_id = d.mission_id3 AND h3.user_id = :user_id AND DATE_FORMAT(h3.add_date,'%y-%m-%d') = :today;";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':today', $today);
        $stmt->execute();
        $missionsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $missionsList;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();
    date_default_timezone_set('Asia/Tokyo');
    $today = date("Y-m-d");
    $user_id = $_SESSION['user']['user_id'];
    $missionsList = selectAllMissionsInfo($pdo, $user_id, $today);
    echo $missionsList ? json_encode(['status' => true, 'missionsList' => $missionsList]) : json_encode(['status' => false]);
}
