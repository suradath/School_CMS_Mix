<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Anuphan:wght@100..700&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: 'Anuphan', 'Sarabun', sans-serif;
        }

        .heading-font {
            font-family: 'Anuphan', sans-serif;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body class="bg-[#f8fafc] min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                    <i class="fa fa-graduation-cap"></i>
                </div>
                <h1 class="text-xl font-black text-slate-800 heading-font hidden md:block">ระบบลงทะเบียนชุมนุม</h1>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">ยินดีต้อนรับ</p>
                    <p class="text-sm font-black text-slate-700"><?= $student_name ?> (<?= $student_grade ?>)</p>
                </div>
                <a href="<?= url('/club-logout') ?>"
                    class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                    <i class="fa fa-sign-out"></i>
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <!-- Header Section -->
        <div class="mb-12">
            <h2 class="text-4xl font-black text-slate-800 heading-font mb-4">สวัสดี, <?= $student_name ?> 👋</h2>
            <?php if ($myClub): ?>
                <p class="text-lg text-slate-500 font-medium italic">คุณได้ลงทะเบียนเข้าร่วมชุมนุมเรียบร้อยแล้ว</p>
            <?php elseif (!$system_enabled): ?>
                <div class="p-6 bg-amber-50 border border-amber-100 rounded-[2rem] flex items-center gap-6 mt-6">
                    <div
                        class="w-16 h-16 bg-amber-100 rounded-[1.5rem] flex items-center justify-center text-amber-600 flex-shrink-0 text-2xl">
                        <i class="fa fa-lock"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-amber-800 heading-font">ระบบยังไม่เปิดรับสมัคร</h4>
                        <p class="text-amber-600 font-medium">กรุณารอประกาศจากทางโรงเรียนอีกครั้ง</p>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-lg text-slate-500 font-medium">กรุณาเลือกชุมนุมที่สนใจเข้าร่วม (เปิดรับสำหรับชั้น
                    <?= $student_grade ?>)</p>
            <?php endif; ?>
        </div>

        <!-- My Club Card -->
        <?php if ($myClub): ?>
            <div
                class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[3rem] p-10 text-white shadow-2xl shadow-blue-200 mb-12 relative overflow-hidden">
                <i class="fa fa-check-circle absolute -bottom-10 -right-10 text-[15rem] opacity-10"></i>
                <div class="relative z-10">
                    <div class="flex justify-between items-start">
                        <span
                            class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-[10px] font-black uppercase tracking-widest mb-6 inline-block">ชุมนุมของฉัน</span>
                        <?php if ($system_enabled): ?>
                            <button onclick="confirmWithdraw()"
                                class="px-4 py-2 bg-rose-500/20 hover:bg-rose-500 backdrop-blur-md border border-rose-400/30 rounded-xl text-[11px] font-bold transition-all flex items-center gap-2">
                                <i class="fa fa-times"></i> ยกเลิกการสมัคร
                            </button>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-5xl font-black heading-font mb-4"><?= htmlspecialchars($myClub['club_name']) ?></h3>
                    <div class="flex flex-wrap gap-10 mt-8">
                        <div>
                            <p class="text-white/60 text-xs font-bold uppercase tracking-widest mb-2">สถานที่</p>
                            <p class="text-xl font-bold italic"><?= htmlspecialchars($myClub['location'] ?? 'ไม่ระบุ') ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-white/60 text-xs font-bold uppercase tracking-widest mb-2">วันที่ลงทะเบียน</p>
                            <p class="text-xl font-bold italic">
                                <?= date('d/m/Y H:i', strtotime($myClub['registration_date'])) ?> น.</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Available Clubs -->
        <?php if (!$myClub && $system_enabled): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if (empty($availableClubs)): ?>
                    <div class="col-span-full py-20 text-center">
                        <div
                            class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 text-4xl">
                            <i class="fa fa-search"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-slate-400 heading-font">ไม่พบชุมนุมที่เปิดรับสำหรับคุณในขณะนี้</h4>
                    </div>
                <?php endif; ?>

                <?php foreach ($availableClubs as $club): ?>
                    <div
                        class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all group border-b-8 border-b-transparent hover:border-b-blue-600">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">ที่ว่าง</span>
                                <span
                                    class="text-2xl font-black text-slate-800"><?= $club['capacity'] - $club['current_count'] ?></span>
                            </div>
                        </div>

                        <h4 class="text-2xl font-black text-slate-800 heading-font mb-2"><?= htmlspecialchars($club['name']) ?>
                        </h4>
                        <p class="text-slate-500 font-medium text-sm mb-6 flex items-center gap-2">
                            <i class="fa fa-map-marker text-blue-500"></i> <?= htmlspecialchars($club['location'] ?? '-') ?>
                        </p>

                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 overflow-hidden border-2 border-white shadow-sm">
                                <i class="fa fa-user"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ครูประจำชุมนุม</p>
                                <p class="text-sm font-bold text-slate-700"><?= htmlspecialchars($club['advisor_name']) ?></p>
                            </div>
                        </div>

                        <div class="mb-8">
                            <div
                                class="flex justify-between text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">
                                <span>ความสำเร็จการรับสมัคร</span>
                                <span><?= round(($club['current_count'] / $club['capacity']) * 100) ?>%</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600 transition-all duration-1000"
                                    style="width: <?= ($club['current_count'] / $club['capacity']) * 100 ?>%"></div>
                            </div>
                        </div>

                        <button onclick="confirmRegister(<?= $club['id'] ?>, '<?= addslashes($club['name']) ?>')"
                            class="w-full py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-200 transition-all active:scale-95">
                            ลงทะเบียนเข้าร่วม
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function confirmRegister(clubId, clubName) {
            Swal.fire({
                title: 'ยืนยันการลงทะเบียน?',
                html: `คุณแน่ใจหรือไม่ว่าต้องการเข้าร่วมชุมนุม <br><strong class="text-blue-600 text-xl font-black heading-font mt-2 inline-block">${clubName}</strong>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'ยืนยัน, ลงทะเบียน',
                cancelButtonText: 'ยกเลิก',
                borderRadius: '2rem',
                customClass: {
                    confirmButton: 'rounded-xl font-bold px-8 py-4',
                    cancelButton: 'rounded-xl font-bold px-8 py-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= url('/club-register') ?>',
                        method: 'POST',
                        data: {
                            club_id: clubId,
                            csrf_token: '<?= \Core\Security::csrf_token() ?>'
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'สำเร็จ!',
                                    text: response.message,
                                    icon: 'success',
                                    borderRadius: '2rem'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'ผิดพลาด',
                                    text: response.message,
                                    icon: 'error',
                                    borderRadius: '2rem'
                                });
                            }
                        }
                    });
                }
            });
        }

        function confirmWithdraw() {
            Swal.fire({
                title: 'ยืนยันการยกเลิกการสมัคร?',
                text: 'หากคุณยกเลิก คุณจะสามารถเลือกสมัครชุมนุมใหม่ได้จนกว่าระบบจะปิดรับสมัคร',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'ยืนยัน, ยกเลิกเลย',
                cancelButtonText: 'กลับ',
                borderRadius: '2rem'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= url('/club-withdraw') ?>',
                        method: 'POST',
                        data: {
                            csrf_token: '<?= \Core\Security::csrf_token() ?>'
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'ยกเลิกสำเร็จ',
                                    text: response.message,
                                    icon: 'success',
                                    borderRadius: '2rem'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'ผิดพลาด',
                                    text: response.message,
                                    icon: 'error',
                                    borderRadius: '2rem'
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>