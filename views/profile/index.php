<?php
require_once '../../controllers/profile/profile_controller.php';
$pageTitle = "Profil";
ob_start();
?>

<h2 class="text-2xl font-bold mb-4">Profil</h2>
<p>Selamat datang, <?= $_SESSION['user_name']; ?>!</p>

<h3 class="text-xl font-semibold mt-6">Produk yang Diunggah</h3>
<a href="../upload_product/" class="block bg-blue-500 text-white text-center py-2 px-4 rounded mt-4">
  Unggah Produk Baru
</a>
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
    <?php foreach ($products as $product) : ?>
      <tr>
        <td><?= htmlspecialchars($product['name']) ?></td>
        <td><img src="../../uploads/<?= htmlspecialchars($product['image']) ?>" alt="" class="aspect-square w-14"></td>
        <td><?= htmlspecialchars($product['description']) ?></td>
        <td><?= number_format($product['price'], 2) ?></td>
        <td><?= $product['stock'] ?></td>
        <td>
          <?php if ($product['user_id'] == $user_id): ?>
            <a href="../edit_product?id=<?= $product['id'] ?>">Edit</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<a href="../../controllers/auth/logout_handler.php" class="text-red-500 w-min">Logout</a>

<?php
$content = ob_get_clean();
include '../../layout.php';
?>