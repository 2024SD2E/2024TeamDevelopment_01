<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header('Location: G7.php');
    exit;
}

session_start();
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['change_info'])) {
        header('Location: G7-2.php');
        exit;
    } elseif (isset($_POST['logout'])) {
        session_destroy();
        header('Location: G7.php');
        exit;
    } elseif (isset($_POST['purchase_history'])) {
        header('Location: G7-3.php');
        exit;
    }
}

// データベース接続
require_once 'db.php';

try {
    // 最新の購入履歴の商品を取得
    $stmt = $pdo->prepare("
        SELECT pd.product_id, p.product_name, p.price, p.image_url
        FROM purchase_details pd
        JOIN purchase_history ph ON pd.purchase_id = ph.purchase_id
        JOIN products p ON pd.product_id = p.product_id
        WHERE ph.user_id = :user_id
        ORDER BY ph.purchase_date DESC
        LIMIT 2
    ");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $recent_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 配列が空の場合はデフォルトの商品を取得
    if (empty($recent_products)) {
        $default_stmt = $pdo->query("
            SELECT product_id, product_name AS product_name, price, image_url
            FROM products
            WHERE product_id IN (1, 2);
        ");
        $recent_products = $default_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    $recent_products = [];
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G7-1.css">
</head>
<body>
    <?php require_once 'header.php'; ?>

    <div class="toptext">
        マイページ
    </div>

    <div class="container">
        <!-- サイドバー -->
        <form action="" method="post">
            <div class="sidebar">
                <p class="greeting">こんにちは！<br><?php echo htmlspecialchars($name); ?>さん！</p>
                <button type="submit" name="change_info" value="1">お客様情報変更</button>
                <button type="submit" name="logout" value="1">ログアウト</button>
                <button type="submit" name="purchase_history" value="1">購入履歴</button>
            </div>
        </form>
    
        <!-- おすすめセクション -->
        <div class="recommendation">
            <h2>あなたへのおススメ</h2>
            <div class="product-list">
                <?php if (!empty($recent_products)): ?>
                    <?php foreach ($recent_products as $product): ?>
                        <div class="product">
                            <form action="G6-2.php" method="POST">
                                <input type="hidden" name="type" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                                <button type="submit" class="no-style">
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                </button>
                            </form>
                            <p><?php echo htmlspecialchars($product['product_name']); ?></p>
                            <p class="price">¥<?php echo number_format($product['price']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>表示する商品がありません。</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

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
