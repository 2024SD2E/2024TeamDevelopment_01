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
    $product['stock'] = $_POST['stock'];
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
    <link rel="stylesheet" href="./css/KG2-2-4-2.css">
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
            <a href="KG2-2-4-1.php" class="header-back">≺戻る</a>
            <h2 class="header-title">商品更新</h2>
        </div>

        <form method="POST" action="KG2-2-4-3.php" enctype="multipart/form-data" class="product-form">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">

            <label>
                <div class="text-lable">
                    <span class="textbox-label">商品ID
            </label>
            <input type="text" class="textbox" id="product_id" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>" readonly>

            <span class="textbox-label">商品名</label>
                <input type="text" class="textbox" id="product_name" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" readonly>

                <span class="textbox-label">商品数</label>
                    <input type="number" class="textbox" id="stock" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>
                    </div>
                    </label>

                    <div class="area">
                        <button type="submit" class="check" name="check">更新</button>
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