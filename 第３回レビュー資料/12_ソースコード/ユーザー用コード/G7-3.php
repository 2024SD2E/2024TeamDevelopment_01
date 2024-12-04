<?php
session_start();
require_once 'db.php'; // データベース接続を読み込み
$previousUrl = $_SERVER['HTTP_REFERER'] ?? null;

// ログイン中のユーザー確認
$user_id = $_SESSION['user_id'] ?? null;

if(!isset($user_id)){
    header('Location: G7.php');
    exit;
};

try {
    // ログインユーザーの購入履歴を取得
    $stmt = $pdo->prepare("
        SELECT 
            p.purchase_id, 
            p.purchase_date, 
            p.total_amount, 
            p.name, 
            p.email, 
            p.phonenumber, 
            p.address, 
            p.postal_code, 
            d.product_id, 
            d.quantity, 
            pr.product_name
        FROM purchase_history p
        JOIN purchase_details d ON p.purchase_id = d.purchase_id
        JOIN products pr ON d.product_id = pr.product_id
        WHERE p.user_id = :user_id
        ORDER BY p.purchase_date DESC
    ");
    $stmt->execute(['user_id' => $user_id]);

    // 結果を配列に格納
    $purchaseHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 購入履歴を購入IDごとにグループ化
    $groupedHistory = [];
    foreach ($purchaseHistory as $item) {
        $groupedHistory[$item['purchase_id']]['info'] = [
            'purchase_date' => $item['purchase_date'],
            'total_amount' => $item['total_amount'],
            'name' => $item['name'],
            'email' => $item['email'],
            'phonenumber' => $item['phonenumber'],
            'address' => $item['address'],
            'postal_code' => $item['postal_code'],
        ];
        $groupedHistory[$item['purchase_id']]['items'][] = [
            'product_name' => $item['product_name'],
            'quantity' => $item['quantity'],
        ];
    }
} catch (PDOException $e) {
    echo "購入履歴の取得中に問題が発生しました。詳細: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入履歴</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G7-3.css">
</head>
<body>
<?php require_once 'header.php'; ?>

<div class="G8box1">
    <a href="<?= htmlspecialchars($previousUrl); ?>" class="back-to-shop">戻る</a><br>
    <h1 class="G8-title">購入履歴</h1>

    <?php if (empty($groupedHistory)): ?>
        <div class="empty-cart-message">
            <p>購入履歴はありません。</p>
        </div>
    <?php else: ?>
        <div class="G8-1flex-container">
            <?php foreach ($groupedHistory as $purchaseId => $purchase): ?>
                <div class="purchase-item">
                    <!-- 購入情報 -->
                    <div class="purchase-info">
                        <table class="purchase-info-table">
                            <tr>
                                <th>注文番号</th>
                                <td><?= htmlspecialchars($purchaseId); ?></td>
                            </tr>
                            <tr>
                                <th>購入日</th>
                                <td><?= htmlspecialchars($purchase['info']['purchase_date']); ?></td>
                            </tr>
                            <tr>
                                <th>合計金額</th>
                                <td>¥<?= number_format($purchase['info']['total_amount']); ?></td>
                            </tr>
                            <tr>
                                <th>購入者名</th>
                                <td><?= htmlspecialchars($purchase['info']['name']); ?></td>
                            </tr>
                            <tr>
                                <th>メールアドレス</th>
                                <td><?= htmlspecialchars($purchase['info']['email']); ?></td>
                            </tr>
                            <tr>
                                <th>電話番号</th>
                                <td><?= htmlspecialchars($purchase['info']['phonenumber']); ?></td>
                            </tr>
                            <tr>
                                <th>住所</th>
                                <td><?= htmlspecialchars($purchase['info']['address']); ?>（郵便番号: <?= htmlspecialchars($purchase['info']['postal_code']); ?>）</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="purchase-item">
                        <div class="purchase-info">
                            <!-- 購入情報テーブル -->
                        </div>
                        <div class="product-list">
                            <h3>購入商品</h3>
                            <table class="product-info-table">
                                <thead>
                                    <tr>
                                        <th>商品名</th>
                                        <th>数量</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($purchase['items'] as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['product_name']); ?></td>
                                            <td><?= htmlspecialchars($item['quantity']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

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
