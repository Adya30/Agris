<?php $__env->startSection('title', 'Daftar Produk - AGRIS'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-5 pb-12 px-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
            <p class="text-gray-500 text-sm">Cari dan pilih produk berdasarkan kategori yang tersedia</p>
        </div>
    </div>

    <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <form action="<?php echo e(route('agen.produk.index')); ?>" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Jenis</label>
                <select name="jenis" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Jenis</option>
                    <?php $__currentLoopData = $daftarJenis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($j); ?>" <?php echo e(request('jenis') == $j ? 'selected' : ''); ?>><?php echo e(strtoupper($j)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Mutu</label>
                <select name="mutu" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Mutu</option>
                    <?php $__currentLoopData = $daftarMutu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($m); ?>" <?php echo e(request('mutu') == $m ? 'selected' : ''); ?>>MUTU <?php echo e(strtoupper($m)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="w-full md:flex-1">
                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 ml-1">Isi Karung</label>
                <select name="karung" class="w-full px-4 py-2.5 rounded-xl border border-gray-100 bg-gray-50 outline-none focus:ring-2 focus:ring-[#58CC02] text-sm cursor-pointer appearance-none">
                    <option value="">Semua Ukuran</option>
                    <?php $__currentLoopData = $daftarKarung; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k); ?>" <?php echo e(request('karung') == $k ? 'selected' : ''); ?>><?php echo e($k); ?> Kg</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto bg-gray-800 hover:bg-black text-white px-8 py-2.5 rounded-xl transition font-bold text-sm flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-filter mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2 md:gap-3">
        <?php $__empty_1 = true; $__currentLoopData = $produks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="group bg-white rounded-lg overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition flex flex-col h-full relative">
            <a href="<?php echo e(route('agen.produk.show', $item->id)); ?>" class="relative aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                <?php if($item->fotoProduk): ?>
                    <img src="<?php echo e(asset('storage/' . $item->fotoProduk)); ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="<?php echo e($item->namaProduk); ?>">
                <?php else: ?>
                    <div class="flex items-center justify-center h-full text-gray-200">
                        <i class="fa-solid fa-image text-4xl"></i>
                    </div>
                <?php endif; ?>

                <?php if($item->stok <= 0): ?>
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10">
                        <span class="bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase">Stok Habis</span>
                    </div>
                <?php endif; ?>
            </a>

            <div class="p-2.5 flex flex-col grow">
                <div class="flex flex-wrap gap-1 mb-2">
                    <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded">
                        <?php echo e($item->kategori->jenisKategori); ?>

                    </span>
                    <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded">
                        <?php echo e($item->kategori->karung); ?> Kg
                    </span>
                    <span class="text-[9px] font-bold uppercase text-gray-800 bg-gray-800/10 px-1.5 py-0.5 rounded">
                        <?php echo e($item->kategori->mutu); ?>

                    </span>
                </div>

                <a href="<?php echo e(route('agen.produk.show', $item->id)); ?>" class="grow">
                    <h3 class="text-gray-800 text-15 font-normal line-clamp-2 leading-snug mb-1 min-h-9.5">
                        <?php echo e($item->namaProduk); ?>

                    </h3>
                    <p class="text-gray-900 font-bold text-base mb-0.5">
                        Rp <?php echo e(number_format($item->harga, 0, ',', '.')); ?>

                    </p>
                </a>

                <div class="mt-auto">
                    <div class="flex items-center justify-between pt-2 border-t border-gray-100 flex-wrap gap-1 mb-3">
                        <div class="flex items-center gap-1 text-[11px] text-gray-500 truncate max-w-[70%]">
                            <div class="bg-violet-600 text-white rounded w-3.5 h-3.5 flex items-center justify-center text-[8px] shrink-0">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="truncate font-medium text-gray-500">Tersedia</span>
                        </div>
                        <span class="text-[10px] font-bold <?php echo e($item->stok > 5 ? 'text-gray-500' : 'text-orange-500'); ?> uppercase tracking-tight shrink-0">
                            Stok: <?php echo e($item->stok); ?>

                        </span>
                    </div>

                    <form class="add-to-cart-form">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="produkId" value="<?php echo e($item->id); ?>">
                        <input type="hidden" name="jumlah" value="1">

                        <button type="button"
                            onclick="addToCart(this)"
                            <?php echo e($item->stok <= 0 ? 'disabled' : ''); ?>

                            class="w-full <?php echo e($item->stok <= 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-[#58CC02] hover:bg-[#46A302]'); ?> text-white py-2 rounded-xl transition font-bold text-xs flex items-center justify-center gap-2 shadow-sm">
                            <i class="fa-solid fa-cart-plus"></i> <?php echo e($item->stok <= 0 ? 'Habis' : 'Tambah Pesanan'); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full py-20 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
            <i class="fa-solid fa-box-open text-5xl text-gray-200 mb-4"></i>
            <p class="text-gray-400 font-bold uppercase text-sm tracking-widest">Produk tidak ditemukan.</p>
        </div>
        <?php endif; ?>
    </div>

    <div class="mt-10 px-4 md:px-0">
        <?php echo e($produks->links()); ?>

    </div>
</div>

<script>
function addToCart(btn) {
    let form = btn.closest('.add-to-cart-form');
    let formData = new FormData(form);

    fetch("<?php echo e(route('agen.produk.add-to-cart')); ?>", {
        method: "POST",
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const container = document.querySelector('.fixed.bottom-5.right-5');
        const notif = document.createElement('div');
        notif.className = "alert-info flex items-center w-full max-w-xs p-4 rounded-2xl shadow-xl border border-green-200 bg-green-50";
        notif.innerHTML = `
            <div class="inline-flex items-center justify-center shrink-0 w-10 h-10 rounded-full bg-green-600 text-white">
                <i class="fa-solid fa-check text-sm"></i>
            </div>
            <div class="ms-3">
                <div class="text-sm font-bold text-green-800">Berhasil</div>
                <div class="text-xs text-green-700 mt-0.5">${data.message}</div>
            </div>
        `;
        container.appendChild(notif);

        notif.style.opacity = '0';
        notif.style.transform = 'translateX(20px)';
        notif.style.transition = "all 0.5s ease";

        setTimeout(() => {
            notif.style.opacity = '1';
            notif.style.transform = 'translateX(0)';
        }, 100);

        setTimeout(() => {
            notif.style.opacity = '0';
            notif.style.transform = 'translateX(20px)';
            setTimeout(() => notif.remove(), 500);
        }, 4000);
    })
    .catch(error => console.error('Error:', error));
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\agen\produk\index.blade.php ENDPATH**/ ?>