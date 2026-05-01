<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center bg-gradient-to-r from-white to-slate-50/50">
            <a href="<?= url('/discipline') ?>" class="p-2 bg-slate-100 text-slate-500 rounded-xl mr-4 hover:bg-slate-200 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h3 class="text-xl font-bold text-slate-800 heading-font">บันทึกพฤติกรรม</h3>
                <p class="text-sm text-slate-500 mt-1">เลือกหมวดหมู่เพื่อบันทึกคะแนนความดีหรือความผิด</p>
            </div>
        </div>

        <div class="p-8">
            <?php if (!$student): ?>
                <!-- Student Search Area if not selected -->
                <div class="p-10 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-700">ยังไม่ได้เลือกนักเรียน</h4>
                    <p class="text-sm text-slate-400 mt-2 max-w-xs mx-auto">กรุณากลับไปที่หน้าภาพรวมเพื่อเลือกนักเรียนที่ต้องการบันทึกพฤติกรรม</p>
                    <a href="<?= url('/discipline') ?>" class="mt-6 inline-flex items-center px-6 py-3 bg-primary text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">กลับหน้าภาพรวม</a>
                </div>
            <?php else: ?>
                <form action="<?= url('/discipline/storeRecord') ?>" method="POST" class="space-y-8">
                    <?= \Core\Security::csrf_field() ?>
                    <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                    
                    <!-- Student Info Card -->
                    <div class="p-6 bg-slate-900 rounded-2xl text-white flex items-center relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-2xl font-bold mr-6 backdrop-blur-md">
                            <?= substr($student['first_name'], 0, 1) ?>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold heading-font"><?= $student['title'] . $student['first_name'] . ' ' . $student['last_name'] ?></h4>
                            <p class="text-xs font-bold text-blue-300 uppercase tracking-widest mt-1">รหัสนักเรียน: <?= $student['student_code'] ?> | ชั้น: <?= $student['class_level'] . '/' . $student['room_number'] ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Category Selection -->
                        <div class="space-y-4">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">เลือกประเภทพฤติกรรม</label>
                            <div class="grid grid-cols-1 gap-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                                <?php foreach ($categories as $cat): ?>
                                    <label class="relative group cursor-pointer">
                                        <input type="radio" name="category_id" value="<?= $cat['id'] ?>" required class="peer hidden">
                                        <div class="p-4 bg-white border border-slate-100 rounded-2xl peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:ring-2 peer-checked:ring-primary/10 transition-all hover:bg-slate-50">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-xl flex items-center justify-center mr-3 <?= $cat['type'] === 'good' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' ?>">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                            <?php if ($cat['type'] === 'good'): ?>
                                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                            <?php else: ?>
                                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                                            <?php endif; ?>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm font-bold text-slate-700"><?= $cat['name'] ?></span>
                                                </div>
                                                <span class="text-xs font-black outfit <?= $cat['points'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                                                    <?= $cat['points'] > 0 ? '+' . $cat['points'] : $cat['points'] ?>
                                                </span>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Date, Remarks & Submit -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">วันที่เกิดเหตุ</label>
                                <input type="date" name="record_date" value="<?= date('Y-m-d') ?>" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">หมายเหตุเพิ่มเติม</label>
                                <textarea name="remarks" rows="6" class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-5 py-4 text-slate-700 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none resize-none" placeholder="รายละเอียดเหตุการณ์ หรือข้อมูลเพิ่มเติม..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-5 bg-primary text-white rounded-2xl font-bold text-sm shadow-xl shadow-primary/20 hover:-translate-y-0.5 transition-all flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                                ยืนยันการบันทึก
                            </button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
