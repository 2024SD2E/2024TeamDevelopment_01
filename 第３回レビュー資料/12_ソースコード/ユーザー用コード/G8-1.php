<?php
session_start();
require_once 'db.php'; // データベース接続を読み込み
$previousUrl = $_SERVER['HTTP_REFERER'] ?? null;
unset($_SESSION['form_data']);

// ログイン中のユーザー確認
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_token = isset($_COOKIE['session_token']) ? $_COOKIE['session_token'] : null;

$cartItems = []; // カート内アイテムの初期化
$isCartEmpty = true; // カートが空であることを示すフラグ

if ($user_id || $session_token) {
    try {
        if ($user_id) {
            // ログインユーザーのカートアイテムを取得
            $stmt = $pdo->prepare("
                SELECT ci.cartitem_id, ci.product_id, ci.quantity, p.product_name, p.price, p.stock, p.image_url
                FROM cartsitem ci
                JOIN products p ON ci.product_id = p.product_id
                WHERE ci.cart_id = (SELECT cart_id FROM carts WHERE user_id = :user_id)
            ");
            $stmt->execute(['user_id' => $user_id]);
        } else {
            // ゲストユーザーのセッショントークンを基にカートアイテムを取得
            $stmt = $pdo->prepare("
                SELECT ci.cartitem_id, ci.product_id, ci.quantity, p.product_name, p.price, p.stock, p.image_url
                FROM cartsitem ci
                JOIN products p ON ci.product_id = p.product_id
                WHERE ci.cart_id = (SELECT cart_id FROM carts WHERE session_token = :session_token)
            ");
            $stmt->execute(['session_token' => $session_token]);
        }
        

        // 結果を配列に格納
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $isCartEmpty = empty($cartItems); // カートが空かどうか確認

    } catch (PDOException $e) {
        echo "カートアイテム取得時にエラーが発生しました: " . $e->getMessage();
        exit;
    }
} else {
    // ユーザーIDもセッショントークンも存在しない場合はリダイレクト
    header('Location: G8.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ショッピングカート</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G8-1.css">
</head>
<body>
<?php require_once 'header.php'; // ヘッダーを読み込み ?>

<div class="G8box1">
    <a href="<?= htmlspecialchars($previousUrl); ?>" class="back-to-shop">＜ショップに戻る</a><br>
    <h1 class="G8-title">ショッピングカート</h1>

    <?php if ($isCartEmpty): ?>
        <div class="empty-cart-message">
            <p>カート内に現在アイテムはありません。</p>
        </div>
    <?php else: ?>
        <div class="G8-1flex-container">
            <?php
            $subtotal = 0; // 合計金額の初期化

            foreach ($cartItems as $item) {
                // 小計を計算（価格 × 数量）
                $itemTotal = $item['price'] * $item['quantity'];
                $subtotal += $itemTotal;
                ?>
                <div class="cart-item" data-id="<?php echo $item['cartitem_id']; ?>">
                    <!-- 商品画像 -->
                    <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($item['product_name']); ?>" 
                         class="cart-item-image">
                    <!-- 商品名 -->
                    <h2 class="cart-item-name"><?php echo htmlspecialchars($item['product_name']); ?></h2>
                    <!-- 数量選択 -->
                    <select name="quantity" class="quantity-select" 
                            data-price="<?php echo $item['price']; ?>" 
                            data-id="<?php echo $item['cartitem_id']; ?>"
                            data-stock="<?php echo $item['stock']; ?>">
                        <?php 
                        // 在庫数を基にループを制限
                        for ($i = 1; $i <= $item['stock']; $i++) : ?>
                            <option value="<?php echo $i; ?>" 
                                    <?php echo $i == $item['quantity'] ? 'selected' : ''; ?> >
                                <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <!-- 削除ボタン -->
                    <form action="delete_cart_item.php" method="POST" style="display:inline;">
                        <button type="submit" name="cartitem_id" value="<?php echo $item['cartitem_id']; ?>" class="delete-button">削除</button>
                    </form>
                    <!-- 小計 -->
                    <div class="item-subtotal">小計 ¥<span class="item-total"><?php echo number_format($itemTotal); ?></span></div>
                </div>
            <?php } ?>
        </div>

        <!-- 合計金額 -->
        <div class="total">
            <span>合計</span>
            <span>¥<span class="cart-total"><?php echo number_format($subtotal); ?></span></span>
        </div>

        <!-- レジに進むボタン -->
        <form action="G8-1-2.php" method="post">
            <button type="submit" class="checkout-button">レジに進む</button>
        </form>
    <?php endif; ?>
</div>

<script>
    // 数量変更時に小計と合計を更新する
    document.querySelectorAll('.quantity-select').forEach(select => {
        select.addEventListener('change', function () {
            const cartItemId = this.dataset.id;
            const newQuantity = parseInt(this.value);
            const price = parseFloat(this.dataset.price);
            const stock = parseInt(this.dataset.stock);

            // 小計を再計算
            const newItemTotal = price * newQuantity;

            // 小計の表示を更新
            document.querySelector(`.cart-item[data-id="${cartItemId}"] .item-total`).textContent = newItemTotal.toLocaleString();

            // 合計を再計算
            let subtotal = 0;
            document.querySelectorAll('.item-total').forEach(itemTotal => {
                subtotal += parseFloat(itemTotal.textContent.replace(/,/g, ''));
            });

            // 合計金額を更新
            document.querySelector('.cart-total').textContent = subtotal.toLocaleString();

        });
    });

    // 削除ボタンの処理
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const cartItemId = this.value;
            const cartItem = this.closest('.cart-item');

            // フォームを送信して削除
            fetch('delete_cart_item.php', {
                method: 'POST',
                body: new URLSearchParams({ 'cartitem_id': cartItemId })
            })
            .then(response => {
                if (response.ok) {
                    // 画面から削除
                    cartItem.remove();

                    // 合計金額を再計算
                    let subtotal = 0;
                    document.querySelectorAll('.item-total').forEach(itemTotal => {
                        subtotal += parseFloat(itemTotal.textContent.replace(/,/g, ''));
                    });
                    document.querySelector('.cart-total').textContent = subtotal.toLocaleString();
                }
            });
        });
    });
</script>

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
