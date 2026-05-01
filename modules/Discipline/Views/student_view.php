<div class="space-y-8 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="text-center">
        <h3 class="text-3xl font-black text-slate-800 heading-font">ประวัติพฤติกรรมของฉัน</h3>
        <p class="text-sm text-slate-400 mt-2 uppercase font-bold tracking-widest">Personal Discipline History</p>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">คะแนนความดี</p>
            <p class="text-3xl font-black text-emerald-500 outfit">+<?= $summary['positive_points'] ?></p>
        </div>
        <div class="bg-slate-900 p-8 rounded-3xl shadow-xl text-center relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
            <p class="text-[10px] font-bold text-blue-300 uppercase tracking-widest mb-2">คะแนนคงเหลือสุทธิ</p>
            <p class="text-5xl font-black text-white outfit">
                <?= $summary['total_score'] > 0 ? '+' . $summary['total_score'] : $summary['total_score'] ?>
            </p>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 text-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">คะแนนพฤติกรรมที่หัก</p>
            <p class="text-3xl font-black text-rose-500 outfit"><?= $summary['negative_points'] ?></p>
        </div>
    </div>

    <!-- History List -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-slate-50/30">
            <h4 class="text-lg font-bold text-slate-800">รายละเอียดรายการบันทึก</h4>
        </div>
        <div class="p-0">
            <?php if (empty($logs)): ?>
                <div class="py-20 text-center">
                    <p class="text-slate-400 font-bold">ยังไม่มีบันทึกประวัติพฤติกรรม</p>
                </div>
            <?php else: ?>
                <div class="divide-y divide-slate-50">
                    <?php foreach ($logs as $log): ?>
                    <div class="p-6 hover:bg-slate-50/50 transition-colors flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mr-5 <?= $log['points_affected'] > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' ?>">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <?php if ($log['points_affected'] > 0): ?>
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    <?php else: ?>
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                    <?php endif; ?>
                                </svg>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-slate-800"><?= $log['category_name'] ?: 'รายการพิเศษ' ?></h5>
                                <p class="text-xs text-slate-400 mt-1"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?> • <?= $log['remarks'] ?: 'ไม่มีหมายเหตุ' ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-black outfit <?= $log['points_affected'] > 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                                <?= $log['points_affected'] > 0 ? '+' . $log['points_affected'] : $log['points_affected'] ?>
                            </span>
                            <p class="text-[9px] font-bold text-slate-300 uppercase tracking-widest mt-1">Points</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
