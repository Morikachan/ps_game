<?php
require_once '../core/Database.php';

session_start();

if (empty($_SESSION['user'])) {
    header("Location: ../index.php");
}

function selectUserData($pdo, $user_id)
{
    $sql = "SELECT * FROM users_info WHERE user_id = :user_id";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function selectUserHomeCharacter($pdo, $user_id)
{
    $sql = "SELECT home_card_id FROM user_home WHERE user_id = :user_id";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $character = $stmt->fetch(PDO::FETCH_ASSOC);
        return $character;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
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

function selectUsername($pdo, $user_id)
{
    $sql = "SELECT username FROM users_info WHERE user_id = :user_id";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $username = $stmt->fetch(PDO::FETCH_ASSOC);
        return $username;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function updateLastLogin($pdo, $user_id, $loginDate)
{
    $sql = "UPDATE users_info SET last_login = :last_login WHERE user_id = :user_id;";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':last_login', $loginDate);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}


$pdo = Database::getInstance()->getPDO();
$user_id = $_SESSION['user']['user_id'];
$_SESSION['user'] = selectUserData($pdo, $user_id);

date_default_timezone_set('Asia/Tokyo');
$loginDate = date("Y-m-d H:i:s");
updateLastLogin($pdo, $user_id, $loginDate);

$homeCharacter_id = selectUserHomeCharacter($pdo, $user_id)['home_card_id'];
$levelsTable = selectLevelRequirements($pdo);

$userExp = $_SESSION['user']['user_exp'];
$userLevel = 1;
$nextLevelExp = null;

$username = selectUsername($pdo, $user_id);
$_SESSION['user']['username'] = $username['username'];

foreach ($levelsTable as $key => $level) {
    if ($userExp < $level["exp_amount"]) {
        $nextLevelExp = $level["exp_amount"];
        break;
    }
    $userLevel = $level["lvl"];
}

$userItems = selectUserItemInventory($pdo, $_SESSION['user']['user_id']);

$_SESSION['homeCharacter'] = $homeCharacter_id;
$_SESSION['userLvl'] = $userLevel;
$_SESSION['nextLvlValue'] = $nextLevelExp;
$_SESSION['nextLvlValuePercent'] = floor($userExp * 100 / $nextLevelExp);

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
    <link rel="stylesheet" href="./homepage-style.css">
    <script src="../core/bgmPlay.js" defer></script>
    <script src="./changeName.js" defer></script>
    <script src="./homeChar.js" defer></script>
    <script src="https://kit.fontawesome.com/f8fcf0ba93.js" crossorigin="anonymous"></script>
    <title>ホームページ</title>
</head>

<body>
    <div class="game-container" style="background-image: url('../src/cards/card_illust/card_<?php echo $_SESSION['homeCharacter'] ?>.jpg')" data-cardId="<?php echo $_SESSION['homeCharacter'] ?>">
        <header>
            <div class="header-left">
                <p><?php echo $_SESSION['user']['username'] ?></p>
                <button type="button" id="changeUsernameOpen" class="header-icon">
                    <i class="fa-solid fa-pencil"></i>
                </button>
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
                <!-- TODO -->
                <!-- <button type="button" id="settings" class="header-icon">
                    <i class="fa-solid fa-gear"></i>
                </button> -->
            </div>
        </header>
        <main>
            <div class="main-container">
                <div class="top-buttons">
                    <!-- change home char -->
                    <!-- <button type="button" onclick="location.href='changeHomeChar.php'" id="changeHomeChar" class="gray-button left"> -->
                    <button type="button" id="changeHomeCharOpen" class="gray-button left">
                        <i class="fa-solid fa-arrows-rotate" style="color: #000000;"></i>
                    </button>
                    <button type="button" id="soundButton" class="gray-button right active">
                        <i class="fa-solid fa-volume-high" style="color: #000000;"></i>
                    </button>
                </div>
            </div>
            <nav class="game-page-nav">
                <a href="../missions/missionlist-page.php">ミッション</a>
                <a href="../ranking/ranking.php">ランキング</a>
                <a href="../cardlist/cardlist.php">カード一覧</a>
                <a href="../gacha/gachalist-page.php">ガチャ</a>
                <!-- <a href="../">ゲーム</a> -->
            </nav>
        </main>
    </div>
    <div id="modalName" class="modal">
        <div class="modal-content">
            <div class="name-field">
                <input type="text" id="homeUsername" name="homeUsername" value=<?php echo $_SESSION['user']['username'] ?>>
                <button type="button" class="modalNameBtn" id="changeNameConfirm"><i class="fa-duotone fa-solid fa-circle-check" style="color: #FFA3B1;"></i></button>
                <button type="button" class="modalNameBtn" id="changeNameClose"><i class="fa-duotone fa-solid fa-circle-xmark" style="color:rgb(134, 134, 134);"></i></button>
            </div>
        </div>
    </div>
    <div id="modalHomeChar" class="modal">
        <div class="modal-content">
            <div class="cardContainer scrollbar">
                <!-- ｊｓ・ｐｈｐでカードを取り出して表示する -->
            </div>
            <div class="modal-buttons">
                <button type="button" class="modalBtn Gray" id="changeCardClose">キャンセル</button>
                <button type="button" class="modalBtn" id="changeCardConfirm">決定</button>
            </div>
        </div>
    </div>
    <audio autoplay loop id="bgm-play">
        <source src="../bgm/sinnesloschen-beam-117362.mp3" type="audio/mpeg">
    </audio>
</body>

</html>