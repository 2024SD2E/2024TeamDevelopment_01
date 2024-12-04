<?php
session_start();
require_once 'db.php';

// POSTデータを取得またはセッションから復元
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category'] ?? null;
    $sort_order = $_POST['sort_order'] ?? 'price';
    $keyword = trim($_POST['keyword'] ?? '');

    // セッションに保存
    $_SESSION['search_conditions'] = [
        'category' => $category_id,
        'sort_order' => $sort_order,
        'keyword' => $keyword,
    ];
} elseif (isset($_SESSION['search_conditions'])) {
    // セッションから条件を復元
    $category_id = $_SESSION['search_conditions']['category'] ?? null;
    $sort_order = $_SESSION['search_conditions']['sort_order'] ?? 'price';
    $keyword = $_SESSION['search_conditions']['keyword'] ?? '';
} else {
    // 初期状態
    $category_id = null;
    $sort_order = 'price';
    $keyword = '';
}

// ソート順を設定
switch ($sort_order) {
    case 'price':
        $order_by = 'price';
        break;
    case 'cacao_content':
        $order_by = 'cacao_content';
        break;
    default:
        $order_by = 'price';
}

// 検索処理
$_SESSION['search_results_url'] = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索結果</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G1.css">
</head>
<body>
<?php require_once 'header.php'; ?>

<div class="toptext">検索結果</div>

<section class="product-list">
    <?php
    if (!empty($keyword)) {
        $sql = "SELECT * FROM products WHERE product_name LIKE :keyword ORDER BY $order_by";
        $params = [':keyword' => "%$keyword%"];
    } elseif (!empty($category_id)) {
        $sql = "SELECT * FROM products WHERE category_id = :category_id ORDER BY $order_by";
        $params = [':category_id' => $category_id];
    } else {
        echo '<div class="no-results"><p>検索条件を入力してください。</p></div>';
        exit;
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        if (empty($products)) {
            echo '<div class="no-results"><p>該当する商品が見つかりませんでした。</p></div>';
        } else {
            foreach ($products as $row) {
                ?>
                <div class="product-item">
                    <form action="G6-2.php" method="POST">
                        <input type="hidden" name="type" value="<?php echo htmlspecialchars($row['product_id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="back_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8'); ?>">
                        <button type="submit" class="no-style">
                            <img src="<?php echo htmlspecialchars($row['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
                        </button>
                    </form>
                    <p><?php echo htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <span>￥<?php echo number_format($row['price']); ?></span>
                    <?php if ($order_by == 'cacao_content'): ?>
                        <br><span><?php echo $row['cacao_content']; ?>%</span>
                    <?php endif; ?>
                </div>
                <?php
            }
        }
    } catch (PDOException $e) {
        echo '<p>データベースエラー: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
    }
    ?>
</section>

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
