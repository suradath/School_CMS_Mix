<div class="mb-6">
    <a href="javascript:history.back()" class="text-sm text-gray-500 hover:text-primary transition-colors font-bold">
        <i class="fa fa-arrow-left mr-1"></i> ย้อนกลับ
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Basic Info & Address -->
    <div class="lg:col-span-1 space-y-8">
        
        <!-- Profile Card -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-primary to-blue-400 opacity-20"></div>
            
            <div class="relative w-32 h-32 mx-auto mt-4 mb-4 rounded-full bg-white border-4 border-white shadow-lg overflow-hidden flex items-center justify-center">
                <?php if ($student['gender'] === 'ช'): ?>
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= urlencode($student['first_name']) ?>&style=circle&backgroundColor=b6e3f4" alt="Avatar" class="w-full h-full object-cover">
                <?php else: ?>
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= urlencode($student['first_name']) ?>&style=circle&backgroundColor=ffdfbf" alt="Avatar" class="w-full h-full object-cover">
                <?php endif; ?>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900 heading-font mb-1">
                <?= htmlspecialchars($student['title'] . $student['first_name'] . ' ' . $student['last_name']) ?>
            </h3>
            <p class="text-sm font-bold text-gray-500 mb-4">
                รหัสนักเรียน: <span class="text-primary"><?= htmlspecialchars($student['student_code']) ?></span>
            </p>
            
            <div class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-full text-sm font-bold">
                ชั้น <?= htmlspecialchars($student['class_level']) ?> ห้อง <?= htmlspecialchars((string)$student['room_number']) ?>
            </div>
        </div>

        <!-- Address Card -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <h4 class="text-lg font-bold text-gray-900 heading-font mb-4 border-b pb-2"><i class="fa fa-map-marker text-primary mr-2"></i> ข้อมูลที่อยู่</h4>
            
            <?php if (!empty($student['address'])): $addr = $student['address']; ?>
                <ul class="space-y-3 text-sm">
                    <li class="flex"><span class="w-24 text-gray-500 font-bold">บ้านเลขที่:</span> <span class="text-gray-900"><?= htmlspecialchars($addr['address_no']) ?> หมู่ <?= htmlspecialchars($addr['moo']) ?></span></li>
                    <li class="flex"><span class="w-24 text-gray-500 font-bold">ถนน/ซอย:</span> <span class="text-gray-900"><?= htmlspecialchars($addr['soi_road'] ?: '-') ?></span></li>
                    <li class="flex"><span class="w-24 text-gray-500 font-bold">ตำบล:</span> <span class="text-gray-900"><?= htmlspecialchars($addr['sub_district']) ?></span></li>
                    <li class="flex"><span class="w-24 text-gray-500 font-bold">อำเภอ:</span> <span class="text-gray-900"><?= htmlspecialchars($addr['district']) ?></span></li>
                    <li class="flex"><span class="w-24 text-gray-500 font-bold">จังหวัด:</span> <span class="text-gray-900"><?= htmlspecialchars($addr['province']) ?></span></li>
                </ul>
            <?php else: ?>
                <p class="text-gray-400 text-sm text-center py-4">ไม่มีข้อมูลที่อยู่</p>
            <?php endif; ?>
        </div>

    </div>

    <!-- Right Column: Details & Parents -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Detailed Info -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
            <h4 class="text-lg font-bold text-gray-900 heading-font mb-6"><i class="fa fa-id-card-o text-primary mr-2"></i> ข้อมูลส่วนตัว</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">เลขประจำตัวประชาชน</label>
                    <p class="mt-1 text-gray-900 font-medium"><?= htmlspecialchars($student['citizen_id']) ?></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">วันเกิด</label>
                    <p class="mt-1 text-gray-900 font-medium">
                        <?= $student['birth_date'] ? date('d/m/Y', strtotime($student['birth_date'])) : '-' ?>
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">กรุ๊ปเลือด</label>
                    <p class="mt-1 text-gray-900 font-medium"><?= htmlspecialchars($student['blood_type'] ?: '-') ?></p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">ศาสนา / เชื้อชาติ / สัญชาติ</label>
                    <p class="mt-1 text-gray-900 font-medium">
                        <?= htmlspecialchars($student['religion'] ?: '-') ?> / 
                        <?= htmlspecialchars($student['ethnicity'] ?: '-') ?> / 
                        <?= htmlspecialchars($student['nationality'] ?: '-') ?>
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">น้ำหนัก / ส่วนสูง</label>
                    <p class="mt-1 text-gray-900 font-medium">
                        <?= htmlspecialchars((string)($student['weight'] ?: '-')) ?> กก. / 
                        <?= htmlspecialchars((string)($student['height'] ?: '-')) ?> ซม.
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase">ความด้อยโอกาส</label>
                    <p class="mt-1 text-gray-900 font-medium"><?= htmlspecialchars($student['disadvantage_status'] ?: 'ปกติ') ?></p>
                </div>
            </div>
        </div>

        <!-- Parents Info -->
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm">
            <h4 class="text-lg font-bold text-gray-900 heading-font mb-6"><i class="fa fa-users text-primary mr-2"></i> ข้อมูลบิดา-มารดา และผู้ปกครอง</h4>
            
            <?php if (!empty($student['parents'])): $par = $student['parents']; ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="bg-blue-50/50 p-4 rounded-2xl border border-blue-100">
                        <h5 class="text-sm font-bold text-blue-800 mb-3 border-b border-blue-200 pb-2">ข้อมูลบิดา</h5>
                        <p class="text-sm mb-2"><span class="text-gray-500">ชื่อ-สกุล:</span> <span class="font-medium text-gray-900"><?= htmlspecialchars($par['father_name'] ?: '-') ?></span></p>
                        <p class="text-sm"><span class="text-gray-500">อาชีพ:</span> <span class="font-medium text-gray-900"><?= htmlspecialchars($par['father_occupation'] ?: '-') ?></span></p>
                    </div>

                    <div class="bg-pink-50/50 p-4 rounded-2xl border border-pink-100">
                        <h5 class="text-sm font-bold text-pink-800 mb-3 border-b border-pink-200 pb-2">ข้อมูลมารดา</h5>
                        <p class="text-sm mb-2"><span class="text-gray-500">ชื่อ-สกุล:</span> <span class="font-medium text-gray-900"><?= htmlspecialchars($par['mother_name'] ?: '-') ?></span></p>
                        <p class="text-sm"><span class="text-gray-500">อาชีพ:</span> <span class="font-medium text-gray-900"><?= htmlspecialchars($par['mother_occupation'] ?: '-') ?></span></p>
                    </div>

                    <div class="md:col-span-2 bg-gray-50 p-4 rounded-2xl border border-gray-200">
                        <h5 class="text-sm font-bold text-gray-800 mb-3 border-b border-gray-200 pb-2">ข้อมูลผู้ปกครอง</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <p class="text-sm"><span class="text-gray-500">ชื่อ-สกุล:</span> <span class="font-medium text-gray-900"><?= htmlspecialchars($par['guardian_name'] ?: '-') ?></span></p>
                            <p class="text-sm"><span class="text-gray-500">ความเกี่ยวข้อง:</span> <span class="font-medium text-gray-900"><?= htmlspecialchars($par['guardian_relation'] ?: '-') ?></span></p>
                            <p class="text-sm"><span class="text-gray-500">อาชีพ:</span> <span class="font-medium text-gray-900"><?= htmlspecialchars($par['guardian_occupation'] ?: '-') ?></span></p>
                        </div>
                    </div>

                </div>
            <?php else: ?>
                <p class="text-gray-400 text-sm text-center py-4">ไม่มีข้อมูลบิดา-มารดาและผู้ปกครอง</p>
            <?php endif; ?>
        </div>

    </div>
</div>
