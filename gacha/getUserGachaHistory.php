<?php

require_once '../core/Database.php';

session_start();

function selectUserHistory($pdo, $user_id)
{
    // -- リストのカード情報
    $sql = "SELECT * FROM gacha_history LEFT JOIN gacha_info
        ON gacha_info.gacha_id = gacha_history.gacha_id
        WHERE user_id = :user_id
        ORDER BY gacha_history.gacha_day DESC";
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

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$pdo = Database::getInstance()->getPDO();
$user_id = $_SESSION['user']['user_id'];
$userGachaHistory = selectUserHistory($pdo, $user_id);
echo $userGachaHistory ? json_encode(['status' => true, 'userGachaHistory' => $userGachaHistory]) : json_encode(['status' => false]);
exit();
// }
