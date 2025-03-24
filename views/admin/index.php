<?php
require_once '../../config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: ../login");
  exit;
}

$pageTitle = "Admin Dashboard";
ob_start();

// Ambil data produk
$sql_products = "SELECT * FROM products";
$result_products = $conn->query($sql_products);

// Ambil data kategori
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

// Ambil data pengguna
$sql_users = "SELECT * FROM users";
$result_users = $conn->query($sql_users);

// Cek status hasil operasi dari query string
$status = isset($_GET['status']) ? $_GET['status'] : null;
?>

<style type="text/tailwindcss">
  table, tr, td, th {
    @apply border text-center;
  }
</style>

<h1><a href="../home" class="text-blue-500 hover:underline">Beranda</a> Admin Dashboard</h1>
<a href="../../controllers/auth/logout_handler.php" class="text-red-500 hover:underline">Logout</a>

<!-- Notifikasi -->
<?php if ($status === "success"): ?>
  <p class="text-green-600">Operasi berhasil!</p>
<?php elseif ($status === "error"): ?>
  <p class="text-red-600">Terjadi kesalahan, coba lagi.</p>
<?php else : ?>
  <p class="text-red-600"></p>
<?php endif; ?>

<!-- Manage Products -->
<h2>Manage Products</h2>

<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Description</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Actions</th>
  </tr>
  <?php while ($row = $result_products->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['id']); ?></td>
      <td><?= htmlspecialchars($row['name']); ?></td>
      <td><?= htmlspecialchars($row['description']); ?></td>
      <td><?= htmlspecialchars($row['price']); ?></td>
      <td><?= htmlspecialchars($row['stock']); ?></td>
      <td>
        <a href="edit_product.php?id=<?= $row['id']; ?>">Edit</a> |
        <a href="delete_product.php?id=<?= $row['id']; ?>" onclick="return confirm('Hapus produk ini?')">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<!-- Manage Categories -->
<h2>Manage Categories</h2>

<!-- Form Tambah Kategori -->
<form action="add_category.php" method="POST">
  <input type="hidden" name="action" value="add">
  <input type="text" name="category_name" placeholder="New Category" class="border" required>
  <button type="submit">Add</button>
</form>

<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Actions</th>
  </tr>
  <?php while ($row = $result_categories->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['id']); ?></td>
      <td><?= htmlspecialchars($row['name']); ?></td>
      <td>
        <a href="edit_category.php?id=<?= $row['id']; ?>">Edit</a> |
        <a href="delete_category.php?id=<?= $row['id']; ?>" onclick="return confirm('Hapus kategori ini?')">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<!-- Manage Users -->
<h2>Manage Users</h2>
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Status</th>
    <th>Actions</th>
  </tr>
  <?php while ($row = $result_users->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['id']); ?></td>
      <td><?= htmlspecialchars($row['name']); ?></td>
      <td><?= htmlspecialchars($row['email']); ?></td>
      <td><?= $row['role']; ?></td>
      <td><?= $row['status']; ?></td>
      <td>
        <a href="toggle_role.php?id=<?= $row['id']; ?>">Toggle Role</a> |
        <a href="block_user.php?id=<?= $row['id']; ?>"><?= ($row['status'] === 'active') ? 'blocked' : 'active'; ?></a> |
        <a href="delete_user.php?id=<?= $row['id']; ?>" onclick="return confirm('Hapus user ini?')">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>