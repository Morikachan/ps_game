<?php

require_once '../core/Database.php';

session_start();

function selectUsersRanking($pdo)
{
    $sql = "SELECT * FROM users_info
    LEFT JOIN user_home 
        ON user_home.user_id = users_info.user_id ORDER BY users_info.user_exp DESC";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $userList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $userList;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();
    $userList = selectUsersRanking($pdo);
    echo $userList ? json_encode(['status' => true, 'userList' => $userList]) : json_encode(['status' => false]);
}
