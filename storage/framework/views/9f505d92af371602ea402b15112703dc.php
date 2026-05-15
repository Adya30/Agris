<?php $__env->startSection('title', 'Lupa Password - AGRIS'); ?>

<?php $__env->startSection('content'); ?>

<div class="min-h-screen bg-gray-100 flex items-center justify-center">

    <div class="bg-white w-full max-w-md p-8 rounded-lg shadow relative">


        <h2 class="text-2xl font-bold text-center mb-6">
            Lupa Password
        </h2>

        <p class="text-sm text-gray-600 text-center mb-6">
            Masukkan email yang terdaftar, kami akan kirimkan link untuk mereset password Anda.
        </p>

        <?php if(session('success')): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.email')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-5">
                <input type="email"
                    name="email"
                    value="<?php echo e(old('email')); ?>"
                    placeholder="Masukkan Email"
                    class="w-full border-b border-gray-400 focus:border-[#58CC02] focus:outline-none py-2">

                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1">
                        <?php echo e($message); ?>

                    </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="w-full bg-[#58CC02] hover:bg-green-600 text-white py-3 rounded-full cursor-pointer text-lg transition">
                Kirim Link Reset
            </button>
        </form>

        <div class="text-center mt-6 text-sm">
            Kembali ke
            <a href="<?php echo e(route('login')); ?>" class="text-[#58CC02] font-semibold hover:underline"> Login
            </a>
        </div>

    </div>
</div>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>