<?php
require_once 'db.php'; // データベース接続を利用
session_start(); // セッション開始

// セッションデータの読み込み
// $product = $_SESSION['product'] ?? null;
$_SESSION['product'] = null;

// POSTデータの更新
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product['product_id'] = $_POST['product_id'];
    $product['product_name'] = $_POST['product_name'];
    $product['description'] = $_POST['description'];
    $product['category_id'] = $_POST['category_id'];
    $product['price'] = $_POST['price'];
    $product['cacao_content'] = $_POST['cacao_content'];
    $_SESSION['product'] = $product; // 更新内容をセッションに保存
}

if (!$product) {
    header('Location: KG2-2-1-1.php'); // 商品情報がない場合は戻る
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修正確認画面</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/KG2-2-1-2.css">
</head>

<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
        <div class="header-text">
            <span>修正確認画面</span>
        </div>
    </header>

    <main>
        <div class="header-inner">
            <a href="KG2-2-1-1.php" class="header-back">≺戻る</a>
            <h2 class="header-title">商品更新確認</h2>
        </div>

        <form method="POST" action="./KG2-2-1-3.php" enctype="multipart/form-data" class="product-form">
            <div class="text-label">

                <span class="textbox-label">商品ID</span>
                <input type="text" class="textbox" id="product_id" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>" readonly>

                <span class="textbox-label">商品名</span>
                <input type="text" class="textbox" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>">

                <span class="textbox-label">商品詳細</span>
                <textarea id="description" name="description" rows="7" cols="35"><?= htmlspecialchars($product['description']) ?></textarea>

                <span class="textbox-label">種別</span>
                <select id="category" name="category_id">
                    <option value="1" <?= $product['category_id'] == 1 ? 'selected' : '' ?>>チョコレート</option>
                    <option value="2" <?= $product['category_id'] == 2 ? 'selected' : '' ?>>焼き菓子</option>
                    <option value="3" <?= $product['category_id'] == 3 ? 'selected' : '' ?>>ケーキ</option>
                    <option value="4" <?= $product['category_id'] == 4 ? 'selected' : '' ?>>その他</option>
                </select>

                <span class="textbox-label">価格</span>
                <input type="number" class="textbox" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>">

                <span class="textbox-label">カカオ含有量</span>
                <input type="number" class="textbox" id="cacao_content" name="cacao_content" value="<?= htmlspecialchars($product['cacao_content']) ?>">

                <label for="image" class="form-label">商品画像</label>
                <input type="file" class="textbox" id="image_url" name="image_url">

                <div class="area">
                    <button type="submit" class="check" name="check">更新確認</button>
                </div>
            </div>
        </form>
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