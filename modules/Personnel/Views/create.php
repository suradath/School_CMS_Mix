<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8">
        <form action="<?= url('/personnel/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= \Core\Security::csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Side: Basic Info -->
                <div class="space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">ชื่อ-นามสกุล</label>
                        <input type="text" name="name" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition" placeholder="เช่น นายวิชาการ ใจดี">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">ตำแหน่ง</label>
                        <input type="text" name="position" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition" placeholder="เช่น ผู้อำนวยการโรงเรียน">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">กลุ่มสาระฯ / ฝ่ายงาน</label>
                        <select name="department_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition">
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"><?= $dept['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">อีเมล</label>
                            <input type="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition">
                        </div>
                    </div>
                </div>

                <!-- Right Side: Image & Bio -->
                <div class="space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">รูปภาพบุคลากร</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-2xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">คลิกเพื่ออัพโหลด</span> หรือลากวาง</p>
                                    <p class="text-xs text-gray-400">PNG, JPG, GIF (Max. 2MB)</p>
                                </div>
                                <input type="file" name="image" class="hidden" accept="image/*" />
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">ลำดับการแสดงผล</label>
                        <input type="number" name="sort_order" value="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">ประวัติย่อ</label>
                        <textarea name="bio" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition"></textarea>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end space-x-3">
                <a href="<?= url('/personnel') ?>" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition shadow-sm">ยกเลิก</a>
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>
