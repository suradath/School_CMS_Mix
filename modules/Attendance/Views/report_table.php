<div class="p-8 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
    <div>
        <h3 class="text-lg font-bold text-slate-800 heading-font">
            สรุปสถิติห้อง <?= $level ?>/<?= $room ?>
        </h3>
        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">อ้างอิงจากฐานข้อมูลล่าสุด</p>
    </div>
    <div id="dt-buttons" class="flex space-x-2">
        <!-- DataTables buttons will be injected here if needed, or we use default -->
    </div>
</div>

<div class="p-6">
    <div class="overflow-x-auto">
        <table id="report-table" class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-gray-100">
                <tr>
                    <th class="px-4 py-4 text-center">#</th>
                    <th class="px-4 py-4">รหัส</th>
                    <th class="px-4 py-4">ชื่อ-นามสกุล</th>
                    <th class="px-4 py-4 text-center">คาบรวม</th>
                    <th class="px-4 py-4 text-center text-green-600">มาเรียน</th>
                    <th class="px-4 py-4 text-center text-orange-600">มาสาย</th>
                    <th class="px-4 py-4 text-center text-red-700">ขาด</th>
                    <th class="px-4 py-4 text-center text-blue-600">ลา</th>
                    <th class="px-4 py-4 text-center">ร้อยละ</th>
                    <th class="px-4 py-4 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($reportData as $index => $row): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-4 text-center font-medium text-slate-400"><?= $index + 1 ?></td>
                    <td class="px-4 py-4 font-bold text-slate-700 outfit"><?= $row['student_code'] ?></td>
                    <td class="px-4 py-4 font-bold text-slate-800">
                        <?= ($row['title'] ?? '') . $row['first_name'] . ' ' . $row['last_name'] ?>
                    </td>
                    <td class="px-4 py-4 text-center font-bold text-slate-600"><?= $row['total_periods'] ?></td>
                    <td class="px-4 py-4 text-center text-green-600 font-bold"><?= $row['count_present'] ?></td>
                    <td class="px-4 py-4 text-center text-orange-600 font-bold"><?= $row['count_late'] ?></td>
                    <td class="px-4 py-4 text-center text-red-700 font-bold"><?= $row['count_absent'] ?></td>
                    <td class="px-4 py-4 text-center text-blue-600 font-bold"><?= $row['count_leave'] ?></td>
                    <td class="px-4 py-4 text-center">
                        <?php 
                            $pct = (float)$row['attendance_percentage'];
                            $colorClass = $pct < 80 ? 'bg-red-50 text-red-600 border-red-200' : 'bg-green-50 text-green-600 border-green-200';
                        ?>
                        <div class="inline-flex items-center px-3 py-1 rounded-full border <?= $colorClass ?> font-bold outfit text-xs">
                            <?= number_format($pct, 2) ?>%
                            <?php if ($pct < 80): ?>
                                <i class="fa fa-warning ml-1 text-[10px]"></i>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <button type="button" onclick="showAttendanceCalendar(<?= $row['id'] ?>)" class="px-3 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-bold hover:bg-blue-600 hover:text-white transition-all">
                            ดูรายละเอียด
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#report-table')) {
            $('#report-table').DataTable().destroy();
        }

        initPremiumDataTable('#report-table', {
            pageLength: 25,
            columnDefs: [
                { targets: [0, 3, 4, 5, 6, 7, 8, 9], className: 'text-center' },
                { orderable: false, targets: [9] }
            ]
        });
    }
</script>
