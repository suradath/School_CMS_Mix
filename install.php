<?php
declare(strict_types=1);

/**
 * School CMS Mix V2.7 Installer - Modern Edition
 */

session_start();
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$error = '';
$success = '';

if (file_exists(__DIR__ . '/config.php')) {
    die("System is already installed. Please remove install.php or delete config.php to reinstall.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($step === 2) {
        $host = $_POST['db_host'];
        $name = $_POST['db_name'];
        $user = $_POST['db_user'];
        $pass = $_POST['db_pass'];

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8mb4", $user, $pass);
            $_SESSION['db'] = ['host' => $host, 'db_name' => $name, 'username' => $user, 'password' => $pass];
            header("Location: install.php?step=3");
            exit;
        } catch (PDOException $e) {
            $error = "ฐานข้อมูลเชื่อมต่อไม่สำเร็จ: " . $e->getMessage();
        }
    } elseif ($step === 3) {
        $admin_user = $_POST['admin_user'];
        $admin_pass = password_hash($_POST['admin_pass'], PASSWORD_DEFAULT);
        $site_name = $_POST['site_name'];
        
        $db = $_SESSION['db'];
        try {
            $pdo = new PDO("mysql:host={$db['host']};dbname={$db['db_name']};charset=utf8mb4", $db['username'], $db['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = file_get_contents(__DIR__ . '/database.sql');
            $pdo->exec($sql);

            $stmt = $pdo->prepare("INSERT INTO users (username, password, email, full_name, role) VALUES (?, ?, ?, ?, 'admin')");
            $stmt->execute([$admin_user, $admin_pass, 'admin@example.com', 'System Administrator']);

            $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('site_name', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            $stmt->execute([$site_name]);

            $config_content = "<?php\nreturn " . var_export(['db' => $db], true) . ";\n";
            file_put_contents(__DIR__ . '/config.php', $config_content);

            $success = "ติดตั้งระบบเสร็จสมบูรณ์เรียบร้อยแล้ว!";
            $step = 4;
        } catch (Exception $e) {
            $error = "เกิดข้อผิดพลาดในการติดตั้ง: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School CMS Mix V2.7 Installer - Modern Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&family=Outfit:wght@400;600;700&family=K2D:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background: #f8fafc; }
        h1, h2, h3 { font-family: 'K2D', sans-serif; }
        .outfit { font-family: 'Outfit', sans-serif; }
        .wizard-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-6">
    <div class="max-w-2xl w-full">
        <!-- Brand Header -->
        <div class="flex flex-col items-center mb-10">
            <div class="w-16 h-16 bg-blue-600 rounded-3xl flex items-center justify-center shadow-2xl shadow-blue-500/20 mb-4">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
            </div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">School CMS Mix V2.7</h1>
            <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest mt-2">Installation Wizard</p>
        </div>

        <div class="wizard-card rounded-5xl shadow-2xl overflow-hidden">
            <!-- Progress Bar -->
            <div class="bg-slate-50/50 p-6 border-b border-slate-100">
                <div class="flex items-center space-x-4 max-w-md mx-auto">
                    <?php for($i=1; $i<=3; $i++): ?>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 <?= $step >= $i ? 'bg-blue-600 text-white shadow-lg' : 'bg-slate-100 text-slate-400' ?>">
                                <span class="text-sm font-bold outfit"><?= $i ?></span>
                            </div>
                            <span class="mt-2 text-[10px] font-bold uppercase tracking-widest <?= $step >= $i ? 'text-blue-600' : 'text-slate-400' ?>">
                                <?= $i == 1 ? 'Check' : ($i == 2 ? 'Database' : 'Finalize') ?>
                            </span>
                        </div>
                        <?php if($i < 3): ?>
                            <div class="h-[2px] w-12 mt-5 <?= $step > $i ? 'bg-blue-600' : 'bg-slate-200' ?>"></div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="p-10 md:p-12">
                <?php if($error): ?>
                    <div class="flex p-4 mb-8 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-100 shadow-sm" role="alert">
                        <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                        <div><span class="font-bold uppercase tracking-tight">System Error:</span> <?= $error ?></div>
                    </div>
                <?php endif; ?>

                <?php if($step === 1): ?>
                    <div class="text-center mb-10">
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">ตรวจสอบระบบ</h2>
                        <p class="text-slate-400">ระบบจะทำการตรวจสอบความพร้อมของเซิร์ฟเวอร์ก่อนดำเนินการ</p>
                    </div>
                    <div class="space-y-3 mb-10">
                        <?php 
                            $checks = [
                                'PHP Version (>= 8.1)' => PHP_VERSION_ID >= 80100,
                                'PDO MySQL Extension' => extension_loaded('pdo_mysql'),
                                'Writable Directory' => is_writable(__DIR__)
                            ];
                            $all_pass = true;
                            foreach($checks as $label => $pass):
                                if(!$pass) $all_pass = false;
                        ?>
                        <div class="flex items-center justify-between p-5 bg-slate-50/50 rounded-3xl border border-slate-100 transition-all hover:bg-white hover:shadow-sm">
                            <span class="text-slate-700 font-bold text-sm tracking-tight"><?= $label ?></span>
                            <?php if($pass): ?>
                                <span class="bg-green-100 text-green-700 p-1.5 rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-700 p-1.5 rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></span>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= $all_pass ? 'install.php?step=2' : '#' ?>" class="block text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-5 rounded-2xl shadow-xl shadow-blue-500/20 transition-all <?= !$all_pass ? 'opacity-30 cursor-not-allowed grayscale' : 'hover:-translate-y-1' ?>">
                        เริ่มกระบวนการติดตั้ง
                    </a>

                <?php elseif($step === 2): ?>
                    <div class="text-center mb-10">
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">ตั้งค่าฐานข้อมูล</h2>
                        <p class="text-slate-400">ระบุข้อมูลการเชื่อมต่อ MySQL / MariaDB</p>
                    </div>
                    <form action="install.php?step=2" method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Host</label>
                                <input type="text" name="db_host" value="localhost" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 transition-all" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Database Name</label>
                                <input type="text" name="db_name" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 transition-all" placeholder="school_db" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">User</label>
                                <input type="text" name="db_user" value="root" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 transition-all" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Password</label>
                                <input type="password" name="db_pass" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 transition-all">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-5 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1">
                            ตรวจสอบและบันทึกข้อมูล
                        </button>
                    </form>

                <?php elseif($step === 3): ?>
                    <div class="text-center mb-10">
                        <h2 class="text-3xl font-bold text-slate-900 mb-2">ตั้งค่าเว็ปไซต์และแอดมิน</h2>
                        <p class="text-slate-400">ขั้นตอนสุดท้ายในการเริ่มต้นระบบ School CMS Mix V2.7</p>
                    </div>
                    <form action="install.php?step=3" method="POST" class="space-y-6">
                        <div>
                            <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">ชื่อโรงเรียนหรือชื่อเว็ปไซต์</label>
                            <input type="text" name="site_name" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 transition-all" placeholder="ยินดีต้อนรับสู่โรงเรียนไออาร์ที" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Username (Admin)</label>
                                <input type="text" name="admin_user" value="admin" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 transition-all" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Password (Admin)</label>
                                <input type="password" name="admin_pass" class="w-full bg-slate-50 border border-slate-200 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 p-4 transition-all" required>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-5 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1">
                            เสร็จสิ้นการติดตั้ง
                        </button>
                    </form>

                <?php elseif($step === 4): ?>
                    <div class="text-center py-10">
                        <div class="w-24 h-24 bg-green-100 rounded-5xl flex items-center justify-center mx-auto mb-8 shadow-inner">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h2 class="text-4xl font-bold text-slate-900 mb-4">ติดตั้งสำเร็จ!</h2>
                        <p class="text-slate-400 mb-10 max-w-sm mx-auto">ระบบ School CMS Mix V2.7 พร้อมใช้งานแล้ว กรุณาลบไฟล์ install.php ออกจากเซิร์ฟเวอร์เพื่อความปลอดภัย</p>
                        <a href="index.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-5 px-12 rounded-2xl shadow-xl shadow-blue-500/20 transition-all hover:-translate-y-1">
                            เข้าสู่หน้าเว็ปไซต์
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <p class="text-center mt-10 text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em] opacity-50">
            &copy; <?= date('Y') ?> School CMS Mix V2.7 Application
        </p>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
