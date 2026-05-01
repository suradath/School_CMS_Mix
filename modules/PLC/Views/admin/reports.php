<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 heading-font">สรุปชั่วโมง PLC ทั้งโรงเรียน</h1>
            <p class="text-slate-500 mt-1">ติดตามความก้าวหน้าการสะสมชั่วโมงของบุคลากรทุกคน</p>
        </div>
        <div class="flex items-center gap-3">
            <select onchange="window.location.href='?year='+this.value" class="px-5 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold outline-none focus:ring-4 focus:ring-primary/10 transition-all">
                <?php 
                $currentYearBE = date('Y') + 543;
                for($y = $currentYearBE; $y >= $currentYearBE-2; $y--): 
                ?>
                    <option value="<?= $y ?>" <?= $academicYear == $y ? 'selected' : '' ?>>ปีการศึกษา พ.ศ. <?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>

    <!-- Summary Table Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8">
            <table class="w-full text-left datatable">
                <thead>
                    <tr class="text-slate-400 text-xs font-bold uppercase tracking-widest border-b border-slate-50">
                        <th class="pb-4 pl-4">ชื่อ-นามสกุล</th>
                        <th class="pb-4">ตำแหน่ง / สังกัด</th>
                        <th class="pb-4 text-center">ชั่วโมงสะสม</th>
                        <th class="pb-4 text-center">ความก้าวหน้า</th>
                        <th class="pb-4 text-right pr-4">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($summary as $user): ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-5 pl-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary mr-4 font-bold">
                                    <?= mb_substr($user['full_name'], 0, 1) ?>
                                </div>
                                <p class="font-bold text-slate-700"><?= $user['full_name'] ?></p>
                            </div>
                        </td>
                        <td class="py-5">
                            <p class="text-sm font-bold text-slate-600"><?= $user['position_name'] ?: 'คุณครู' ?></p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight"><?= $user['department_name'] ?: 'ไม่ระบุ' ?></p>
                        </td>
                        <td class="py-5 text-center">
                            <span class="text-xl font-black text-primary"><?= (float)($user['total_hours'] ?? 0) ?></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase">ชม.</span>
                        </td>
                        <td class="py-5">
                            <?php 
                                $hours = (float)($user['total_hours'] ?? 0);
                                $percent = min(100, ($hours / $targetGoal) * 100);
                                $color = $percent >= 100 ? 'bg-emerald-500' : ($percent > 50 ? 'bg-primary' : 'bg-amber-500');
                            ?>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden max-w-[120px] mx-auto">
                                <div class="h-full <?= $color ?> rounded-full" style="width: <?= $percent ?>%"></div>
                            </div>
                            <p class="text-[10px] text-center mt-1 font-bold <?= $percent >= 100 ? 'text-emerald-500' : 'text-slate-400' ?>">
                                <?= $percent >= 100 ? 'ครบตามเกณฑ์' : number_format($percent, 0) . '%' ?>
                            </p>
                        </td>
                        <td class="py-5 text-right pr-4">
                            <a href="<?= url('/plc/report?user_id=' . $user['id'] . '&year=' . $academicYear) ?>" target="_blank" class="px-4 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl hover:bg-primary hover:text-white transition">
                                ดูรายละเอียด
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
