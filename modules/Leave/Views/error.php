<div class="max-w-2xl mx-auto py-12">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden text-center p-12">
        <div class="w-24 h-24 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-8">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-800 heading-font mb-4"><?= $message ?></h2>
        <p class="text-slate-500 mb-10 leading-relaxed">
            เพื่อให้ระบบทราบว่าใครเป็นผู้ลาและคำนวณโควตาวันลาได้ถูกต้อง บัญชีผู้ใช้งานจะต้องถูกเชื่อมโยงเข้ากับรายชื่อบุคลากรในฐานข้อมูลก่อน
        </p>
        
        <?php if (\Core\Security::checkRole('admin')): ?>
        <div class="space-y-4">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">คำแนะนำสำหรับผู้ดูแลระบบ</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/admin/users" class="bg-primary text-white px-8 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 hover:shadow-primary/40 transition-all">
                    ไปที่หน้าจัดการผู้ใช้งาน
                </a>
                <a href="/personnel" class="bg-slate-100 text-slate-700 px-8 py-3 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all">
                    ตรวจสอบรายชื่อบุคลากร
                </a>
            </div>
        </div>
        <?php else: ?>
        <p class="text-sm font-bold text-slate-400">กรุณาติดต่อผู้ดูแลระบบเพื่อทำการเชื่อมโยงข้อมูลของคุณ</p>
        <?php endif; ?>
    </div>
</div>
