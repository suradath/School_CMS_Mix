<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8">
        <form action="/settings/update-footer" method="POST" class="space-y-10">
            <?= \Core\Security::csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Footer Description -->
                <div class="md:col-span-2">
                    <label class="block mb-4 text-sm font-bold text-gray-900">คำบรรยายโรงเรียน (Footer Description)</label>
                    <textarea name="footer_description" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-4 shadow-sm" placeholder="คำบรรยายแนะนำโรงเรียนสั้นๆ ในส่วนท้ายเว็บไซต์"><?= htmlspecialchars($settings['footer_description']) ?></textarea>
                    <p class="mt-2 text-xs text-gray-400">แนะนำ: ความยาวประมาณ 2-3 บรรทัด เพื่อความสวยงาม</p>
                </div>

                <!-- Address -->
                <div>
                    <label class="block mb-4 text-sm font-bold text-gray-900">ที่อยู่โรงเรียน (Address)</label>
                    <textarea name="school_address" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-4 shadow-sm" placeholder="บ้านเลขที่, ถนน, ตำบล, อำเภอ, จังหวัด, รหัสไปรษณีย์"><?= htmlspecialchars($settings['school_address']) ?></textarea>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block mb-4 text-sm font-bold text-gray-900">เบอร์โทรศัพท์ติดต่อ (Phone)</label>
                    <input type="text" name="school_phone" value="<?= htmlspecialchars($settings['school_phone']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-4 shadow-sm" placeholder="เช่น 043-xxx-xxxx">
                </div>

                <!-- Copyright -->
                <div class="md:col-span-2">
                    <label class="block mb-4 text-sm font-bold text-gray-900">ข้อความลิขสิทธิ์ (Copyright Text)</label>
                    <input type="text" name="footer_copyright" value="<?= htmlspecialchars($settings['footer_copyright']) ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-4 shadow-sm" placeholder="© 2024 School Name. All rights reserved.">
                </div>
            </div>

            <div class="pt-8 flex justify-end">
                <button type="submit" class="px-10 py-4 bg-primary hover:bg-blue-700 text-white font-bold rounded-2xl shadow-xl transition transform hover:scale-105 active:scale-95 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    บันทึกข้อมูลส่วนท้าย
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Preview Section -->
<div class="mt-12 bg-slate-900 rounded-3xl p-10 text-white overflow-hidden relative">
    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
    <div class="relative z-10">
        <h4 class="text-xs font-bold text-primary uppercase tracking-widest mb-6">Live Preview (ส่วนที่กำลังแก้ไข)</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 opacity-70">
            <div>
                <p class="text-xl font-bold mb-4 heading-font"><?= \Core\Database::getSetting('site_name', 'School Name') ?></p>
                <p class="text-sm leading-relaxed mb-6 italic"><?= nl2br(htmlspecialchars($settings['footer_description'])) ?></p>
            </div>
            <div class="space-y-4">
                <div class="flex items-start text-sm">
                    <svg class="w-4 h-4 mr-3 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                    <span><?= htmlspecialchars($settings['school_address']) ?></span>
                </div>
                <div class="flex items-center text-sm">
                    <svg class="w-4 h-4 mr-3 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    <span><?= htmlspecialchars($settings['school_phone']) ?></span>
                </div>
            </div>
        </div>
        <div class="mt-10 pt-6 border-t border-white/10 text-[10px] text-center opacity-50">
            <?= htmlspecialchars($settings['footer_copyright']) ?>
        </div>
    </div>
</div>
