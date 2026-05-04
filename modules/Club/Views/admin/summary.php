<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
    <div>
        <a href="<?= url('/club') ?>"
            class="inline-flex items-center text-slate-400 hover:text-primary transition-colors font-bold mb-4">
            <i class="fa fa-arrow-left mr-2"></i> กลับหน้าจัดการ
        </a>
        <h2 class="text-3xl font-black text-slate-800 heading-font">สรุปการเข้าเรียน</h2>
        <p class="text-slate-500 font-medium">ชุมนุม: <?= htmlspecialchars($club['name']) ?></p>
    </div>
    <div class="flex gap-2">
        <button onclick="window.print()"
            class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-600 text-sm font-bold rounded-2xl hover:bg-slate-200 transition-all">
            <i class="fa fa-print mr-2"></i> พิมพ์รายงาน
        </button>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden p-8">
    <div class="overflow-x-auto">
        <table id="summaryTable" class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] heading-font">
                    <th class="px-8 py-5">รหัสนักเรียน</th>
                    <th class="px-8 py-5">ชื่อ-นามสกุล</th>
                    <th class="px-8 py-5">ชั้น/ห้อง</th>
                    <th class="px-8 py-5 text-center">มาเรียน (ครั้ง)</th>
                    <th class="px-8 py-5 text-center">ขาด (ครั้ง)</th>
                    <th class="px-8 py-5 text-center">ลา (ครั้ง)</th>
                    <th class="px-8 py-5 text-center">รวมวันที่เช็ค</th>
                    <th class="px-8 py-5 text-center">ร้อยละการเข้าเรียน</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($summary as $s): ?>
                    <?php
                    $percent = ($s['total_days'] > 0) ? ($s['present_count'] / $s['total_days']) * 100 : 0;
                    $percentColor = ($percent >= 80) ? 'text-emerald-600' : (($percent >= 50) ? 'text-amber-600' : 'text-rose-600');
                    ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5 text-sm font-bold text-slate-600"><?= $s['student_code'] ?></td>
                        <td class="px-8 py-5 font-bold text-slate-900"><?= $s['first_name'] . ' ' . $s['last_name'] ?></td>
                        <td class="px-8 py-5 text-sm text-slate-500 font-medium"><?= $s['class_level'] ?> /
                            <?= $s['room_number'] ?></td>
                        <td class="px-8 py-5 text-center font-bold text-emerald-600"><?= $s['present_count'] ?></td>
                        <td class="px-8 py-5 text-center font-bold text-rose-600"><?= $s['absent_count'] ?></td>
                        <td class="px-8 py-5 text-center font-bold text-amber-600"><?= $s['leave_count'] ?></td>
                        <td class="px-8 py-5 text-center font-medium text-slate-500"><?= $s['total_days'] ?></td>
                        <td class="px-8 py-5 text-center">
                            <span class="font-black <?= $percentColor ?>"><?= number_format($percent, 2) ?>%</span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        initPremiumDataTable('#summaryTable', {
            pageLength: 50,
            order: [[0, 'asc']]
        });
    });
</script>