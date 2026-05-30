<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto pt-6 pb-12 px-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Keranjang Belanja</h2>
            <p class="text-gray-500 mt-1">Kelola produk yang akan Anda checkout</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-5 py-4 rounded-2xl">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-5">
            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $harga = $item->produk->harga ?? 0;
                $subtotal = $harga * $item->jumlah;
            ?>

            <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-5">
                <div class="flex flex-col md:flex-row md:items-center gap-5">
                    <div class="w-28 h-28 bg-gray-100 rounded-2xl overflow-hidden shrink-0">
                        <?php if($item->produk->fotoProduk): ?>
                            <img src="<?php echo e(asset('storage/' . $item->produk->fotoProduk)); ?>"
                                 class="w-full h-full object-cover"
                                 alt="<?php echo e($item->produk->namaProduk); ?>">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-3xl">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex-1">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">
                                    <?php echo e($item->produk->namaProduk ?? 'Produk Tidak Ditemukan'); ?>

                                </h3>

                                <p class="text-[#58CC02] text-lg font-semibold mt-2">
                                    Rp <?php echo e(number_format($harga, 0, ',', '.')); ?>

                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <form action="<?php echo e(route('agen.keranjang.kurang', $item->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                        class="w-10 h-10 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-700 font-bold text-lg transition">
                                        -
                                    </button>
                                </form>

                                <div class="px-5 py-2 rounded-xl border border-gray-200 bg-white font-bold text-gray-800">
                                    <?php echo e($item->jumlah); ?>

                                </div>

                                <form action="<?php echo e(route('agen.produk.add-to-cart')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="produkId" value="<?php echo e($item->produk->id); ?>">
                                    <input type="hidden" name="jumlah" value="1">
                                    <button type="submit"
                                        class="w-10 h-10 rounded-xl bg-[#58CC02] hover:bg-[#46A302] flex items-center justify-center text-white font-bold text-lg transition">
                                        +
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Subtotal</p>
                                <h4 class="text-2xl font-bold text-gray-800">
                                    Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?>

                                </h4>
                            </div>

                            <form action="<?php echo e(route('agen.keranjang.destroy', $item->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                    onclick="return confirm('Hapus produk ini dari keranjang?')"
                                    class="px-5 py-2.5 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 transition font-semibold">
                                    <i class="fa-solid fa-trash mr-2"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white border border-gray-100 rounded-3xl shadow-sm py-16 px-6 text-center">
                <div class="w-24 h-24 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-5">
                    <i class="fa-solid fa-cart-shopping text-4xl text-gray-300"></i>
                </div>

                <h3 class="text-2xl font-bold text-gray-700 mb-2">
                    Keranjang Kosong
                </h3>

                <p class="text-gray-500">
                    Belum ada produk yang ditambahkan ke keranjang.
                </p>
            </div>
            <?php endif; ?>
        </div>

        <?php if($items->count() > 0): ?>
        <?php
            $total = 0;
            foreach($items as $item){
                $total += ($item->produk->harga ?? 0) * $item->jumlah;
            }
        ?>

        <div>
            <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-6 sticky top-24">
                <h3 class="text-2xl font-bold text-gray-800 mb-6">
                    Ringkasan Belanja
                </h3>

                <div class="space-y-4">
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $subtotal = ($item->produk->harga ?? 0) * $item->jumlah;
                    ?>

                    <div class="flex justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-700">
                                <?php echo e($item->produk->namaProduk); ?>

                            </p>
                            <span class="text-sm text-gray-400">
                                <?php echo e($item->jumlah); ?> x Rp <?php echo e(number_format($item->produk->harga ?? 0, 0, ',', '.')); ?>

                            </span>
                        </div>

                        <p class="font-semibold text-gray-800 whitespace-nowrap">
                            Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?>

                        </p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="border-t border-dashed border-gray-200 my-6"></div>

                <div class="flex items-center justify-between mb-6">
                    <span class="text-lg font-semibold text-gray-700">
                        Total
                    </span>

                    <span class="text-3xl font-bold text-[#58CC02]">
                        Rp <?php echo e(number_format($total, 0, ',', '.')); ?>

                    </span>
                </div>

                <a href="#"
                    class="w-full flex items-center justify-center gap-3 bg-[#58CC02] hover:bg-[#46A302] text-white py-4 rounded-2xl font-bold text-lg transition shadow-sm">
                    <i class="fa-solid fa-credit-card"></i>
                    Checkout Sekarang
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.agen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/agen/keranjang/index.blade.php ENDPATH**/ ?>