<?php

require_once '../core/Database.php';

session_start();

function selectAllShopInfo($pdo)
{
    $sql = "SELECT * FROM m_shop
    LEFT JOIN m_shop_group 
        ON m_shop_group.pack_group_id = m_shop.pack_group_id";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $shopList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $shopList;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();
    $shopList = selectAllShopInfo($pdo);
    echo $shopList ? json_encode(['status' => true, 'shopList' => $shopList]) : json_encode(['status' => false]);
}
