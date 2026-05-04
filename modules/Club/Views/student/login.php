<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Anuphan:wght@100..700&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { font-family: 'Anuphan', 'Sarabun', sans-serif; }
        .heading-font { font-family: 'Anuphan', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <div class="w-24 h-24 bg-blue-600 rounded-[2rem] shadow-2xl shadow-blue-200 flex items-center justify-center mx-auto mb-6 transform rotate-12">
                <i class="fa fa-graduation-cap text-4xl text-white -rotate-12"></i>
            </div>
            <h1 class="text-4xl font-black text-slate-800 heading-font mb-2">ลงทะเบียนชุมนุม</h1>
            <p class="text-slate-500 font-medium italic">Online Club Registration System</p>
        </div>

        <div class="bg-white rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-10 relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full opacity-50"></div>

            <form action="<?= url('/club-auth') ?>" method="POST" class="space-y-6 relative z-10">
                <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl text-sm font-bold flex items-center gap-3">
                        <i class="fa fa-exclamation-circle"></i>
                        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">รหัสนักเรียน (Username)</label>
                    <div class="relative">
                        <i class="fa fa-user-o absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="student_code" required class="w-full pl-12 pr-5 py-4 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-600 font-bold" placeholder="ระบุรหัสนักเรียน">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-1">รหัสผ่าน (5 ตัวท้ายเลขบัตรประชาชน)</label>
                    <div class="relative">
                        <i class="fa fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="password" name="password" required class="w-full pl-12 pr-5 py-4 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-slate-600 font-bold" placeholder="•••••">
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                    เข้าสู่ระบบ <i class="fa fa-arrow-right"></i>
                </button>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-50 text-center">
                <a href="<?= url('/') ?>" class="text-sm font-bold text-slate-400 hover:text-blue-600 transition-colors">
                    <i class="fa fa-home mr-1"></i> กลับหน้าหลักเว็บไซต์
                </a>
            </div>
        </div>
        
        <p class="text-center mt-10 text-slate-400 text-sm font-medium">
            &copy; <?= date('Y') ?> School CMS Mix V2.9. All rights reserved.
        </p>
    </div>
</body>
</html>
