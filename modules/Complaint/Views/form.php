<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-slate-900 heading-font tracking-tight mb-4"><?= $title ?></h1>
        <p class="text-lg text-slate-600">เรายินดีรับฟังทุกข้อเสนอแนะและเรื่องร้องเรียน เพื่อนำไปพัฒนาโรงเรียนให้ดียิ่งขึ้น</p>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-8 p-6 bg-emerald-50 border border-emerald-100 rounded-3xl flex items-center animate-fade-in">
            <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white mr-4 shadow-lg shadow-emerald-200">
                <i class="fa fa-check text-xl"></i>
            </div>
            <div>
                <h3 class="text-emerald-900 font-bold">ส่งข้อมูลสำเร็จ!</h3>
                <p class="text-emerald-700 text-sm"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-8 p-6 bg-rose-50 border border-rose-100 rounded-3xl flex items-center animate-fade-in">
            <div class="w-12 h-12 bg-rose-500 rounded-2xl flex items-center justify-center text-white mr-4 shadow-lg shadow-rose-200">
                <i class="fa fa-exclamation-triangle text-xl"></i>
            </div>
            <div>
                <h3 class="text-rose-900 font-bold">เกิดข้อผิดพลาด</h3>
                <p class="text-rose-700 text-sm"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <form action="<?= url('/complaint/store') ?>" method="POST" enctype="multipart/form-data" class="p-8 md:p-12 space-y-8">
            <?= \Core\Security::csrf_field() ?>

            <div class="grid grid-cols-1 gap-8">
                <!-- Topic -->
                <div class="space-y-2">
                    <label for="topic" class="block text-sm font-bold text-slate-700 ml-1">หัวข้อเรื่องร้องเรียน <span class="text-rose-500">*</span></label>
                    <input type="text" name="topic" id="topic" required 
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all text-slate-900 placeholder-slate-400 font-medium"
                        placeholder="สรุปหัวข้อที่ต้องการแจ้ง">
                </div>

                <!-- Details -->
                <div class="space-y-2">
                    <label for="details" class="block text-sm font-bold text-slate-700 ml-1">รายละเอียด <span class="text-rose-500">*</span></label>
                    <textarea name="details" id="details" rows="5" required
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all text-slate-900 placeholder-slate-400 font-medium"
                        placeholder="ระบุรายละเอียด ข้อเท็จจริง หรือข้อเสนอแนะ"></textarea>
                </div>

                <!-- Attachment -->
                <div class="space-y-2">
                    <label for="attachment" class="block text-sm font-bold text-slate-700 ml-1">แนบรูปภาพประกอบ (ถ้ามี)</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="attachment" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <i class="fa fa-cloud-upload text-2xl text-slate-400 mb-2"></i>
                                <p class="text-xs text-slate-500 font-bold">คลิกเพื่ออัปโหลด (JPG, PNG ไม่เกิน 5MB)</p>
                            </div>
                            <input id="attachment" name="attachment" type="file" accept="image/*" class="hidden" />
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">ข้อมูลผู้ติดต่อ (เลือกกรอกหรือไม่ก็ได้ - Optional)</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Contact Name -->
                        <div class="space-y-2">
                            <label for="contact_name" class="block text-sm font-bold text-slate-700 ml-1">ชื่อ-นามสกุล</label>
                            <input type="text" name="contact_name" id="contact_name" 
                                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all text-slate-900 placeholder-slate-400 font-medium"
                                placeholder="ไม่ต้องระบุหากต้องการปกปิด">
                        </div>

                        <!-- Contact Info -->
                        <div class="space-y-2">
                            <label for="contact_info" class="block text-sm font-bold text-slate-700 ml-1">เบอร์โทรศัพท์ / อีเมล</label>
                            <input type="text" name="contact_info" id="contact_info" 
                                class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all text-slate-900 placeholder-slate-400 font-medium"
                                placeholder="เพื่อการติดต่อกลับ">
                        </div>
                    </div>
                </div>

                <!-- CAPTCHA -->
                <div class="bg-primary/5 p-6 rounded-3xl flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-primary text-white rounded-xl flex items-center justify-center mr-4">
                            <i class="fa fa-shield"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">การยืนยันตัวตน</p>
                            <p class="text-xs text-slate-500">กรุณาตอบคำถามเพื่อยืนยันว่าคุณไม่ใช่บอท</p>
                        </div>
                    </div>
                    <div class="flex items-center bg-white p-2 rounded-2xl shadow-sm border border-primary/10">
                        <span class="px-4 font-bold text-primary"><?= $captcha_question ?></span>
                        <input type="number" name="captcha" required
                            class="w-24 px-4 py-2 bg-slate-50 border-none rounded-xl focus:ring-2 focus:ring-primary/20 text-center font-bold"
                            placeholder="?">
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" 
                    class="w-full py-5 bg-primary hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-primary/30 transition-all transform hover:-translate-y-1 flex items-center justify-center text-lg">
                    <span>ส่งข้อมูลร้องเรียน</span>
                    <i class="fa fa-paper-plane ml-3"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="mt-12 text-center text-slate-400 text-sm font-medium">
        <p>ข้อมูลส่วนตัวของคุณจะถูกเก็บเป็นความลับสูงสุดตามนโยบายความเป็นส่วนตัวของโรงเรียน</p>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>
