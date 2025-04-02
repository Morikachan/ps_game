<?php
require_once '../core/Database.php';

session_start();

function searchMail($pdo, $email)
{
    $sql = "SELECT * FROM users_info WHERE email=:email";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function selectUserData($pdo, $email)
{
    $sql = "SELECT * FROM users_info WHERE email = :email";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function createUser($pdo, $email, $password, $username, $registrationDate)
{
    $sql = "INSERT INTO users_info (email, password, username, registration_date, user_exp, last_login) 
    VALUES (:email, :password, :username, :registration_date, :user_exp, :last_login)";
    try {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $lastLoginDefault = '00-00-00 00:00:00';
        $userExpDefault = 0;

        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':email', $email);
        $smtp->bindParam(':password', $passwordHash);
        $smtp->bindParam(':username', $username);
        $smtp->bindParam(':registration_date', $registrationDate);
        $smtp->bindParam(':last_login', $lastLoginDefault);
        $smtp->bindParam(':user_exp', $userExpDefault);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function addInventoryCard($pdo, $user_id, $card_id, $add_date)
{
    $sql = "INSERT INTO card_inventory (user_id, card_id, add_date, card_exp) VALUES (:user_id, :card_id, :add_date, :card_exp)";
    try {
        $cardExpDefault = 0;

        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':card_id', $card_id);
        $smtp->bindParam(':add_date', $add_date);
        $smtp->bindParam(':card_exp', $cardExpDefault);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function addHomeChar($pdo, $user_id, $card_id)
{
    $sql = "INSERT INTO user_home (user_id, home_card_id) VALUES (:user_id, :home_card_id)";
    try {
        $smtp = $pdo->prepare($sql);

        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':home_card_id', $card_id);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

function addInventoryItem($pdo, $user_id, $item_id, $amount)
{
    $sql = "INSERT INTO users_inventory (user_id, item_id, amount) VALUES (:user_id, :item_id, :amount)";
    try {
        $smtp = $pdo->prepare($sql);
        $smtp->bindParam(':user_id', $user_id);
        $smtp->bindParam(':item_id', $item_id);
        $smtp->bindParam(':amount', $amount);
        return $smtp->execute();
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];

    date_default_timezone_set('Asia/Tokyo');
    $formattedRegistrationDate = date("Y-m-d H:i:s");

    // $_SESSION['error'] =  array();

    $pdo = Database::getInstance()->getPDO();
    $user = searchMail($pdo, $email);
    if ($user) {
        $_SESSION['error'] = 'ユーザがすでに存在します';
        header("Location: ./registration.php");
    } else {
        $result = createUser($pdo, $email, $password, $username, $formattedRegistrationDate);
        if ($result) {
            // create user inventory for 5 cards R from id 1 to 6
            // add to home char with 1-st id
            $newUserData = selectUserData($pdo, $email);
            for ($i = 1; $i <= 6; $i++) {
                addInventoryCard($pdo, $newUserData['user_id'], $i, $formattedRegistrationDate);
            }
            addHomeChar($pdo, $newUserData['user_id'], 1);
            addInventoryItem($pdo, $newUserData['user_id'], 1, 2500);
            addInventoryItem($pdo, $newUserData['user_id'], 2, 0);
            addInventoryItem($pdo, $newUserData['user_id'], 3, 100000);
            addInventoryItem($pdo, $newUserData['user_id'], 4, 100);
            echo json_encode(['status' => true]);
            session_destroy();
        } else {
            echo json_encode(['status' => false]);
        }
    }
}
