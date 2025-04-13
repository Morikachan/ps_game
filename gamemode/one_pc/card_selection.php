<?php
require_once '../../core/Database.php';

session_start();

if (empty($_SESSION['user'])) {
    header("Location: ../../index.php");
}

function selectLevelRequirements($pdo)
{
    $sql = "SELECT * FROM m_user_lvl";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $levelTable = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $levelTable;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function selectUserItemInventory($pdo, $user_id)
{
    $sql = "SELECT item_id, amount FROM users_inventory WHERE user_id = :user_id";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $userItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $userItems;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

$pdo = Database::getInstance()->getPDO();
$user_id = $_SESSION['user']['user_id'];
$levelsTable = selectLevelRequirements($pdo);

$userExp = $_SESSION['user']['user_exp'];
$userLevel = 1;
$nextLevelExp = null;

foreach ($levelsTable as $key => $level) {
    if ($userExp < $level["exp_amount"]) {
        $nextLevelExp = $level["exp_amount"];
        break;
    }
    $userLevel = $level["lvl"];
}

$userItems = selectUserItemInventory($pdo, $_SESSION['user']['user_id']);

$_SESSION['free_gems'] = $userItems[array_search(1, array_column($userItems, 'item_id'))]['amount'];
$_SESSION['paid_gems'] = $userItems[array_search(2, array_column($userItems, 'item_id'))]['amount'];
$_SESSION['coins'] = $userItems[array_search(3, array_column($userItems, 'item_id'))]['amount'];
$_SESSION['tickets'] = $userItems[array_search(4, array_column($userItems, 'item_id'))]['amount'];

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="../gamemode-select.css">
    <script src="../../core/bgmPlay.js" defer></script>
    <script src="../../core/pageBack.js" defer></script>
    <script src="./createCardSelection.js" type="module" defer></script>
    <script src="https://kit.fontawesome.com/f8fcf0ba93.js" crossorigin="anonymous"></script>
    <title>カード選択</title>
</head>

<body>
    <div class="game-container">
        <div class="top-buttons">
            <h1 class="purple-pageTitle left">
                カード選択
            </h1>
        </div>
        <main class="gamemode-container">
            <div class="section" id="player1">
                <div class="user-game-info">
                    <h2>プレイヤー１</h2>
                    <p>選択: <span id="selected1-count">0</span>/3</p>
                    <p id="timer1">60</p>
                </div>
                <div class="section-cardlist" id="cards1"></div>
                <button class="confirm-btn" id="confirm1" disabled>決定</button>
            </div>

            <div class="section hidden" id="player2">
                <div class="user-game-info">
                    <h2>プレイヤー２</h2>
                    <p>選択: <span id="selected2-count">0</span>/3</p>
                    <p id="timer2">60</p>
                </div>
                <div class="section-cardlist" id="cards2"></div>
                <button class="confirm-btn" id="confirm2" disabled>決定</button>
            </div>
        </main>
        <footer class="game-page-footer">
            <button type="button" class="gray-button right active" onclick="location.href='../../homepage/homepage.php'">
                <i class="fa-solid fa-house" style="color: #000000;"></i>
            </button>
            <button type="button" id="soundButton" class="gray-button right active">
                <i class="fa-solid fa-volume-high" style="color: #000000;"></i>
            </button>
        </footer>
    </div>
    <div id="modalPurchase" class="modal">
        <div class="modal-content">
            <h4 id="modal-shop-title">購入完了</h4>
            <p id="modalPurchaseText"></p>
            <button type="button" class="modalBtn Gray" id="closeModalPurchase">閉じる</button>
        </div>
    </div>
    <audio autoplay loop id="bgm-play">
        <source src="../../bgm/sinnesloschen-beam-117362.mp3" type="audio/mpeg">
    </audio>
</body>

</html>