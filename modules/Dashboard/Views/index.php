<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Pages Stat -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
        <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">หน้าทั้งหมด</p>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['pages'] ?></p>
        </div>
    </div>

    <!-- Personnel Stat -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
        <div class="p-3 rounded-full bg-green-50 text-green-600 mr-4">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3.005 3.005 0 013.75-2.906z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">บุคลากร</p>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['personnel'] ?></p>
        </div>
    </div>

    <!-- News Stat -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
        <div class="p-3 rounded-full bg-orange-50 text-orange-600 mr-4">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"></path><path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">ข่าวสาร</p>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['news'] ?></p>
        </div>
    </div>

    <!-- Visitor Stat -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center">
        <div class="p-3 rounded-full bg-purple-50 text-purple-600 mr-4">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">ผู้เข้าชม (Session)</p>
            <p class="text-2xl font-bold text-gray-800"><?= $stats['visitors'] ?></p>
        </div>
    </div>
</div>

<!-- Welcome Section -->
<div class="mt-8 p-8 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl text-white shadow-lg">
    <h3 class="text-2xl font-bold heading-font mb-2">ยินดีต้อนรับกลับมา, <?= $_SESSION['user_name'] ?>!</h3>
    <p class="opacity-90 max-w-2xl">คุณสามารถจัดการเนื้อหาเว็บไซต์โรงเรียน ทั้งระบบบุคลากร ข่าวประชาสัมพันธ์ และหน้าเว็บต่างๆ ได้จากเมนูทางด้านซ้ายมือ</p>
    
    <?php if ($stats['pending_leaves'] > 0 && \Core\Security::checkRole(['admin', 'editor', 'hr', 'director'])): ?>
    <div class="mt-6 p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-amber-400 rounded-xl flex items-center justify-center text-amber-900 mr-4 shadow-lg shadow-amber-400/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <div>
                <p class="text-sm font-bold">มีคำขอลาใหม่ที่รอการพิจารณา</p>
                <p class="text-xs opacity-75">จำนวน <?= $stats['pending_leaves'] ?> รายการที่รอการตรวจสอบจากคุณ</p>
            </div>
        </div>
        <a href="<?= url('/leave/review') ?>" class="px-4 py-2 bg-white text-blue-700 text-xs font-bold rounded-lg hover:bg-blue-50 transition shadow-sm">ตรวจสอบตอนนี้</a>
    </div>
    <?php endif; ?>

    <?php if ($stats['unread_saraban'] > 0): ?>
    <div class="mt-4 p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-rose-500 rounded-xl flex items-center justify-center text-white mr-4 shadow-lg shadow-rose-500/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-bold">มีหนังสือเข้าใหม่ที่คุณยังไม่ได้อ่าน</p>
                <p class="text-xs opacity-75">จำนวน <?= $stats['unread_saraban'] ?> ฉบับที่ต้องการการตรวจสอบ</p>
            </div>
        </div>
        <a href="<?= url('/saraban') ?>" class="px-4 py-2 bg-white text-blue-700 text-xs font-bold rounded-lg hover:bg-blue-50 transition shadow-sm">เปิดกล่องขาเข้า</a>
    </div>
    <?php endif; ?>

    <div class="mt-6 flex space-x-4">
        <a href="<?= url('/pages/create') ?>" class="px-5 py-2.5 bg-white text-blue-700 font-semibold rounded-xl text-sm hover:bg-blue-50 transition shadow-sm">สร้างหน้าใหม่</a>
        <a href="<?= url('/news/create') ?>" class="px-5 py-2.5 bg-blue-500 text-white font-semibold rounded-xl text-sm hover:bg-blue-400 transition shadow-sm">ลงข่าวใหม่</a>
    </div>
</div>
