<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50">
            <h3 class="text-xl font-bold text-slate-800 heading-font">เขียนใบลาออนไลน์</h3>
            <p class="text-sm text-slate-500 mt-1">กรอกรายละเอียดการลาของคุณ ระบบจะคำนวณวันลาอัตโนมัติ (เฉพาะวันทำการ)</p>
        </div>
        
        <form action="/leave/store" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            <?= \Core\Security::csrf_field() ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-rose-50 text-rose-600 p-4 rounded-2xl border border-rose-100 text-sm font-bold flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Leave Type -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ประเภทการลา</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <?php foreach ($leaveTypes as $type): ?>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="leave_type_id" value="<?= $type['id'] ?>" required class="peer sr-only">
                            <div class="p-4 border border-gray-100 rounded-2xl bg-slate-50 text-center transition-all peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:ring-2 peer-checked:ring-primary/20 group-hover:bg-white group-hover:shadow-sm">
                                <span class="w-2 h-2 rounded-full inline-block mb-1" style="background-color: <?= $type['color'] ?>"></span>
                                <div class="text-sm font-bold text-slate-700 peer-checked:text-primary"><?= $type['name'] ?></div>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">วันที่เริ่มลา</label>
                    <input type="date" name="start_date" id="start_date" required class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ถึงวันที่</label>
                    <input type="date" name="end_date" id="end_date" required class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm">
                </div>

                <!-- Reason -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">เหตุผลการลา</label>
                    <textarea name="reason" rows="3" required placeholder="ระบุเหตุผลในการลาหรือความจำเป็น..." class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm"></textarea>
                </div>

                <!-- Attachment -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">เอกสารแนบ (เช่น ใบรับรองแพทย์)</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-100 border-dashed rounded-3xl cursor-pointer bg-slate-50 hover:bg-white hover:border-primary/30 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-6 h-6 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">อัปโหลดไฟล์ PDF หรือรูปภาพ</p>
                            </div>
                            <input type="file" name="attachment" class="hidden" accept="image/*,application/pdf" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex items-center justify-end space-x-4">
                <a href="/leave" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">ยกเลิก</a>
                <button type="submit" class="px-10 py-3 bg-primary text-white text-sm font-bold rounded-2xl hover:shadow-lg hover:shadow-primary/30 transition-all">
                    ส่งใบลา
                </button>
            </div>
        </form>
    </div>
</div>
