<?php

require_once '../core/Database.php';

session_start();

function selectActiveGacha($pdo)
{
    $sql = "SELECT * FROM gacha_info WHERE is_active = true ORDER BY gacha_id DESC;";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $gachaInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $gachaInfo;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function selectAllGacha($pdo)
{
    $sql = "SELECT * FROM gacha_info;";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $gachaInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $gachaInfo;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();
    $activeFlag = $_POST['isActive'];
    $activeFlag = true;
    $gachaList = $activeFlag ? selectActiveGacha($pdo) : selectAllGacha($pdo);
    echo $gachaList ? json_encode(['status' => true, 'gachaList' => $gachaList]) : json_encode(['status' => false]);
    exit();
}
