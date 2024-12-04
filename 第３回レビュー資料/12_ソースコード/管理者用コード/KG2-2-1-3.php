<?php
require_once 'db.php'; // データベース接続
session_start(); // セッション開始
$update_success = false;

// POSTデータのチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 必須フィールドのチェック
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product_name = $_POST['product_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $price = isset($_POST['price']) ? intval($_POST['price']) : 0;
    $cacao_content = isset($_POST['cacao_content']) ? intval($_POST['cacao_content']) : 0;

    // エラーチェック
    // if ($product_id && $product_name && $price) {
    if ($product_id >0 ) {
            try {
            // 更新クエリ
            $stmt = $pdo->prepare("UPDATE products SET 
                product_name = ?, 
                description = ?, 
                category_id = ?, 
                price = ?, 
                cacao_content = ? 
                WHERE product_id = ?");
            $stmt->execute([
                $product_name,
                $description,
                $category_id,
                $price,
                $cacao_content,
                $product_id
            ]);

            // 更新成功
            $update_success = true;
        } catch (PDOException $e) {
            $update_success = false;
            $error_message = "データベースエラー: " . $e->getMessage();
        }
    } else {
        $update_success = false;
        $error_message = "すべての必須項目を入力してください。";
    }
} else {
    // 不正なアクセス
    header('Location: KG2-2-1-1.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>更新完了画面</title>
    <link rel="stylesheet" href="./css/kcommon.css">
</head>

<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
        <div class="header-container">
            <div class="header-text" align="center">
                <span>更新完了画面</span>
            </div>
        </div>
    </header>

    <h1>更新結果</h1>
    <hr><br>

    <?php if (isset($update_success) && $update_success): ?>
        <p>商品ID: <?= htmlspecialchars($product_id) ?> の更新が完了しました。</p>
        <a href="./KG2-2-1-1.php" class="back-link">商品検索に戻る</a>
    <?php else: ?>
        <p style="color: red;">更新に失敗しました。</p>
        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <a href="javascript:history.back();" class="back-link">＜ 戻る</a>
    <?php endif; ?>

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
