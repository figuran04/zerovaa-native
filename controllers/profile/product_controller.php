<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['is_admin']));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action'])) {
    switch ($_POST['action']) {
      case 'edit':
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);

        if (empty($name) || empty($description) || $price <= 0 || $stock < 0) {
          header("Location: ../../views/edit_product?id=$id&status=error");
          exit;
        }

        // Pastikan produk ada dan ambil pemiliknya
        $query = "SELECT user_id FROM products WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        if (!$product) {
          header("Location: ../../views/profile?status=notfound");
          exit;
        }

        // Pastikan hanya admin atau pemilik produk yang bisa edit
        if (!$is_admin && $product['user_id'] != $user_id) {
          header("Location: ../../views/profile?status=unauthorized");
          exit;
        }

        // Update produk
        $query = "UPDATE products SET name=?, description=?, price=?, stock=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssdii", $name, $description, $price, $stock, $id);

        if ($stmt->execute()) {
          $status = "success";
        } else {
          $status = "error";
        }

        header("Location: ../../views/profile?status=$status");
        exit;
    }
  }
}
