<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    <!-- Add Course Form -->
    <div class="lg:col-span-5">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 sticky top-10">
            <h3 class="text-xl font-bold text-slate-800 heading-font mb-6">เพิ่มรายวิชาใหม่</h3>
            <form action="/attendance/setup/store" method="POST" class="space-y-4">
                                                    <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
                
                <div>
                    <label class="block mb-2 text-sm font-bold text-slate-700 outfit uppercase tracking-wider">รหัสวิชา</label>
                    <input type="text" name="course_code" required placeholder="เช่น ว21101"
                        class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-3.5 transition-all">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-bold text-slate-700 outfit uppercase tracking-wider">ชื่อวิชา</label>
                    <input type="text" name="course_name" required placeholder="เช่น วิทยาศาสตร์"
                        class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-3.5 transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-slate-700 outfit uppercase tracking-wider">ชั้นเรียน</label>
                        <input type="text" name="class_level" placeholder="เช่น ม.1"
                            class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-3.5 transition-all">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-slate-700 outfit uppercase tracking-wider">ห้องเรียน</label>
                        <input type="number" name="room_number" placeholder="เช่น 1"
                            class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-3.5 transition-all">
                    </div>
                </div>

                <p class="text-[10px] text-slate-400 font-bold uppercase italic">* คุณสามารถผูกห้องเรียนเพิ่มได้ในภายหลัง</p>

                <button type="submit" class="w-full mt-4 px-6 py-4 bg-primary text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                    บันทึกรายวิชา
                </button>
            </form>

            <hr class="my-8 border-gray-100">

            <h3 class="text-xl font-bold text-slate-800 heading-font mb-6">ผูกห้องเรียนเพิ่ม</h3>
            <form action="/attendance/setup/link" method="POST" class="space-y-4">
                                                    <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
                
                <div>
                    <label class="block mb-2 text-sm font-bold text-slate-700 outfit uppercase tracking-wider">เลือกวิชา</label>
                    <select name="course_id" required class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-3.5 transition-all">
                        <option value="">-- เลือกวิชา --</option>
                        <?php foreach ($allCourses as $c): ?>
                            <option value="<?= $c['id'] ?>">[<?= $c['course_code'] ?>] <?= $c['course_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-slate-700 outfit uppercase tracking-wider">ชั้นเรียน</label>
                        <input type="text" name="class_level" required placeholder="เช่น ม.1"
                            class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-3.5 transition-all">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-slate-700 outfit uppercase tracking-wider">ห้องเรียน</label>
                        <input type="number" name="room_number" required placeholder="เช่น 1"
                            class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-3.5 transition-all">
                    </div>
                </div>

                <button type="submit" class="w-full mt-4 px-6 py-4 bg-slate-800 text-white rounded-2xl font-bold text-sm shadow-lg hover:scale-[1.02] active:scale-95 transition-all">
                    ผูกห้องเรียน
                </button>
            </form>
        </div>
    </div>

    <!-- Linked Courses Table -->
    <div class="lg:col-span-7">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-100 bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-800 heading-font">รายการที่ผูกข้อมูลไว้</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-4">รหัสวิชา</th>
                            <th class="px-6 py-4">ชื่อวิชา</th>
                            <th class="px-6 py-4">ห้องเรียน</th>
                            <th class="px-6 py-4 text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if (empty($linkedCourses)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center text-gray-400">
                                    <i class="fa fa-folder-open text-4xl mb-3"></i>
                                    <p>ยังไม่มีข้อมูลการผูกรายวิชา</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($linkedCourses as $c): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-700 outfit"><?= $c['course_code'] ?></td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-800"><?= $c['course_name'] ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">
                                    <?= $c['class_level'] ?>/<?= $c['room_number'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="/attendance/setup/unlink" method="POST" class="inline" onsubmit="return confirm('ยกเลิกการผูกห้องเรียนนี้?')">
                                    <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
                                    <input type="hidden" name="id" value="<?= $c['link_id'] ?>">
                                    <button type="submit" class="p-2 text-amber-500 hover:bg-amber-50 rounded-xl transition-all" title="ยกเลิกการผูกห้อง">
                                        <i class="fa fa-unlink"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end">
            <a href="/attendance" class="px-8 py-3.5 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all">
                <i class="fa fa-arrow-left mr-2"></i>กลับไปหน้าเช็คชื่อ
            </a>
        </div>
    </div>
</div>

<!-- Master Courses Management -->
<div class="mt-12 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-100 bg-slate-50/50">
        <h3 class="text-xl font-bold text-slate-800 heading-font">จัดการรายวิชาหลัก (Master Courses)</h3>
        <p class="text-xs text-rose-500 font-bold mt-1 uppercase tracking-widest">* การลบวิชาหลักจะลบประวัติการเช็คชื่อและการผูกห้องทั้งหมดของวิชานี้</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                <tr>
                    <th class="px-6 py-4">รหัสวิชา</th>
                    <th class="px-6 py-4">ชื่อวิชา</th>
                    <th class="px-6 py-4 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($allCourses as $c): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-bold text-slate-700 outfit"><?= $c['course_code'] ?></td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-800"><?= $c['course_name'] ?></td>
                    <td class="px-6 py-4 text-center">
                        <form action="/attendance/setup/delete" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบรายวิชานี้? (ข้อมูลประวัติการเช็คชื่อทั้งหมดจะหายไป)')">
                            <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                            <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                <i class="fa fa-trash"></i> ลบวิชา
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
