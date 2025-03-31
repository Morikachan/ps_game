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
    LEFT JOIN m_card_type 
        ON m_card_type.card_type_id = card_info.card_type_id 
    LEFT JOIN m_rarity 
        ON m_rarity.rarity_id = card_info.rarity_id 
        WHERE gacha_id = :gacha_id;";
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

function addCardToUserCardList($pdo, $user_id, $card_id, $add_date)
{
    $sql = "INSERT INTO card_inventory (user_id, card_id, card_exp, add_date) VALUES (:user_id, :card_id, :card_exp, :add_date)";
    try {
        $card_exp_default = 0;

        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':card_id', $card_id);
        $smtp->bindParam(':card_exp', $card_exp_default);
        $smtp->bindParam(':add_date', $add_date);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function getGuaranteedCards($type, $cardsList)
{
    $guaranteedList = $type == 0 ? array_values(array_filter($cardsList, function ($cardInfo) {
        return $cardInfo['name'] == 'SR' ||
            $cardInfo['name'] == 'SSR';
    })) : array_values(array_filter($cardsList, function ($cardInfo) {
        return $cardInfo['name'] == 'SSR';
    }));
    return $guaranteedList;
}

function getRandomCharacter($data)
{
    $weightedPool = [];

    foreach ($data as $entry) {
        for ($i = 0; $i < $entry['weight']; $i++) {
            $weightedPool[] = $entry['card_id'];
        }
    }

    return $weightedPool[array_rand($weightedPool)];
}

function pullOne($cardsList, $pdo, $user_id, $pullDate)
{
    $randomCharacterId = getRandomCharacter($cardsList);
    $cardIndex = array_search($randomCharacterId, array_column($cardsList, 'card_id'));

    if (is_numeric($cardIndex)) {
        addCardToUserCardList($pdo, $user_id, $cardsList[$cardIndex]['card_id'], $pullDate);
        return $cardsList[$cardIndex];
    } else {
        echo "No match found.";
    }
}

function pullTen($cardsList, $cardsListGuaranteed, $pdo, $user_id, $pullDate)
{
    $result = array();
    for ($i = 0; $i < 9; $i++) {
        $card = pullOne($cardsList, $pdo, $user_id, $pullDate);
        array_push($result, $card);
    }
    $card10 = pullOne($cardsListGuaranteed, $pdo, $user_id, $pullDate);
    array_push($result, $card10);
    return $result;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = Database::getInstance()->getPDO();
    $gacha_id = $_POST['gacha_id'];
    $gem_amount = $_POST['gacha_gem_amount'];
    $free_gem_amount = $_POST['free_gem_amount'];
    $paid_gem_amount = $_POST['paid_gem_amount'];
    $pullNum = $_POST['pull_num'];
    $user_id = $_SESSION['user']['user_id'];
    $pullDate = date("Y-m-d H:i:s");
    $selectGachaRes = selectGacha($pdo, $gacha_id);
    if ($selectGachaRes) {
        $cardsList = selectGachaCardList($pdo, $gacha_id);
        $guaranteedList = getGuaranteedCards($selectGachaRes["type"], $cardsList);
        $gachaResult = $pullNum == 1 ? pullOne($cardsList, $pdo, $user_id, $pullDate) : pullTen($cardsList, $guaranteedList, $pdo, $user_id, $pullDate);
        if ($gachaResult) {
            $newFreeGem = $_SESSION['free_gems'] - $free_gem_amount;
            $newPaidGem = $_SESSION['paid_gems'] - $paid_gem_amount;
            if ($free_gem_amount != 0 && $paid_gem_amount != 0) {
                updateUserFreeGems($pdo, $user_id, $newFreeGem);
                updateUserPaidGems($pdo, $user_id, $newPaidGem);
            } else if ($free_gem_amount == 0) {
                updateUserPaidGems($pdo, $user_id, $newPaidGem);
            } else {
                updateUserFreeGems($pdo, $user_id, $newFreeGem);
            }
        }
        echo $gachaResult ? json_encode(['status' => true, 'gachaResult' => $gachaResult]) : json_encode(['status' => false]);
    }
    exit();
}
