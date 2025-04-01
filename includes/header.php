<header class="bg-[#E2E6CF] shadow sticky top-0 z-50">
  <div class="xl:container xl:mx-auto text-[#509717] py-4 px-8 flex justify-between items-center">
    <nav class="w-full flex">
      <div class="flex flex-col md:flex-row items-center gap-8 w-full md:w-2/3 bg-yellow-500 justify-between">
        <ul class="flex gap-8 items-center justify-between md:justify-normal w-full bg-red-500">
          <li>
            <h1 class="text-xl font-bold text-[#509717]"><a href="../home">Zerovaa</a></h1>
          </li>
          <li><a href="../categories">Kategori</a></li>
        </ul>
        <form action="../search" method="get" class="relative">
          <input type="text" name="q" placeholder="Cari produk..." class="w-full max-w-sm py-2 px-4 mr-4 bg-gray-100 rounded-full outline-[#509717]" required>
        </form>
      </div>
      <ul class="flex gap-2 items-center flex-nowrap justify-end w-full md:w-1/3">
        <?php if (isset($_SESSION['user_id'])) : ?>
          <li class="hidden md:block"><a href="../../controllers/orders/fetch_orders.php"><i class="ph-bold ph-scroll text-xl mx-4"></i></a></li>
          <li class="hidden md:block"><a href="../cart"><i class="ph-bold ph-shopping-cart text-xl mx-4"></i></a></li>
          <li class="hidden md:block"><a href="../profile?id=<?= $_SESSION['user_id']; ?>"><i class="ph-bold ph-user text-xl mx-4"></i></a></li>
          <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) : ?>
            <li><a href="../admin" class="px-4 py-1 rounded border-2 border-[#509717] bg-[#509717] text-gray-50 flex gap-1"><span class="hidden md:block">Dashboard</span>Admin</a></li>
          <?php endif; ?>

        <?php else : ?>
          <li><a href="../login" class="px-4 py-1 rounded border-2 border-[#509717] hover:bg-[#509717] hover:text-gray-50 transition-all">Masuk</a></li>
          <li><a href="../register" class="px-4 py-1 rounded border-2 border-[#509717] bg-[#509717] text-gray-50">Daftar</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</header>