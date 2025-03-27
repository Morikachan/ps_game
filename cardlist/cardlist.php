<?php
require_once '../core/Database.php';

session_start();

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
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

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="./cardlist-style.css">
    <script src="../core/bgmPlay.js" defer></script>
    <script src="../core/pageBack.js" defer></script>
    <script src="./cardlist.js" defer></script>
    <script src="https://kit.fontawesome.com/f8fcf0ba93.js" crossorigin="anonymous"></script>
    <title>カード一覧ページ</title>
</head>

<body>
    <div class="game-container">
        <header>
            <div class="header-left">
                <p><?php echo $_SESSION['user']['username'] ?></p>
            </div>
            <div class="header-right">
                <div class="header-bar lvl">
                    <!-- lvl bar -->
                    <div class="level-container">
                        <p class="user_lvl">
                            <?php echo $_SESSION['userLvl'] ?>
                            <br>
                            <span>レベル</span>
                        </p>
                        <p class="user_exp">
                            <?php echo $_SESSION['user']['user_exp'] ?>/<?php echo $_SESSION['nextLvlValue'] ?>
                        </p>
                    </div>
                    <div class="experience" style="width:<?php echo $_SESSION['nextLvlValuePercent'] ?>%"></div>
                </div>
                <div class="header-bar">
                    <!-- coins -->
                    <p>
                        <img src="../src/gold_coin_img.png" alt="コイン" class="header-bar-icon">
                    </p>
                    <p>
                        <?php echo $_SESSION['coins'] ?>
                    </p>
                </div>
                <div class="header-bar">
                    <!-- gems -->
                    <p>
                        <img src="../src/gem_img.png" alt="ジェム" class="header-bar-icon">
                    </p>
                    <p class="header-gems">
                        <?php echo ($_SESSION['free_gems'] + $_SESSION['paid_gems']) ?>
                        <a href="../shop/shop.php" id="gem_shop" style="color: #FFA3B1; width: 24px; height: 24px; margin-left: 4px;">
                            <i class="fa-duotone fa-solid fa-circle-plus" style="font-size: 24px;"></i>
                        </a>
                    </p>
                </div>
                <button type="button" id="logout" class="header-icon" onclick="location.href='../logout.php'">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </div>
        </header>
        <div class="top-buttons">
            <h1 class="purple-pageTitle left">
                カード一覧
            </h1>
            <button type="button" class="gray-button right active" onclick="location.href='../homepage/homepage.php'">
                <i class="fa-solid fa-house" style="color: #000000;"></i>
            </button>
        </div>
        <main class="container-main-small">
            <div class="main-container scrollbar">
                <div class="cardlist-container" id="cardlist-container">

                </div>
            </div>
        </main>
        <footer class="game-page-footer">
            <button type="button" id="pageBackButton" class="gray-button left">
                <i class="fa-solid fa-left-long" style="color: #FFFFFF;"></i>
            </button>
            <button type="button" id="soundButton" class="gray-button right active">
                <i class="fa-solid fa-volume-high" style="color: #000000;"></i>
            </button>
        </footer>
    </div>
    <audio autoplay loop id="bgm-play">
        <source src="../bgm/sinnesloschen-beam-117362.mp3" type="audio/mpeg">
    </audio>
</body>

</html>