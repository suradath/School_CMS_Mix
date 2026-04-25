<div class="max-w-4xl mx-auto">
    <div class="mb-10 flex items-center">
        <a href="/calendar" class="mr-4 p-2 bg-white rounded-xl shadow-sm border border-gray-100 text-slate-400 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h3 class="text-3xl font-extrabold text-slate-900 heading-font">แก้ไขกิจกรรม</h3>
    </div>

    <form action="/calendar/update/<?= $event['id'] ?>" method="POST" class="space-y-8">
        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ชื่อกิจกรรม <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($event['title']) ?>"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">รายละเอียด</label>
                    <textarea name="description" rows="4"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900"><?= htmlspecialchars($event['description']) ?></textarea>
                </div>

                <!-- Dates -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">วันที่เริ่มต้น <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" required value="<?= $event['start_date'] ?>"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">วันที่สิ้นสุด (ถ้ามี)</label>
                    <input type="date" name="end_date" value="<?= $event['end_date'] ?>"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>

                <!-- Times -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">เวลาเริ่มต้น (ถ้ามี)</label>
                    <input type="time" name="start_time" value="<?= $event['start_time'] ?>"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">เวลาสิ้นสุด (ถ้ามี)</label>
                    <input type="time" name="end_time" value="<?= $event['end_time'] ?>"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>

                <!-- Responsible & Color -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ผู้รับผิดชอบ</label>
                    <input type="text" name="responsible_person" value="<?= htmlspecialchars($event['responsible_person']) ?>"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">สีที่แสดงในปฏิทิน</label>
                    <div class="flex items-center gap-4">
                        <input type="color" name="color" value="<?= $event['color'] ?>"
                            class="w-16 h-14 bg-slate-50 border-none rounded-2xl cursor-pointer">
                        <span class="text-sm text-slate-400 font-medium italic">เลือกสีเพื่อแยกประเภทกิจกรรม</span>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit" class="px-10 py-4 bg-primary text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-primary/20 transform hover:-translate-y-1">
                    บันทึกการแก้ไข
                </button>
            </div>
        </div>
    </form>
</div>
