<div class="max-w-4xl mx-auto">
    <div class="mb-10 flex items-center">
        <a href="<?= url('/calendar') ?>" class="mr-4 p-2 bg-white rounded-xl shadow-sm border border-gray-100 text-slate-400 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h3 class="text-3xl font-extrabold text-slate-900 heading-font">เพิ่มกิจกรรมใหม่</h3>
    </div>

    <form action="<?= url('/calendar/store') ?>" method="POST" class="space-y-8">
            <?= \Core\Security::csrf_field() ?>
        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ชื่อกิจกรรม <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="เช่น สอบกลางภาค, วันหยุดนักขัตฤกษ์"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">รายละเอียด</label>
                    <textarea name="description" rows="4" placeholder="ระบุรายละเอียดเพิ่มเติมของกิจกรรม..."
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900"></textarea>
                </div>

                <!-- Dates -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">วันที่เริ่มต้น <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" required
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">วันที่สิ้นสุด (ถ้ามี)</label>
                    <input type="date" name="end_date"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>

                <!-- Times -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">เวลาเริ่มต้น (ถ้ามี)</label>
                    <input type="time" name="start_time"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">เวลาสิ้นสุด (ถ้ามี)</label>
                    <input type="time" name="end_time"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>

                <!-- Responsible & Color -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ผู้รับผิดชอบ</label>
                    <input type="text" name="responsible_person" placeholder="ชื่อบุคคลหรือฝ่ายที่รับผิดชอบ"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">สีที่แสดงในปฏิทิน</label>
                    <div class="flex items-center gap-4">
                        <input type="color" name="color" value="#1d4ed8"
                            class="w-16 h-14 bg-slate-50 border-none rounded-2xl cursor-pointer">
                        <span class="text-sm text-slate-400 font-medium italic">เลือกสีเพื่อแยกประเภทกิจกรรม</span>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit" class="px-10 py-4 bg-primary text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-primary/20 transform hover:-translate-y-1">
                    บันทึกข้อมูลกิจกรรม
                </button>
            </div>
        </div>
    </form>
</div>
