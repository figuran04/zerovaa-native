<?php
require_once '../../config/init.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login");
  exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['is_admin']));

// Ambil ID produk
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: ../profile?status=error");
  exit;
}

$id = intval($_GET['id']);
$query = "SELECT * FROM products WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Pastikan produk ada
if (!$product) {
  header("Location: ../profile?status=error");
  exit;
}

// Pastikan hanya pemilik atau admin yang bisa edit
if ($is_admin != 1 && $product['user_id'] != $user_id) {
  header("Location: ../profile?status=unauthorized");
  exit;
}
ob_start();
?>
<style type="text/tailwindcss">
  input, textarea, option {
    @apply border;
  }
</style>
<h2>Edit Produk</h2>
<form action="../../controllers/profile/product_controller.php" method="POST" class="flex flex-col gap-1">
  <input type="hidden" name="action" value="edit">
  <input type="hidden" name="id" value="<?= $id ?>">

  <label>Nama:</label>
  <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

  <label>Deskripsi:</label>
  <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

  <label>Harga:</label>
  <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

  <label>Stok:</label>
  <input type="number" name="stock" value="<?= $product['stock'] ?>" required>

  <button type="submit">Update</button>
</form>

<a href="../profile">Kembali</a>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>