<div class="max-w-4xl">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= url('/settings/menu/store') ?>" method="POST" class="p-8 space-y-6">
            <?= \Core\Security::csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block mb-2 text-sm font-bold text-slate-700">ชื่อเมนู (Menu Title)</label>
                    <input type="text" name="title" required class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-xl focus:ring-primary focus:border-primary block w-full p-4 transition-all" placeholder="เช่น หน้าแรก, ติดต่อเรา">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-slate-700">เมนูหลัก (Parent Menu)</label>
                    <select name="parent_id" class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-xl focus:ring-primary focus:border-primary block w-full p-4 transition-all">
                        <option value="">-- เป็นเมนูหลัก (ไม่มีเมนูแม่) --</option>
                        <?php foreach ($parents as $parent): ?>
                            <option value="<?= $parent['id'] ?>"><?= htmlspecialchars($parent['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-slate-700">URL / Path</label>
                    <input type="text" name="url" required class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-xl focus:ring-primary focus:border-primary block w-full p-4 transition-all" placeholder="เช่น /, /about-us, https://google.com">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-slate-700">ลำดับการแสดงผล (Sort Order)</label>
                    <input type="number" name="sort_order" value="0" class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-xl focus:ring-primary focus:border-primary block w-full p-4 transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-bold text-slate-700">Icon Class (Font Awesome 4)</label>
                    <input type="text" name="icon" class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-xl focus:ring-primary focus:border-primary block w-full p-4 transition-all" placeholder="เช่น fa-home, fa-users, fa-newspaper-o">
                    <p class="mt-2 text-xs text-slate-400">สามารถดูชื่อ Icon ได้จาก <a href="https://fontawesome.com/v4/icons/" target="_blank" class="text-primary hover:underline">Font Awesome 4.7 Icons</a></p>
                </div>

                <div class="md:col-span-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                        <span class="ml-3 text-sm font-bold text-slate-700">เปิดใช้งานเมนูนี้</span>
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-50 flex items-center space-x-4">
                <button type="submit" class="bg-primary hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-2xl transition shadow-lg shadow-blue-500/20">
                    บันทึกข้อมูล
                </button>
                <a href="<?= url('/settings/menu') ?>" class="text-slate-400 hover:text-slate-600 font-bold px-4">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>
