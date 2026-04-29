<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <?php foreach ($quotaStats as $stat): 
        $percent = ($stat['quota'] > 0) ? ($stat['used'] / $stat['quota']) * 100 : 0;
        $color = $stat['color'] ?? '#3b82f6';
    ?>
    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 rounded-2xl" style="background-color: <?= $color ?>20">
                <span class="font-bold" style="color: <?= $color ?>"><?= $stat['name'] ?></span>
            </div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">โควตา <?= $stat['quota'] ?> วัน</span>
        </div>
        <div class="flex items-end justify-between mb-2">
            <h4 class="text-3xl font-extrabold text-slate-900"><?= $stat['used'] ?> <span class="text-sm font-medium text-slate-400">วัน</span></h4>
            <span class="text-sm font-bold text-slate-500">เหลือ <?= $stat['remaining'] ?> วัน</span>
        </div>
        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-1000" style="width: <?= min(100, $percent) ?>%; background-color: <?= $color ?>"></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="flex justify-between items-center mb-6">
    <h3 class="text-xl font-bold text-slate-800 heading-font">ประวัติการลาของฉัน</h3>
    <a href="<?= url('/leave/create') ?>" class="bg-primary hover:bg-blue-700 text-white px-6 py-2.5 rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 transition-all flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
        เขียนใบลาใหม่
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ประเภทการลา</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">วันที่ลา</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">จำนวนวัน</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">สถานะ</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">ดำเนินการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($history)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-400 font-medium">ยังไม่มีประวัติการลาของคุณ</td>
                </tr>
                <?php endif; ?>
                <?php foreach ($history as $req): 
                    $statusColors = [
                        'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                        'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        'rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                        'cancelled' => 'bg-slate-50 text-slate-400 border-slate-100'
                    ];
                    $statusText = [
                        'pending' => 'รออนุมัติ',
                        'approved' => 'อนุมัติแล้ว',
                        'rejected' => 'ไม่อนุมัติ',
                        'cancelled' => 'ยกเลิกแล้ว'
                    ];
                ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <span class="w-2 h-2 rounded-full mr-3" style="background-color: <?= $req['leave_type_color'] ?>"></span>
                            <span class="font-bold text-slate-700 text-sm"><?= $req['leave_type_name'] ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-bold text-slate-600"><?= date('d/m/Y', strtotime($req['start_date'])) ?> - <?= date('d/m/Y', strtotime($req['end_date'])) ?></div>
                        <div class="text-[10px] text-slate-400 uppercase tracking-tight mt-0.5">ส่งเมื่อ: <?= date('d/m/Y H:i', strtotime($req['created_at'])) ?></div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs"><?= $req['total_days'] ?> วัน</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest border <?= $statusColors[$req['status']] ?>">
                            <?= $statusText[$req['status']] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-slate-400 hover:text-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
