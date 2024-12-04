<?php


session_start();
$user_id = $_SESSION['user_id'] ?? null;
$productId = $_POST['type'];

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['back_url'])) {
    $_SESSION['back_url'] = $_POST['back_url'];
}
$backLink = $_SESSION['back_url'] ?? $_SERVER['HTTP_REFERER'] ?? '/';

// 商品情報を取得
$sql = $pdo->prepare("SELECT * FROM products WHERE product_id = :product_id");
$sql->execute(['product_id' => $productId]);
$row = $sql->fetch();

// 商品の在庫数を取得
$stock = $row['stock']; // 'stock'カラムに在庫数が格納されていると仮定

// カートに追加ボタンが押された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['is_post']) && $_POST['is_post'] === '1') {
        $quantity = $_POST['quantity'] ?? 1; // 数量を取得、未指定なら1とする

        if ($user_id) {
            // ユーザーがログインしている場合
            // cartsテーブルからcart_idを取得または新規作成
            $cartSql = $pdo->prepare("SELECT cart_id FROM carts WHERE user_id = :user_id");
            $cartSql->execute(['user_id' => $user_id]);
            $cart = $cartSql->fetch();

            if (!$cart) {
                // カートが存在しない場合、新規作成
                $createCartSql = $pdo->prepare("INSERT INTO carts (user_id) VALUES (:user_id)");
                $createCartSql->execute(['user_id' => $user_id]);
                $cart_id = $pdo->lastInsertId();
            } else {
                $cart_id = $cart['cart_id'];
            }
        } else {
            // ユーザーがログインしていない場合
            // セッショントークンを生成し、クッキーに保存
            if (!isset($_COOKIE['session_token'])) {
                $session_token = bin2hex(random_bytes(16));
                setcookie('session_token', $session_token, time() + (2 * 60 * 60), '/'); // 2時間有効
            } else {
                $session_token = $_COOKIE['session_token'];
            }

            // cartsテーブルからsession_tokenに紐づくcart_idを取得または新規作成
            $cartSql = $pdo->prepare("SELECT cart_id FROM carts WHERE session_token = :session_token");
            $cartSql->execute(['session_token' => $session_token]);
            $cart = $cartSql->fetch();

            if (!$cart) {
                // カートが存在しない場合、新規作成
                $createCartSql = $pdo->prepare("INSERT INTO carts (session_token) VALUES (:session_token)");
                $createCartSql->execute(['session_token' => $session_token]);
                $cart_id = $pdo->lastInsertId();
            } else {
                $cart_id = $cart['cart_id'];
            }
        }

        // cartitemsテーブルに商品を追加
        $addCartItemSql = $pdo->prepare(
            "INSERT INTO cartsitem (cart_id, product_id, price, quantity) 
            VALUES (:cart_id, :product_id, :price, :quantity)"
        );
        $addCartItemSql->execute([
            'cart_id' => $cart_id,
            'product_id' => $productId,
            'price' => $row['price'],
            'quantity' => $quantity // 選択された数量を保存
        ]);

        // メッセージを表示してリダイレクト
        echo "<script>alert('カートに商品を追加しました！'); window.location.href = '".$backLink."';</script>";
        exit;  
    }
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G6-2.css">
</head>
<body>

<?php require_once 'header.php' ?>

<main>
    <div class="product-detail">
        <!-- 商品の画像 -->
        <div class="product-image-wrapper">
            <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['product_name']; ?>" class="product-image">
        </div>
    </div>

    <div class="product-info">
        <!-- 商品名 -->
        <div class="product-name-wrapper">
            <h1><?php echo $row['product_name']; ?></h1>
        </div>

        <!-- 価格 -->
        <div class="product-price-wrapper">
            <p><strong>価格:</strong> ¥<?php echo number_format($row['price']); ?></p>
        </div>

        <!-- カカオ -->
        <div class="product-cacao">
            <p><strong>カカオ含有量:</strong> <?php echo $row['cacao_content']; ?>%</p>
        </div>

        <!-- 商品説明 -->
        <div class="product-description-wrapper">
            <p><?php echo $row['description']; ?></p>
        </div>

        <!-- ボタンエリア -->
        <div class="buttons-container">

            <!-- カートに追加ボタン -->
            <form action="" method="post" class="add-to-cart-form">
                <input type="hidden" name="type" value="<?php echo $productId; ?>">
                <input type="hidden" name="is_post" value="1">

                <!-- 数量選択ドロップダウン -->
                <div class="quantity-wrapper">
                    <label for="quantity"><strong>数量:</strong></label>
                    <select name="quantity" id="quantity" required>
                        <?php 
                        // 在庫数に基づいて最大値を決定
                        $maxQuantity = min($stock, 10); // 最大10個まで、在庫数が少ない場合はその数
                        for ($i = 1; $i <= $maxQuantity; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- 送信ボタン -->
                <a href="<?= htmlspecialchars($backLink); ?>" class="add-to-cart-button">戻る</a>
                <button type="submit" class="add-to-cart-button">カートに追加</button>
            </form>
        </div>
    </div>
</main>

<footer>
    <div class="footer-container">
        <a href="#">
            <div class="footer-logo">
                <span>chocolate</span>
                <span>GARDEN</span>
            </div>
        </a>
        <div class="footer-text">
            <span>生チョコレート専門店オンラインショップ</span>
        </div>
    </div>
</footer>

</body>
</html>
