<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: G8-1.php");
    exit;
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_token = isset($_COOKIE['session_token']) ? $_COOKIE['session_token'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_SESSION['form_data']['name'];
    $email = $_SESSION['form_data']['email'];
    $phone = $_SESSION['form_data']['phone'];
    $postalCode = $_SESSION['form_data']['postalCode'];
    $address = $_SESSION['form_data']['address'];
    $total = $_POST['total'];

    unset($_SESSION['form_data']);

    if (isset($_SESSION['user_id'])) {
        $sql = $pdo->prepare('SELECT cart_id FROM carts WHERE user_id = :user_id');
        $sql->execute(['user_id' => $user_id]);
    } else {
        $sql = $pdo->prepare('SELECT cart_id FROM carts WHERE session_token = :session_token');
        $sql->execute(['session_token' => $_COOKIE['session_token']]);
    }

    $user = $sql->fetch();
    $cart_id = $user['cart_id'];

    // 購入履歴を登録
    $sql = $pdo->prepare('INSERT INTO purchase_history (user_id, cart_id, total_amount, name, postal_code, address, email, phonenumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $result = $sql->execute([$user_id, $cart_id, $total, $name, $postalCode, $address, $email, $phone]);
    $purchase_id = $pdo->lastInsertId();

    // カートアイテムを取得
    $sql = $pdo->prepare('SELECT product_id, quantity, price FROM cartsitem WHERE cart_id = :cart_id');
    $sql->execute(['cart_id' => $cart_id]);
    $cart_items = $sql->fetchAll();

    // 購入明細を登録
    $sql = $pdo->prepare('INSERT INTO purchase_details (purchase_id, product_id, quantity, price) VALUES (?, ?, ?, ?)');
    foreach ($cart_items as $item) {
        $sql->execute([$purchase_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    // カートアイテムを削除
    $sql = $pdo->prepare('DELETE FROM cartsitem WHERE cart_id = :cart_id');
    $sql->execute(['cart_id' => $cart_id]);

    $pdo = null;
}
?>

<!DOCTYPE html>
<html lang="ja">
<header>
    <?php require_once 'Kheader.php'; ?>
    <div class="header-text">
        <span>会員登録</span>
    </div>
</header>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント情報変更</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/G7-1-4.css">
</head>
<body>
    <div class="touroku">
        <h1>購入完了</h1>
    </div>
    <div class="arigatou">
        <h3>ありがとうございます！</h3>
    </div>
    <div class="form-submit">
        <?php if(isset($_SESSION['user_id'])){ ?>
        <a href="G7.php" class="button-link">マイページ</a>
        <?php }else{ ?>
        <a href="G1.php" class="button-link">トップページ</a>
        <?php } ?>
    </div>
</body>
<footer>
    <div class="footer-container">
        <a href="#"><div class="footer-logo">
            <span>chocolate</span>
            <span>GARDEN</span>
        </div></a>
        <div class="footer-text">
            <span>生チョコレート専門店オンラインショップ</span>
        </div>
    </div>
</footer>
</html>
