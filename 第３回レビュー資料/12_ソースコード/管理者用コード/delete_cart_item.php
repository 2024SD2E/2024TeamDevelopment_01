<?php
session_start();
require_once 'db.php';

// データベースからアイテムを削除
if (isset($_POST['cartitem_id'])) {
    $cartitem_id = $_POST['cartitem_id'];

    try {
        // アイテムをcartsitemテーブルから削除
        $stmt = $pdo->prepare("DELETE FROM cartsitem WHERE cartitem_id = :cartitem_id");
        $stmt->execute(['cartitem_id' => $cartitem_id]);

        // 削除後にカートページにリダイレクト
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } catch (PDOException $e) {
        echo "削除に失敗しました: " . $e->getMessage();
    }
}
?>
