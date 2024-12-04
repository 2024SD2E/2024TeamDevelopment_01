<?php
require_once 'db.php'; // データベース接続
session_start(); // セッション開始
$update_success = false;

// デバッグ情報を記録する関数
function debug_log($message)
{
    error_log($message, 3, 'debug.log');
}

// セッションからproduct情報を取得
$product = $_SESSION['product'] ?? null;
debug_log("Session product: " . print_r($product, true));

// POSTデータのチェック
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $product) {
    // 必須フィールドのチェック
    $product_name = $product['product_name'] ?? '';
    $description = $product['description'] ?? '';
    $category_id = isset($product['category_id']) ? intval($product['category_id']) : 0;
    $price = isset($product['price']) ? intval($product['price']) : 0;
    $cacao_content = isset($product['cacao_content']) ? intval($product['cacao_content']) : 0;
    $image_url = $product['image_url'] ?? '';

    debug_log("Product data: " . print_r($product, true));

    // エラーチェック
    if ($product_name && $category_id > 0 && $price > 0) {
        try {
            // 画像の処理
            $final_image_url = $product['image_url'] ?? ''; // セッションから直接取得
            if (!$final_image_url) {
                debug_log("No image file or invalid image data: " . $final_image_url);
                $error_message = 'ファイルのアップロードに失敗しました。';
            }
            // 挿入クエリ
            $stmt = $pdo->prepare("INSERT INTO products (product_name, description, category_id, price, cacao_content, image_url) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([
                $product_name,
                $description,
                $category_id,
                $price,
                $cacao_content,
                $final_image_url
            ]);
            debug_log("SQL Error: " . implode(", ", $stmt->errorInfo()));

            // 挿入成功
            if ($result) {
                $update_success = true;
                $product_id = $pdo->lastInsertId(); // 新しく挿入された商品のIDを取得
                debug_log("Product inserted successfully. ID: " . $product_id);
                // セッションのクリア
                unset($_SESSION['product']);
            } else {
                throw new Exception('データの挿入に失敗しました。 Error: ' . implode(", ", $stmt->errorInfo()));
            }
        } catch (Exception $e) {
            $error_message = "エラー: " . $e->getMessage();
            debug_log("Error occurred: " . $error_message);
        }
    } else {
        $error_message = "すべての必須項目を入力してください。";
        debug_log("Validation failed: " . $error_message);
    }
} else {
    // 不正なアクセス
    debug_log("Invalid access attempt");
    header('Location: KG2-2-2-1.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登録完了画面</title>
    <link rel="stylesheet" href="./css/kcommon.css">
    <link rel="stylesheet" href="./css/KG2-2-2-3.css">
</head>

<body>
    <header>
        <?php require_once 'Kheader.php'; ?>
        <div class="header-container">
            <div class="header-text" align="center">
                <span>登録完了画面</span>
            </div>
        </div>
    </header>

    <h1>登録結果</h1>
    <hr><br>

    <?php if ($update_success): ?>
        <p>新しい商品（ID: <?= htmlspecialchars($product_id) ?>）の登録が完了しました。</p>
        <?php if (!empty($final_image_url)): ?>
            <p>アップロードされた画像</p>
                <p><img src="<?= htmlspecialchars($final_image_url) ?>" alt="商品画像" width="200" height="200" ></p>

        <?php else: ?>
            <p>画像はアップロードされませんでした。</p>
        <?php endif; ?>
        <a href="./KG2-2.php" class="back-link">商品一覧に戻る</a>
    <?php else: ?>
        <p style="color: red;">登録に失敗しました。</p>
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