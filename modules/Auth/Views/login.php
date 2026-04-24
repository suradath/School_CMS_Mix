<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - School CMS Mix V1.2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&family=K2D:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; }
        h1, h2 { font-family: 'K2D', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-blue-700 p-6 text-white text-center">
            <h1 class="text-2xl font-bold">School CMS Mix V1.2</h1>
            <p class="text-blue-100 text-sm">เข้าสู่ระบบจัดการข้อมูล</p>
        </div>
        <div class="p-8">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm border border-red-100">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="/auth/login" method="POST" class="space-y-4">
                <?= \Core\Security::csrf_field() ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">จำฉันไว้</span>
                    </label>
                    <a href="#" class="text-sm text-blue-600 hover:underline">ลืมรหัสผ่าน?</a>
                </div>
                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition shadow-md">
                    เข้าสู่ระบบ
                </button>
            </form>
        </div>
        <div class="p-4 bg-gray-50 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-500">&copy; 2024 School CMS Mix V1.2. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
