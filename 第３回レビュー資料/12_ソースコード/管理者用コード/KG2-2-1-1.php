<?php
require_once 'db.php'; // データベース接続
session_start();

// 商品情報取得処理
$product = null;
$error = null;

if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    try {
        // データベースから該当商品の情報を取得
        $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            $error = "該当する商品が見つかりませんでした。";
        }
    } catch (PDOException $e) {
        $error = "データベースエラー: " . $e->getMessage();
    }
} else {
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
    <link rel="stylesheet" href="./css/KG2-2-1-1.css">
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

        <form method="POST" action="KG2-2-1-2.php" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">

            <label>
                <div class="text-label">
                    <span class="textbox-label">商品名</span>
                    <input type="text" class="textbox" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>
                    <span class="textbox-label">商品詳細</span>
                    <textarea class="textbox" id="description" name="description" required><?= htmlspecialchars($product['description']) ?></textarea>
                    <span class="textbox-label">種別</span>
                    <select id="category_id" class="textbox" name="category_id">
                        <option value="1" <?= $product['category_id'] == 1 ? 'selected' : '' ?>>チョコレート</option>
                        <option value="2" <?= $product['category_id'] == 2 ? 'selected' : '' ?>>焼き菓子</option>
                        <option value="3" <?= $product['category_id'] == 3 ? 'selected' : '' ?>>ケーキ</option>
                        <option value="4" <?= $product['category_id'] == 4 ? 'selected' : '' ?>>その他</option>
                    </select>
                    <span class="textbox-label">価格</span>
                    <input type="number" class="textbox" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required>
                    <span class="textbox-label">カカオ含有量</span>
                    <input type="number" class="textbox" id="cacao_content" name="cacao_content" value="<?= htmlspecialchars($product['cacao_content']) ?>" required>
                    <span class="textbox-label">商品画像</span>
                    <input type="file" class="textbox" id="image_url" name="image_url">
                </div>
            </label>
             <!-- エラーメッセージを表示 -->
             <div class="passcheck">
                <?php if (!empty($error_message)) : ?>
                    <p class="error-message"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
            </div>

            <div class="area">
                <button type="submit" class="check" name="check">更新確認</button>
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