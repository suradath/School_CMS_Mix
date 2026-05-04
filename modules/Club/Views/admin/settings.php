<div class="mb-8">
    <a href="<?= url('/club') ?>" class="inline-flex items-center text-slate-400 hover:text-primary transition-colors font-bold mb-4">
        <i class="fa fa-arrow-left mr-2"></i> กลับหน้าจัดการ
    </a>
    <h2 class="text-3xl font-black text-slate-800 heading-font">ตั้งค่าระบบชุมนุม</h2>
    <p class="text-slate-500 font-medium">เปิด-ปิด การรับสมัครชุมนุมทั้งโรงเรียน</p>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden p-8 max-w-2xl">
    <form action="<?= url('/club/settings/update') ?>" method="POST" class="space-y-8">
        <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
        
        <div class="flex items-center justify-between p-6 bg-slate-50 rounded-[2rem] border border-slate-100">
            <div>
                <h4 class="text-lg font-bold text-slate-800 heading-font mb-1">สถานะระบบรับสมัคร</h4>
                <p class="text-sm text-slate-500 font-medium">หากปิด นักเรียนจะไม่สามารถลงทะเบียนได้</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="enabled" value="1" <?= ($enabled === '1') ? 'checked' : '' ?> class="sr-only peer">
                <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-primary"></div>
            </label>
        </div>

        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex gap-4">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 flex-shrink-0">
                <i class="fa fa-info-circle"></i>
            </div>
            <div>
                <p class="text-sm text-amber-700 font-medium leading-relaxed">
                    เมื่อตั้งค่าเป็น <strong class="font-bold">เปิด</strong> ปุ่ม "ลงทะเบียนชุมนุมออนไลน์" จะแสดงที่หน้าแรกของเว็บไซต์โดยอัตโนมัติ
                </p>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-10 py-4 bg-primary text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-primary/30 transition-all transform hover:-translate-y-1">
                <i class="fa fa-save mr-2"></i> บันทึกการตั้งค่า
            </button>
        </div>
    </form>
</div>
