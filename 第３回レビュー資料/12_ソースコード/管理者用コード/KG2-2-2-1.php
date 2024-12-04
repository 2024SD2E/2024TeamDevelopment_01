<?php
require 'db.php'; // データベース接続
session_start();

// $email = $_SESSION['email'];
// $name = $_SESSION['name'];
// $admin_id = $_SESSION['admin_id'];
// $password = $_SESSION['password'];
// if (!isset($admin_id)) {
//     header('Location: KG1.php');
//     exit;
// };
// セッションからデータを取得
$product = $_SESSION['return_data'] ?? null;

if (isset($_SESSION['product'])) {
    $product = $_SESSION['product'];
}

$stmt = $pdo->prepare("SELECT * FROM categories");
$stmt->execute();
$category = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品追加</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/KG2-2-2-1.css">
    <style>
        .text-label {
            display: flex;
            flex-direction: column;
            /* 縦に並べる */
            align-items: center;
            /* 中央揃え */
        }
    </style>
</head>

<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
        <div class="header-container">
            <div class="header-text" align="center">
                <span>管理者サイト</span>
            </div>
        </div>
    </header>
    <main>
        <div class="header-inner">
            <a href="KG2-2.php" class="header-back" color: blue;>≺戻る</a>
            <h2 class="header-title">商品追加</h2>
        </div>

    <form action="KG2-2-2-2.php" method="post" enctype="multipart/form-data">
    <label>
                <div class="text-lable">
                    <span class="textbox-label">商品名</span>
                    <input type="text" class="textbox" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required><br>

                    <span class="textbox-label">商品詳細</span>
                    <textarea id="description" class="textbox" name="description"  rows="5" cols="30" required><?= htmlspecialchars($product['description']) ?></textarea><br>

                    <span class="textbox-label">種別</span>
                    <select id="category_id" class="textbox2" name="category_id">
                        <?php foreach ($category as $categories): ?>
                            <option value="<?= htmlspecialchars($categories['category_id']) ?>">
                                <?= htmlspecialchars($categories['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br>

                    <span class="textbox-label">価格</span>
                    <input type="number" class="textbox" name="price" value="<?= htmlspecialchars($product['price']) ?>" required><br>

                    <span class="textbox-label">カカオ含有量</span>
                    <input type="number" class="textbox" name="cacao_content" value="<?= htmlspecialchars($product['cacao_content']) ?>" required><br>

                    <span class="textbox-label">商品画像</span>
                    <input type="file" class="textbox" name="file" value="<?= htmlspecialchars($product['image_url']) ?>" required><br>
                </div>
            </label>
            <div class="area">
                    <button type="submit" class="check" name="check">確認画面へ</button>
            </div>
    </form>

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