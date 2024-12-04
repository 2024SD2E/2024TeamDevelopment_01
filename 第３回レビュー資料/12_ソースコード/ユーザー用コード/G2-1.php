<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/common.css">
    <link rel="stylesheet" href="./css/G1.css">
</head>

<body>
    <?php require_once 'header.php' ?>

    <div class="toptext">
    <?php
    $type = $_GET['type'];
        switch ($type) {
            case '1':
                echo "チョコレート";
                $id = '1';
                break;
            case '2':
                echo "焼き菓子";
                $id = '2';
                break;
            case '3':
                echo "ケーキ";
                $id = '3';
                break;
            case '4':
                echo "その他";
                $id = '4';
                break;

        }
    ?>
    </div>


    <section class="product-list">

    
        <?php 
            require 'db.php';

            try {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = :category_id");
                $stmt->execute(['category_id' => $id]);

                while ($row = $stmt->fetch()) {
                    echo '<div class="product-item">';
                    echo '<form action="G6-2.php" method="POST">';
                    echo '<input type="hidden" name="type" value="', $row['product_id'], '">';
                    echo '<input type="hidden" name="back_url" value="', htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES, 'UTF-8'), '">';
                    echo '<button type="submit" class="no-style"><img src="', $row['image_url'], '"></button>';
                    echo '</form>';
                    echo '<p>', $row['product_name'], '</p>';
                    echo '<span>￥', number_format($row['price']), '</span>';
                    echo '</div>';
                }
            } catch (PDOException $e) {
                echo "Query failed: " . $e->getMessage();
            }

            $pdo = null;
        ?>

    <form id="productForm" action="G6-2.php" method="POST" style="display: none;">
        <input type="hidden" name="type" id="productType">
    </form>

    <script>
        function postData(type) {
            document.getElementById('productType').value = type;
            document.getElementById('productForm').submit();
        }
    </script>

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
