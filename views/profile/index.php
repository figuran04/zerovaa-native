<?php
require_once '../../controllers/profile/profile_controller.php';
$pageTitle = "Profil";
ob_start();

if (isset($_GET['id'])) {
  $profile_id = $_GET['id'];
} else {
  $profile_id = $_SESSION['user_id'];
}

$user = getUserById($profile_id);

?>

<style>
  table,
  tr,
  td,
  th {
    border: 1px solid black;
    padding: 4px;
  }
</style>

<?php if ($profile_id == $_SESSION['user_id']) : ?>
  <h2 class="text-2xl font-bold mb-4">Profil</h2>
<?php else : ?>
  <h2 class="text-2xl font-bold mb-4"><?= htmlspecialchars($user['name']); ?></h2>
<?php endif; ?>
<?php if ($profile_id == $_SESSION['user_id']) : ?>
  <p>Selamat datang, <?= htmlspecialchars($_SESSION['user_name']); ?>!</p>
<?php else : ?>
  <p>Profil Pengguna: <?= htmlspecialchars($user['name']); ?></p>
<?php endif; ?>

<h3 class="text-xl font-semibold mt-6">Produk yang Diunggah</h3>

<?php if ($profile_id == $_SESSION['user_id']) : ?>
  <a href="upload_product.php" class="block bg-blue-500 text-white text-center py-2 px-4 rounded mt-4">
    Unggah Produk Baru
  </a>
<?php endif; ?>

<h2>Daftar Produk</h2>
<table>
  <thead>
    <tr>
      <th>Nama</th>
      <th>Gambar</th>
      <th>Deskripsi</th>
      <th>Harga</th>
      <th>Stok</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $products = getProductsByUserId($profile_id);
    foreach ($products as $product) : ?>
      <tr>
        <td><?= htmlspecialchars($product['name']) ?></td>
        <td><img src="../../uploads/<?= htmlspecialchars($product['image']) ?>" alt="" class="aspect-square w-14"></td>
        <td><?= htmlspecialchars($product['description']) ?></td>
        <td><?= number_format($product['price'], 2) ?></td>
        <td><?= $product['stock'] ?></td>
        <td>
          <?php if ($product['user_id'] == $_SESSION['user_id']): ?>
            <a href="edit_product.php?id=<?= $product['id'] ?>" class="underline">Ubah</a>
            <a href="../../controllers/profile/delete_product.php?id=<?= $product['id'] ?>" class="underline">Hapus</a>
          <?php else : ?>
            <a href="../product_detail?id=<?= $product['id'] ?>" class="underline">Lihat detail</a>
          <?php endif; ?>
        </td>


      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php if ($profile_id == $_SESSION['user_id']) : ?>
  <a href="../../controllers/auth/logout_handler.php" class="text-red-500 w-min">Keluar</a>
<?php endif; ?>
<?php
$content = ob_get_clean();
include '../../layout.php';
?>