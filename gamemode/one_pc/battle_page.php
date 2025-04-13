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
    <link rel="stylesheet" href="../gamemode-play.css">
    <script src="../../core/bgmPlay.js" defer></script>
    <script src="../../core/pageBack.js" defer></script>
    <script src="./battleOnePc.js" type="module" defer></script>
    <script src="https://kit.fontawesome.com/f8fcf0ba93.js" crossorigin="anonymous"></script>
    <title>ゲーム</title>
</head>

<body>
    <div class="game-container">
        <main class="container-game">
            <div class="game-section" id="game-info-player1">
                <div>
                    <h2>プレイヤー１</h2>
                    <div class="player-turn active" id="player1-turn"></div>
                    <div class="player-hp" id="player1-hp"></div>
                </div>
                <div>
                    <div class="game-attack-buttons active" id="player1-buttons">
                        <button type="button" class="game-button" id="player1-button-normal"><i class="fa-sharp fa-regular fa-burst" style="color: #ffffff;"></i></button>
                        <button type="button" class="game-button" id="player1-button-charge"></button>
                    </div>
                </div>
            </div>

            <div class="card-table">
                <div class="game-section" id="game-cards-player1">

                </div>
                <div class="game-section" id="game-cards-player2">
                </div>
            </div>

            <div class="game-timer" id="game-timer">

            </div>

            <div class="game-section" id="game-info-player2">
                <div>
                    <h2>プレイヤー２</h2>
                    <div class="player-turn" id="player2-turn"></div>
                    <div class="player-hp" id="player2-hp"></div>
                </div>
                <div>
                    <div class="game-attack-buttons" id="player2-buttons">
                        <button type="button" class="game-button" id="player2-button-normal"><i class="fa-sharp fa-regular fa-burst" style="color: #ffffff;"></i></button>
                        <button type="button" class="game-button" id="player2-button-charge"></button>
                    </div>
                </div>
            </div>
        </main>
        <footer class="game-page-footer">
            <div></div>
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