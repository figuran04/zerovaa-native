<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['is_admin']));

// Ambil ID produk dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: ../profile?status=error");
  exit;
}

$id = intval($_GET['id']);

// Ambil data produk yang akan diedit
$query = "SELECT * FROM products WHERE id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

// Pastikan produk ada dan hanya pemilik atau admin yang bisa edit
if (!$product) {
  header("Location: ../profile?status=error");
  exit;
}

if ($product['user_id'] != $user_id && !$is_admin) {
  header("Location: ../profile?status=unauthorized");
  exit;
}

// Ambil semua kategori untuk dropdown
$category_query = "SELECT * FROM categories";
$category_result = $conn->query($category_query);
$categories = $category_result->fetch_all(MYSQLI_ASSOC);

ob_start();
?>
<h2>Edit Produk</h2>

<form action="../../controllers/profile/product_controller.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-1">
  <input type="hidden" name="action" value="edit">
  <input type="hidden" name="id" value="<?= $id ?>">

  <label>Nama Produk:</label>
  <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

  <label>Deskripsi:</label>
  <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea>

  <label>Harga:</label>
  <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

  <label>Stok:</label>
  <input type="number" name="stock" value="<?= $product['stock'] ?>" required>

  <label>Kategori:</label>
  <select name="category_id" required>
    <option value="">Pilih Kategori</option>
    <?php foreach ($categories as $category) : ?>
      <option value="<?= $category['id'] ?>" <?= $product['category_id'] == $category['id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($category['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label>Gambar Produk:</label>
  <input type="file" name="image" id="image" accept="image/*">
  <p>Gambar Saat Ini: <img src="../../uploads/<?= htmlspecialchars($product['image']) ?>" alt="Product Image" class="w-20"></p>

  <button type="submit">Update Produk</button>
</form>

<a href="../profile">Kembali</a>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>