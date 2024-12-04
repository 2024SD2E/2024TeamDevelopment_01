<?php
require_once 'db.php'; // データベース接続
session_start();

// 商品情報取得処理
$product = null;
$error = null;

// セッションデータのチェック
if (isset($_SESSION['product'])) {
    $product = $_SESSION['product'];
}

if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    try {
        // データベースから該当商品の情報を取得
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            $error = "該当する商品が見つかりませんでした。";
        } else {
            // セッションに保存
            $_SESSION['product'] = $product;
        }
    } catch (PDOException $e) {
        $error = "データベースエラー: " . $e->getMessage();
    }
} elseif (!$product) {
    $error = "商品IDが無効です。";
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品更新</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/KG2-2-4-1.css">
</head>

<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
        <div class="header-text">
            <span>管理者サイト</span>
        </div>
    </header>

    <main>
        <div class="header-inner">
            <a href="KG2-2.php" class="header-back">≺戻る</a>
            <h2 class="header-title">商品更新</h2>
        </div>
        
        <?php if ($error): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($product): ?>
            <form method="POST" action="KG2-2-4-2.php" enctype="multipart/form-data" class="product-form">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">

                <label>
                    <div class="text-lable">
                        <span class="textbox-label">商品ID</span>
                        <input type="text" class="textbox" id="product_id" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>" readonly>
                        <span class="textbox-label">商品名</span>
                        <input type="text" class="textbox" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>
                        <span class="textbox-label">商品数</span>
                        <input type="number" class="textbox" id="stock" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>
                    </div>
                </label>

                <div class="area">
                    <button type="submit" class="check" name="check">確認画面へ</button>
                </div>
            </form>
        <?php endif; ?>
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