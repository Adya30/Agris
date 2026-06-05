<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - AGRIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>

<body class="min-h-screen bg-gray-100 flex items-center justify-center">

<div class="bg-white w-full max-w-md p-8 rounded-lg shadow relative">

    <h2 class="text-2xl font-bold text-center mb-6">
        Reset Password
    </h2>

    <?php if(session('error')): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('password.update')); ?>">
        <?php echo csrf_field(); ?>

        <input type="hidden" name="token" value="<?php echo e($token); ?>">
        <input type="hidden" name="email" value="<?php echo e($email); ?>">

        <div class="mb-5 relative">
            <input type="password" name="password" id="password" placeholder="Password Baru"
                class="w-full border-b focus:border-[#58CC02] focus:outline-none py-2 pr-10 <?php echo e($errors->has('password') ? 'border-red-500' : 'border-gray-400'); ?>">

            <span id="togglePassword" class="absolute right-2 top-2 cursor-pointer text-gray-500 hover:text-[#58CC02]">
                <i id="eyeIcon" class="fa-solid fa-eye"></i>
            </span>

            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="mb-6 relative">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password"
                class="w-full border-b focus:border-[#58CC02] focus:outline-none py-2 pr-10 <?php echo e($errors->has('password') ? 'border-red-500' : 'border-gray-400'); ?>">

            <span id="toggleConfirmPassword" class="absolute right-2 top-2 cursor-pointer text-gray-500 hover:text-[#58CC02]">
                <i id="eyeConfirmIcon" class="fa-solid fa-eye"></i>
            </span>
        </div>

        <button type="submit" class="w-full bg-[#58CC02] hover:bg-green-600 text-white py-3 rounded-full text-lg font-semibold transition">
            Reset Password
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordFields = [
            { toggle: 'togglePassword', input: 'password', icon: 'eyeIcon' },
            { toggle: 'toggleConfirmPassword', input: 'password_confirmation', icon: 'eyeConfirmIcon' }
        ];

        passwordFields.forEach(field => {
            const toggleBtn = document.getElementById(field.toggle);
            const inputField = document.getElementById(field.input);
            const icon = document.getElementById(field.icon);

            if (toggleBtn && inputField && icon) {
                toggleBtn.addEventListener('click', function() {
                    const isPassword = inputField.type === "password";
                    inputField.type = isPassword ? "text" : "password";

                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
        });
    });
</script>

</body>
</html>
<?php /**PATH D:\project\Agris\resources\views\auth\reset-password.blade.php ENDPATH**/ ?>