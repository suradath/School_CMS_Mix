<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Health Dashboard</h1>
            <p class="mt-2 text-gray-600">สรุปข้อมูลงานพยาบาลและการเข้ารับบริการประจำเดือน</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= url('/admin/health/create-treatment') ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all transform hover:scale-[1.02] active:scale-95">
                <i class="fa fa-plus-circle mr-2"></i> บันทึกการรักษาใหม่
            </a>
            <a href="<?= url('/admin/health/bmi') ?>" class="inline-flex items-center px-6 py-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-2xl shadow-sm transition-all">
                <i class="fa fa-weight mr-2 text-emerald-500"></i> จัดการ BMI
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 mr-6">
                <i class="fa fa-user-md text-3xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">ผู้เข้ารับบริการ (เดือนนี้)</p>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($stats['total_visits']) ?> ราย</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 mr-6">
                <i class="fa fa-ambulance text-3xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">ส่งต่อโรงพยาบาล</p>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($stats['referrals']) ?> ราย</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 mr-6">
                <i class="fa fa-pills text-3xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">ยาที่จ่ายไปแล้ว</p>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($stats['medicines_dispensed']) ?> ชิ้น</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Low Stock Alerts -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-red-50/30">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fa fa-exclamation-triangle text-red-500 mr-2"></i> ยาใกล้หมดสต๊อก
                    </h2>
                    <span class="px-2 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-lg"><?= count($lowStock) ?> รายการ</span>
                </div>
                <div class="p-6">
                    <?php if (empty($lowStock)): ?>
                        <div class="text-center py-8">
                            <i class="fa fa-check-circle text-4xl text-emerald-100 mb-2"></i>
                            <p class="text-gray-400 text-sm">สต๊อกยาปกติทุกรายการ</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($lowStock as $item): ?>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($item['name']) ?></p>
                                        <p class="text-xs text-gray-500">เหลือ: <span class="text-red-600 font-bold"><?= $item['stock_quantity'] ?></span> (ขั้นต่ำ <?= $item['min_stock_level'] ?>)</p>
                                    </div>
                                    <a href="<?= url('/admin/health/inventory') ?>" class="text-blue-600 hover:text-blue-800 transition-colors">
                                        <i class="fa fa-plus-square text-xl"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    <a href="<?= url('/admin/health/inventory') ?>" class="block text-center text-sm font-bold text-blue-600 hover:text-blue-800">จัดการสต๊อกยาพยาบาลทั้งหมด</a>
                </div>
            </div>
        </div>

        <!-- Recent Records Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">ประวัติการเข้ารับบริการล่าสุด</h2>
                    <i class="fa fa-history text-gray-300"></i>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">วันที่/เวลา</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">นักเรียน</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">อาการ/การรักษา</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($recent)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">ยังไม่มีข้อมูลการเข้ารับบริการ</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recent as $record): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <p class="text-sm font-medium text-gray-900"><?= date('d/m/Y', strtotime($record['created_at'])) ?></p>
                                            <p class="text-xs text-gray-500"><?= date('H:i', strtotime($record['created_at'])) ?> น.</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($record['first_name'] . ' ' . $record['last_name']) ?></p>
                                            <p class="text-xs text-gray-500"><?= htmlspecialchars($record['class_level'] . '/' . $record['room_number']) ?></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-900 line-clamp-1"><?= htmlspecialchars($record['symptoms']) ?></p>
                                            <p class="text-xs text-gray-500 line-clamp-1"><?= htmlspecialchars($record['treatment']) ?></p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($record['is_referral']): ?>
                                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full border border-amber-200">
                                                    <i class="fa fa-ambulance mr-1"></i> ส่งต่อ รพ.
                                                </span>
                                            <?php else: ?>
                                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full border border-emerald-200">
                                                    สำเร็จ
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
