<?php

require_once '../core/Database.php';

session_start();

function updateUserFreeGems($pdo, $user_id, $free_gems)
{
    $sql = "UPDATE users_inventory SET amount = :free_gem_amount WHERE item_id = 1 AND user_id = :user_id;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':free_gem_amount', $free_gems);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function updateUserPaidGems($pdo, $user_id, $paid_gems)
{
    $sql = "UPDATE users_inventory SET amount = :paid_gem_amount WHERE item_id = 2 AND user_id = :user_id;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':paid_gem_amount', $paid_gems);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function updateUserCoins($pdo, $user_id, $coins)
{
    $sql = "UPDATE users_inventory SET amount = :paid_gem_amount WHERE item_id = 3 AND user_id = :user_id;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':paid_gem_amount', $coins);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function updateUserTickets($pdo, $user_id, $tickets)
{
    $sql = "UPDATE users_inventory SET amount = :paid_gem_amount WHERE item_id = 4 AND user_id = :user_id;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':paid_gem_amount', $tickets);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function selectShopInfo($pdo, $shopPackId)
{
    $sql = "SELECT * FROM m_shop
    LEFT JOIN m_shop_group 
        ON m_shop_group.pack_group_id = m_shop.pack_group_id WHERE m_shop.pack_group_id = :pack_group_id";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':pack_group_id', $shopPackId);
        $smtp->execute();
        $shopList = $smtp->fetchAll(PDO::FETCH_ASSOC);
        return $shopList;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function addPurchaseHistory($pdo, $user_id, $purchase_day, $pack_id)
{
    $sql = "INSERT INTO purchase_history (user_id, purchase_day, pack_id) VALUES (:user_id, :purchase_day, :pack_id)";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':purchase_day', $purchase_day);
        $smtp->bindParam(':pack_id', $pack_id);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function checkUserPurchasable($userInventory, $pack_type, $amount)
{
    $isPurchasable = false;
    if ($pack_type == 1) {
        $userInventory[1] + $userInventory[2] - $amount >= 0 ? $isPurchasable = true : $isPurchasable = false;
    } else {
        $userInventory[$pack_type] - $amount >= 0 ? $isPurchasable = true : $isPurchasable = false;
    }
    return $isPurchasable;
}

function newItemAmount($pdo, $userInventory, $item_type, $amount, $isPurchasedItem, $user_id)
{
    $newAmount = 0;
    $resultUpdatedAllItems = false;

    if (!$isPurchasedItem) {
        if ($item_type == 1) {
            if ($userInventory[1] >= $amount) {
                $newAmount = $userInventory[1] - $amount;
                $resultUpdatedAllItems = updateUserFreeGems($pdo, $user_id, $newAmount);
            } else if ($userInventory[1] <= $amount && $userInventory[1] != 0) {
                $newAmount = $userInventory[2] - ($amount - $userInventory[1]);
                $resultUpdatedAllItems = updateUserFreeGems($pdo, $user_id, 0);
                $resultUpdatedAllItems = updateUserPaidGems($pdo, $user_id, $newAmount) || $resultUpdatedAllItems;
            } else {
                $newAmount = $userInventory[2] - $amount;
                $resultUpdatedAllItems = updateUserPaidGems($pdo, $user_id, $newAmount);
            }
        } else {
            $newAmount = $userInventory[$item_type] - $amount;
            $resultUpdatedAllItems = true;
        }
    } else {
        $newAmount = $userInventory[$item_type] + $amount;
        $resultUpdatedAllItems = true;
    }

    switch ($item_type) {
        case 1:
            if ($isPurchasedItem == 0) {
                break;
            } else {
                $resultUpdatedAllItems = updateUserFreeGems($pdo, $user_id, $newAmount);
            }
            break;
        case 2:
            $resultUpdatedAllItems = updateUserPaidGems($pdo, $user_id, $newAmount);
            break;
        case 3:
            $resultUpdatedAllItems = updateUserCoins($pdo, $user_id, $newAmount);
            break;
        case 4:
            $resultUpdatedAllItems = updateUserTickets($pdo, $user_id, $newAmount);
            break;
        default:
            echo "エラー：アイテムが存在しません";
    }

    return $resultUpdatedAllItems;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();

    date_default_timezone_set('Asia/Tokyo');
    $purchaseDate = date("Y-m-d H:i:s");

    $pack_id = $_POST['pack_id'];

    $user_id = $_SESSION['user']['user_id'];

    $pack_info = selectShopInfo($pdo, $pack_id);

    $userInventory = array(
        1 => $_SESSION['free_gems'],
        2 => $_SESSION['paid_gems'],
        3 => $_SESSION['coins'],
        4 => $_SESSION['tickets'],
    );

    if ($pack_info) {
        $purchaseResult = true;
        $costAdjusting = true;

        if ($pack_info[0]['type'] == 0) { // 現金のパック
            $item_type = $pack_info[0]['m_item_id'];
            $item_amount = $pack_info[0]['m_item_amount'];
            $purchaseResult = newItemAmount($pdo, $userInventory, $item_type, $item_amount, 1, $user_id);
        } else { // ゲーム内アイテムのパック
            $isPurchasable = checkUserPurchasable($userInventory, $pack_info[0]['cost_m_item_id'], $pack_info[0]['cost_amount']);
            if (!$isPurchasable) {
                $purchaseResult = false;
            } else {
                $cost_type = $pack_info[0]['cost_m_item_id'];
                $cost_amount = $pack_info[0]['cost_amount'];
                $costAdjusting = newItemAmount($pdo, $userInventory, $cost_type, $cost_amount, 0, $user_id);
                foreach ($pack_info as $value) {
                    $pack_item_type = $value['m_item_id'];
                    $pack_item_amount = $value['m_item_amount'];
                    $purchaseResult = newItemAmount($pdo, $userInventory, $pack_item_type, $pack_item_amount, 1, $user_id);
                }
            }
        }
        addPurchaseHistory($pdo, $user_id, $purchaseDate, $pack_id);
        echo $purchaseResult && $costAdjusting ? json_encode(['status' => true, 'packName' => $pack_info[0]['pack_name']]) : json_encode(['status' => false]);
    }
    exit();
}
