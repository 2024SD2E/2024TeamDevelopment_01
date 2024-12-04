<?php
    session_start();

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $session_token = isset($_COOKIE['session_token']) ? $_COOKIE['session_token'] : null;

    if ($user_id || $session_token) {
        header('Location: G8-1.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G8.css">
</head>
<body>
    <?php require_once 'header.php'; ?>
    
    <?php
    require_once 'db.php';
    try {
        // 指定したIDの商品を取得
        $stmt = $pdo->prepare("
            SELECT product_id, product_name, price, image_url
            FROM products
            WHERE product_id IN (1, 4, 6, 5, 10);
        ");
        $stmt->execute();
        $specified_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
        $specified_products = [];
    }
    ?>

    <section class="cart-empty">
        <div class="G8box1">
            <img src="./upload/house.png" alt="" class="cart-image">
            <div class="button-container">
                <a href="./G7.php" class="G8btn btn-primary">ご自身のアカウントにサインイン</a>
                <a href="./G7-1-2.php" class="G8btn btn-secondary">アカウントを作成する</a>
            </div>
        </div>
    </section>

    <h2 class="G8-title">おすすめの商品一覧</h2>
    <div class="G8box2">
        <div class="G8flex-container">
            <?php if (!empty($specified_products)): ?>
                <?php foreach ($specified_products as $product): ?>
                    <div class="G8product">
                        <form action="G6-2.php" method="POST">
                            <input type="hidden" name="type" value="<?php echo htmlspecialchars($product['product_id']); ?>">
                            <button type="submit" class="no-style">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="" width="150" height="130">
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
