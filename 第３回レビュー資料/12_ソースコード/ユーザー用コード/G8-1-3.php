<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Location: G8-1.php");
    exit;  
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['form_data'] = $_POST;  // セッションにフォームデータを保存
}

$name = $_SESSION['form_data']['name'];
$email = $_SESSION['form_data']['email'];
$phone = $_SESSION['form_data']['phone'];
$postalCode = $_SESSION['form_data']['postalCode'];
$address = $_SESSION['form_data']['address'];

// POSTデータを取得
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_token = isset($_COOKIE['session_token']) ? $_COOKIE['session_token'] : null;

require_once 'db.php'; // データベース接続を読み込み

$cartItems = []; // カート内アイテムの初期化

if ($user_id || $session_token) {
    try {
        if ($user_id) {
            // ログインユーザーのカートアイテムを取得
            $stmt = $pdo->prepare("SELECT ci.cartitem_id, ci.product_id, ci.quantity, p.product_name, p.price, p.stock, p.image_url
                                   FROM cartsitem ci
                                   JOIN products p ON ci.product_id = p.product_id
                                   WHERE ci.cart_id = (SELECT cart_id FROM carts WHERE user_id = :user_id)");
            $stmt->execute(['user_id' => $user_id]);
        } else {
            // ゲストユーザーのセッショントークンを基にカートアイテムを取得
            $stmt = $pdo->prepare("SELECT ci.cartitem_id, ci.product_id, ci.quantity, p.product_name, p.price, p.stock, p.image_url
                                   FROM cartsitem ci
                                   JOIN products p ON ci.product_id = p.product_id
                                   WHERE ci.cart_id = (SELECT cart_id FROM carts WHERE session_token = :session_token)");
            $stmt->execute(['session_token' => $session_token]);
        }

        // 結果を配列に格納
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $isCartEmpty = empty($cartItems); // カートが空かどうか確認

    } catch (PDOException $e) {
        echo "カートアイテム取得時にエラーが発生しました: " . $e->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>受け取り情報</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G8-1-2.css">
</head>
<body>
<?php require_once 'header.php' ?>


<main class="main-container">
    <a href="./G8-1-2.php" class="back-to-shop">＜戻る</a><br>
    <form action="G8-1-4.php" method="POST" id="addressForm">
        <div class="form-section">
            <div class="form-group">
                <h1>お届け先住所確認</h1>
                <label for="email" class="form-label required">メールアドレス</label>
                <input type="email" id="email" name="email" class="form-input" value="<?= htmlspecialchars($email) ?>" readonly>
            </div>
        </div>

        <div class="form-section">
            <h2 class="form-section-title">お届け先情報</h2>
            
            <div class="form-group">
                <label for="lastName" class="form-label">お名前</label>
                <input type="text" id="lastName" name="name" class="form-input" value="<?= htmlspecialchars($name) ?>" readonly>
            </div>

            <div class="form-group">
                <label for="postalCode" class="form-label">郵便番号</label>
                <input type="text" id="postalCode" name="postalCode" class="form-input" value="<?= htmlspecialchars($postalCode) ?>" pattern="\d{3}-?\d{4}" readonly>
                <div class="form-note">例）123-4567</div>
            </div>

            <div class="form-group">
                <label for="address" class="form-label">住所(都道府県/市町村/番地・号)</label>
                <input type="text" id="address" name="address" class="form-input" value="<?= htmlspecialchars($address) ?>" readonly>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">電話番号</label>
                <input type="tel" id="phone" name="phone" class="form-input" value="<?= htmlspecialchars($phone) ?>" pattern="\d{2,4}-?\d{2,4}-?\d{3,4}" readonly>
                <div class="form-note">例）090-1234-5678</div>
            </div>
        </div>

        <h2>ショッピングカート</h2>

        <div class="cart-items">
            <?php foreach ($cartItems as $item): 
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal; ?>
            <div class="cart-item">
                <img src="<?php echo htmlspecialchars($item['image_url']); ?>">
                <div class="item-details">
                    <h3><?= htmlspecialchars($item['product_name']) ?></h3>
                    <div class="item-controls">
                        <span class="price">数量 <?= $item['quantity']; ?></span>
                        <span class="price">小計 ¥<?= htmlspecialchars(number_format($item['price'] * $item['quantity'])); ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="cart-summary">
            <div class="subtotal">
                <span>小計</span>
                <div class="subtotal-items">
                    <?php foreach ($cartItems as $item): ?>
                    <div><?= htmlspecialchars($item['product_name']) ?></div>
                    <div>¥<?= htmlspecialchars(number_format($item['price'] * $item['quantity'])) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="total">
                <span>合計</span>
                <span><?php echo number_format($subtotal); ?></span>
                <input type="hidden" name="total" value="<?php echo $subtotal; ?>">
            </div>
        </div>

        <button type="submit" class="purchase-button">購入</button>
    </form>
</main>

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

</body>
</html>
