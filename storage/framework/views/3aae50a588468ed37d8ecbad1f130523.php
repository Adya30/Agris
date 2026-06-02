<?php $__env->startSection('title', 'Login - AGRIS'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex min-h-screen w-full bg-gray-100">

    <div class="hidden md:flex w-1/2 bg-[#0f8629] flex-col justify-between p-12">

        <div>
            <img src="<?php echo e(asset('images/icon.png')); ?>" class="w-20">
        </div>

        <div class="flex justify-center items-center flex-1">
            <img src="<?php echo e(asset('images/plant.png')); ?>" class="w-50">
        </div>

        <div class="text-white text-center text-xl font-medium leading-relaxed mb-6 mt-2.5">
            Masuk dan Temukan <br> Keseimbangan Alam Dalam Setiap Tanam
        </div>
    </div>

    <div class="w-full md:w-1/2 flex items-center justify-center relative">

        <a href="<?php echo e(route('landing')); ?>" class="absolute top-6 left-6 text-gray-600 hover:text-[#0f8629] transition">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </a>


        <div class="w-full max-w-md px-10">

            <h2 class="text-3xl font-bold text-gray-700 mb-8">
                Hi, Selamat Datang!
            </h2>

            <form method="POST" action="<?php echo e(route('login')); ?>" id="loginForm">
                <?php echo csrf_field(); ?>

                <div class="mb-6">
                    <input type="text" name="email" value="<?php echo e(old('email')); ?>" placeholder="email" class="w-full bg-transparent border-b border-gray-400 focus:border-[#0f8629] focus:outline-none py-2">
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

                <div class="mb-6 relative">
                    <input type="password" name="password" id="password" placeholder="Password" class="w-full bg-transparent border-b border-gray-400 focus:border-[#0f8629] focus:outline-none py-2 pr-10">
                    <span onclick="togglePassword()"
                          class="absolute right-2 top-2 cursor-pointer text-gray-500 hover:text-[#0f8629]">
                        <i id="eyeIcon" class="fa-solid fa-eye"></i>
                    </span>

                    <?php $__errorArgs = ['password'];
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


                <div class="flex justify-between items-center text-sm mb-8">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="accent-[#0f8629]" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                        Ingat aku!
                    </label>

                    <a href="<?php echo e(route('password.request')); ?>"
                       class="hover:underline text-gray-600 hover:text-[#0f8629]">
                        Lupa Password?
                    </a>
                </div>

                <button type="submit" class="w-full bg-[#0f8629] hover:bg-green-600 text-white py-3 rounded-full text-lg cursor-pointer transition">
                    Login
                </button>
            </form>

            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-400"></div>
                <span class="text-sm text-gray-600">atau</span>
                <div class="flex-1 h-px bg-gray-400"></div>
            </div>

            <a href="<?php echo e(route('google.login')); ?>"
               class="w-full flex items-center justify-center border border-gray-500 rounded-full py-3 hover:bg-gray-200 transition">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg"
                     class="w-5 h-5 mr-2">
                Login with Google
            </a>

            <div class="text-center text-sm mt-6">
                Belum punya akun?
                <a href="<?php echo e(route('register')); ?>" class="text-[#0f8629] font-semibold hover:underline">
                    Register
                </a>
            </div>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script>
    function togglePassword() {
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (password.type === "password") {
            password.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            password.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\project\Agris\resources\views/auth/login.blade.php ENDPATH**/ ?>