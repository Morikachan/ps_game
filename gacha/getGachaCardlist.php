<?php

require_once '../core/Database.php';

session_start();

function selectGacha($pdo, $gacha_id)
{
    $sql = "SELECT * FROM gacha_info WHERE gacha_id = :gacha_id;";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':gacha_id', $gacha_id);
        $stmt->execute();
        $gachaInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        return $gachaInfo;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function selectGachaCardList($pdo, $gacha_id)
{
    // -- リストのカード情報
    $sql = "SELECT * FROM m_gacha_list
    LEFT JOIN card_info 
        ON m_gacha_list.card_id = card_info.card_id
    LEFT JOIN m_card_skill 
        ON m_card_skill.card_skill_id = card_info.card_skill_id 
    LEFT JOIN m_card_type 
        ON m_card_type.card_type_id = card_info.card_type_id 
    LEFT JOIN m_rarity 
        ON m_rarity.rarity_id = card_info.rarity_id
    WHERE m_gacha_list.gacha_id = :gacha_id;";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':gacha_id', $gacha_id);
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
    $gacha_id = $_POST['gacha_id'];
    $user_id = $_SESSION['user']['user_id'];
    $selectGachaRes = selectGacha($pdo, $gacha_id);
    if ($selectGachaRes) {
        $gachaCardsList = selectGachaCardList($pdo, $gacha_id);
        echo $gachaCardsList ? json_encode(['status' => true, 'gachaCardsList' => $gachaCardsList]) : json_encode(['status' => false]);
    }
    exit();
}
