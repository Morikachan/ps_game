<?php
require_once '../core/Database.php';

session_start();

// function selectUserItems($pdo, $user_id)
// {
//     $sql = "SELECT * from users_inventory JOIN m_items ON users_inventory.item_id = m_items.item_id";
//     try {
//         $stmt = $pdo->prepare($sql);
//         $stmt->execute();
//         $levelTable = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         return $levelTable;
//     } catch (PDOException $e) {
//         echo $e->getMessage();
//         return false;
//     }
// }

$userInventory = array(
    [
        // free
        'item_id' => 1,
        'item_name' => 'free',
        'amount' => 2000,
    ],
    [
        // paid
        'item_id' => 2,
        'item_name' => 'paid',
        'amount' => 500,
    ],
    [
        // coins
        'item_id' => 3,
        'item_name' => 'coins',
        'amount' => 100000,
    ],
    [
        // tickets
        'item_id' => 4,
        'item_name' => 'tickets',
        'amount' => 100,
    ],
);
$pdo = Database::getInstance()->getPDO();
// $user_id = $_SESSION["user"]["user_id"];
// $levelsTable = selectUserItems($pdo, $user_id);

$freeIndex = array_search('free', array_column($userInventory, 'item_name'));
$paidIndex = array_search('paid', array_column($userInventory, 'item_name'));
$coinsIndex = array_search('coins', array_column($userInventory, 'item_name'));
$ticketIndex = array_search('tickets', array_column($userInventory, 'item_name'));

echo "free: " . $userInventory[$freeIndex]['amount'] . '<br>';
echo "paid: " . $userInventory[$paidIndex]['amount'] . '<br>';
echo "coins: " . $userInventory[$coinsIndex]['amount'] . '<br>';
echo "ticket: " . $userInventory[$ticketIndex]['amount'] . '<br>';

// echo "User Level: " . $userLevel . '<br>';
// echo "EXP required for next level: " . ($nextLevelExp !== null ? $nextLevelExp : "Max Level Reached") . '<br>';
// $nextLvlProgress = $userExp * 100 / $nextLevelExp;
// echo "Прогресс: " . floor($nextLvlProgress) . '<br>';
