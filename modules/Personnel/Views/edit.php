<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8">
        <form action="/personnel/update/<?= $person['id'] ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= \Core\Security::csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Side: Basic Info -->
                <div class="space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">ชื่อ-นามสกุล</label>
                        <input type="text" name="name" value="<?= $person['name'] ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">ตำแหน่ง</label>
                        <input type="text" name="position" value="<?= $person['position'] ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">กลุ่มสาระฯ / ฝ่ายงาน</label>
                        <select name="department_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition">
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= $dept['id'] == $person['department_id'] ? 'selected' : '' ?>><?= $dept['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">อีเมล</label>
                            <input type="email" name="email" value="<?= $person['email'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">เบอร์โทรศัพท์</label>
                            <input type="text" name="phone" value="<?= $person['phone'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition">
                        </div>
                    </div>
                </div>

                <!-- Right Side: Image & Bio -->
                <div class="space-y-4 text-center">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-left text-gray-900">รูปภาพบุคลากร (อัพโหลดใหม่เพื่อเปลี่ยน)</label>
                        <div class="flex flex-col items-center">
                            <img class="w-32 h-32 rounded-full object-cover border-4 border-blue-50 mb-4 bg-gray-50" src="<?= $person['image_url'] ?: 'https://ui-avatars.com/api/?name='.urlencode($person['name']).'&background=random' ?>" alt="">
                            <input type="file" name="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-xl cursor-pointer bg-gray-50 focus:outline-none" accept="image/*">
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-left text-gray-900">ลำดับการแสดงผล</label>
                        <input type="number" name="sort_order" value="<?= $person['sort_order'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-left text-gray-900">ประวัติย่อ</label>
                        <textarea name="bio" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition"><?= $person['bio'] ?></textarea>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end space-x-3">
                <a href="/personnel" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition shadow-sm">ยกเลิก</a>
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm">บันทึกการแก้ไข</button>
            </div>
        </form>
    </div>
</div>
