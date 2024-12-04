<?php
require_once 'db.php'; // データベース接続を利用
session_start(); // セッション開始

// $email = $_SESSION['email'];
// $name = $_SESSION['name'];
// $admin_id = $_SESSION['admin_id'];
// $password = $_SESSION['password'];
// if (!isset($admin_id)) {
//     header('Location: KG1.php');
//     exit;
// };


// セッションデータの読み込み
// $product = $_SESSION['product'] ?? null;
$_SESSION['product'] = null;

// POSTデータの更新
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product['product_name'] = $_POST['product_name'];
    $product['description'] = $_POST['description'];
    $product['category_id'] = $_POST['category_id'];
    $product['price'] = $_POST['price'];
    $product['cacao_content'] = $_POST['cacao_content'];

    if(isset($GET['product'])){
        $product = $_SESSION['product'];
        }

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'upload/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
    
        $file_name = uniqid() . '_' . basename($_FILES['file']['name']); // 一意のファイル名を生成
        $upload_path = $upload_dir . $file_name;
    
        if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path)) {
            $product['image_url'] = $upload_path; // 保存した画像パスを記録
        } else {
            $product['image_url'] = null; // アップロード失敗時
        }
    }
    
    $_SESSION['product'] = $product;
}


if (!$product) {
    header('Location: KG2-2-2-1.php'); // 商品情報がない場合は戻る
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品追加確認画面</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/KG2-2-2-2.css">
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
                <span>商品追加確認画面</span>
            </div>
        </div>
    </header>
<main>
    <div class="header-inner">
    <?php
   $_SESSION['return_data'] = $product;
    ?>
    <a href="KG2-2-2-1.php" class="header-back">≺ 戻る</a>
            <h2 class="header-title">商品追加確認画面</h2>
        </div>

    <form method="POST" action="./KG2-2-2-3.php" enctype="multipart/form-data" class="product-form">
    <label>
    <div class="text-lable">
                <span class="textbox-label">商品名</span>
                <input type="text" class="textbox" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" readonly><br>
            

                <span class="textbox-label">商品詳細</span>
                <textarea id="description" class="textbox" name="description" rows="7" cols="35" readonly><?= htmlspecialchars($product['description']) ?></textarea><br>
          

                <span class="textbox-label">種別</span>
                <input type="text" class="textbox2" name="category_id" value="<?= htmlspecialchars($product['category_id']) ?>" readonly><br>
    

                <span class="textbox-label">価格</span>
                <input type="number" class="textbox" name="price" value="<?= htmlspecialchars($product['price']) ?>" readonly><br>
            

                <span class="textbox-label">カカオ含有量</span>
                <input type="number" class="textbox" name="cacao_content" value="<?= htmlspecialchars($product['cacao_content']) ?>" readonly><br>


                <span class="textbox-label">商品画像</span>
                <?php
                if (isset($product['image_url']) && file_exists($product['image_url'])) {
                    echo '<p><img src="' . htmlspecialchars($product['image_url']) . '" alt="商品画像" width="200" height="200"></p>';
                } else {
                    echo 'ファイルを選択してください。';
                }
                ?>
                <br>
            </div>
            </label>

            <div class="area">
                <button type="submit" class="check">処理画面へ</button>
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