<?php $__env->startSection('title', 'Keranjang - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-5 pb-12 px-6">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Keranjang</h1>
        <p class="text-gray-500 text-sm">Produk yang anda tambahkan ke keranjang</p>
    </div>

    <?php if($keranjangs->isEmpty()): ?>
    <div class="py-24 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
        <i class="fa-solid fa-cart-shopping text-5xl text-gray-200 mb-4"></i>
        <p class="text-gray-400 font-bold uppercase text-sm tracking-widest">Keranjang masih kosong.</p>
        <a href="<?php echo e(route('agen.produk.index')); ?>" class="mt-4 inline-block bg-[#58CC02] text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-[#46A302] transition">
            Mulai Belanja
        </a>
    </div>
    <?php else: ?>

    <div class="space-y-4 mb-8" id="keranjang-list">
        <?php $__currentLoopData = $keranjangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="keranjang-item bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4" data-id="<?php echo e($item->id); ?>">
            <input type="checkbox" class="item-checkbox w-5 h-5 rounded accent-[#58CC02] cursor-pointer shrink-0" onchange="hitungTotal()">

            <a href="<?php echo e(route('agen.produk.show', $item->produk->id)); ?>" class="w-20 h-20 shrink-0 rounded-xl overflow-hidden bg-gray-50 border border-gray-100 flex items-center justify-center">
                <?php if($item->produk->fotoProduk): ?>
                    <img src="<?php echo e(asset('storage/' . $item->produk->fotoProduk)); ?>" class="w-full h-full object-cover" alt="<?php echo e($item->produk->namaProduk); ?>">
                <?php else: ?>
                    <i class="fa-solid fa-image text-2xl text-gray-200"></i>
                <?php endif; ?>
            </a>

            <div class="flex-1 min-w-0">
                <div class="grid grid-cols-2 md:grid-cols-6 gap-2 items-center">
                    <div class="col-span-2 md:col-span-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-0.5">Nama Produk</p>
                        <p class="font-semibold text-gray-800 text-sm leading-snug"><?php echo e($item->produk->namaProduk); ?></p>
                    </div>

                    <div class="hidden md:block">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-0.5">Jenis</p>
                        <p class="text-sm text-gray-700 font-medium"><?php echo e($item->produk->kategori->jenisKategori); ?></p>
                    </div>

                    <div class="hidden md:block">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-0.5">Mutu</p>
                        <p class="text-sm text-gray-700 font-medium"><?php echo e($item->produk->kategori->mutu); ?></p>
                    </div>

                    <div class="hidden md:block">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-0.5">Berat</p>
                        <p class="text-sm text-gray-700 font-medium"><?php echo e($item->produk->kategori->karung); ?> Kg</p>
                    </div>

                    <div class="hidden md:block">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-0.5">Harga</p>
                        <p class="text-sm font-bold text-gray-800">Rp <?php echo e(number_format($item->produk->harga, 0, ',', '.')); ?></p>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-3 flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase">Jumlah</p>
                        <div class="flex items-center gap-1.5">
                            <button onclick="kurangJumlah('<?php echo e($item->id); ?>', this)"
                                class="w-7 h-7 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition text-xs font-bold">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <span class="jumlah-val font-bold text-gray-800 text-sm w-6 text-center"><?php echo e($item->jumlah); ?></span>
                            <button onclick="tambahJumlah('<?php echo e($item->id); ?>', this)"
                                class="w-7 h-7 rounded-full bg-[#58CC02] hover:bg-[#46A302] text-white flex items-center justify-center transition text-xs font-bold">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Subtotal</p>
                            <p class="subtotal-val font-bold text-gray-900 text-sm">Rp <?php echo e(number_format($item->produk->harga * $item->jumlah, 0, ',', '.')); ?></p>
                        </div>

                        <button onclick="hapusItem('<?php echo e($item->id); ?>', this)"
                            class="w-9 h-9 rounded-xl bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition shadow-sm">
                            <i class="fa-solid fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <p class="text-gray-500 text-sm font-semibold mb-1">Total Harga :</p>
            <p class="text-2xl font-bold text-gray-900" id="total-harga">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></p>
        </div>
        <a href="<?php echo e(route('agen.checkout.index')); ?>" id="btn-checkout"
            class="bg-[#58CC02] hover:bg-[#46A302] text-white px-10 py-3 rounded-xl font-bold text-base transition shadow-md">
            Checkout Pesanan
        </a>
    </div>
    <?php endif; ?>
</div>

<div class="fixed bottom-5 right-5 flex flex-col gap-2 z-50" id="notif-container"></div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

function showNotif(message, success = true) {
    const container = document.getElementById('notif-container');
    const notif = document.createElement('div');
    notif.className = `flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border ${success ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'}`;
    notif.innerHTML = `
        <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full ${success ? 'bg-green-600' : 'bg-red-500'} text-white">
            <i class="fa-solid ${success ? 'fa-check' : 'fa-xmark'} text-sm"></i>
        </div>
        <div class="ms-3">
            <div class="text-sm font-bold ${success ? 'text-green-800' : 'text-red-800'}">${success ? 'Berhasil' : 'Gagal'}</div>
            <div class="text-xs ${success ? 'text-green-700' : 'text-red-700'} mt-0.5">${message}</div>
        </div>
    `;
    container.appendChild(notif);
    notif.style.cssText = 'opacity:0;transform:translateX(20px);transition:all 0.5s ease';
    setTimeout(() => notif.style.cssText = 'opacity:1;transform:translateX(0);transition:all 0.5s ease', 100);
    setTimeout(() => {
        notif.style.cssText = 'opacity:0;transform:translateX(20px);transition:all 0.5s ease';
        setTimeout(() => notif.remove(), 500);
    }, 4000);
}

function tambahJumlah(id, btn) {
    fetch(`/agen/keranjang/${id}/tambah`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.jumlah !== undefined) {
            const row = btn.closest('.keranjang-item');
            row.querySelector('.jumlah-val').textContent = data.jumlah;
            row.querySelector('.subtotal-val').textContent = data.subtotal;
            document.getElementById('total-harga').textContent = data.total;
        }
        showNotif(data.message, !!data.jumlah);
    })
    .catch(() => showNotif('Terjadi kesalahan.', false));
}

function kurangJumlah(id, btn) {
    fetch(`/agen/keranjang/${id}/kurang`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.jumlah !== undefined) {
            const row = btn.closest('.keranjang-item');
            row.querySelector('.jumlah-val').textContent = data.jumlah;
            row.querySelector('.subtotal-val').textContent = data.subtotal;
            document.getElementById('total-harga').textContent = data.total;
        }
        showNotif(data.message, !!data.jumlah);
    })
    .catch(() => showNotif('Terjadi kesalahan.', false));
}

function hapusItem(id, btn) {
    if (!confirm('Hapus produk ini dari keranjang?')) return;

    fetch(`/agen/keranjang/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        const row = btn.closest('.keranjang-item');
        row.style.cssText = 'opacity:0;transform:translateX(20px);transition:all 0.4s ease';
        setTimeout(() => {
            row.remove();
            document.getElementById('total-harga').textContent = data.total;
            if (!document.querySelector('.keranjang-item')) {
                location.reload();
            }
        }, 400);
        showNotif(data.message);
    })
    .catch(() => showNotif('Terjadi kesalahan.', false));
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\agen\keranjang\index.blade.php ENDPATH**/ ?>