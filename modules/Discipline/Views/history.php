<div class="space-y-8 max-w-6xl mx-auto">
    <!-- Header with Back Button -->
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="<?= url('/discipline') ?>" class="p-3 bg-white text-slate-400 rounded-2xl mr-5 shadow-sm border border-gray-100 hover:text-primary transition-all group">
                <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h3 class="text-3xl font-black text-slate-800 heading-font">ประวัติพฤติกรรม</h3>
                <p class="text-sm text-slate-500 mt-1 uppercase font-bold tracking-widest">Student Discipline Log</p>
            </div>
        </div>
        <a href="<?= url('/discipline/record?student_id=' . $student['id']) ?>" class="px-6 py-3.5 bg-primary text-white rounded-2xl font-bold text-sm shadow-xl shadow-primary/20 hover:-translate-y-0.5 transition-all flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            เพิ่มรายการบันทึก
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Student Sidebar Stats -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-gradient-to-tr from-slate-800 to-slate-900 rounded-[2rem] flex items-center justify-center text-white text-3xl font-black mb-6 shadow-xl shadow-slate-200">
                    <?= substr($student['first_name'], 0, 1) ?>
                </div>
                <h4 class="text-xl font-black text-slate-800 heading-font"><?= $student['title'] . $student['first_name'] . ' ' . $student['last_name'] ?></h4>
                <p class="text-xs font-bold text-slate-400 mt-2 tracking-widest uppercase"><?= $student['student_code'] ?></p>
                <div class="mt-4 px-4 py-1.5 bg-slate-100 rounded-full text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                    <?= $student['class_level'] . '/' . $student['room_number'] ?>
                </div>

                <div class="w-full mt-8 pt-8 border-t border-slate-50 grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">ความดี</p>
                        <p class="text-lg font-black text-emerald-500 outfit">+<?= $summary['positive_points'] ?></p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">ความผิด</p>
                        <p class="text-lg font-black text-rose-500 outfit"><?= $summary['negative_points'] ?></p>
                    </div>
                </div>

                <div class="w-full mt-6 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">คะแนนรวมสุทธิ</p>
                    <p class="text-4xl font-black outfit <?= $summary['total_score'] >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                        <?= $summary['total_score'] > 0 ? '+' . $summary['total_score'] : $summary['total_score'] ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- History Timeline -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gradient-to-r from-white to-slate-50/50">
                    <h3 class="text-xl font-bold text-slate-800 heading-font">ประวัติรายการบันทึก</h3>
                </div>
                <div class="p-8">
                    <?php if (empty($logs)): ?>
                        <div class="py-20 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-slate-400 font-bold">ไม่พบประวัติพฤติกรรมของนักเรียนรายนี้</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table id="historyTable" class="w-full text-left border-collapse">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">วันที่ / เวลา</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">รายการพฤติกรรม</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">คะแนน</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">ผู้บันทึก</th>
                                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php foreach ($logs as $log): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-slate-700"><?= date('d/m/Y', strtotime($log['created_at'])) ?></div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5"><?= date('H:i', strtotime($log['created_at'])) ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-slate-800"><?= $log['category_name'] ?: 'ระบุเป็นกรณีพิเศษ' ?></div>
                                            <div class="text-xs text-slate-400 mt-1 line-clamp-1 italic" title="<?= $log['remarks'] ?>"><?= $log['remarks'] ?: '-' ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-[11px] font-black outfit <?= $log['points_affected'] > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' ?>">
                                                <?= $log['points_affected'] > 0 ? '+' . $log['points_affected'] : $log['points_affected'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <?php if ($log['is_auto']): ?>
                                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-blue-100">System Auto</span>
                                                <?php else: ?>
                                                    <div class="w-6 h-6 bg-slate-100 rounded-lg flex items-center justify-center text-[10px] font-bold text-slate-400 mr-2">
                                                        <?= substr($log['recorder_name'] ?: 'U', 0, 1) ?>
                                                    </div>
                                                    <span class="text-xs font-bold text-slate-600"><?= $log['recorder_name'] ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button onclick="deleteLog(<?= $log['id'] ?>)" class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        initPremiumDataTable('#historyTable', {
            order: [[0, 'desc']]
        });
    });

    function deleteLog(id) {
        Swal.fire({
            title: 'ยกเลิกรายการนี้?',
            text: "คะแนนจะถูกปรับปรุงให้กลับมาเหมือนเดิม",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ยืนยันการลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= url('/discipline/deleteLog') ?>';
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = 'csrf_token';
                csrf.value = '<?= \Core\Security::csrf_token() ?>';
                form.appendChild(csrf);

                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'id';
                inputId.value = id;
                form.appendChild(inputId);

                const inputSid = document.createElement('input');
                inputSid.type = 'hidden';
                inputSid.name = 'student_id';
                inputSid.value = '<?= $student['id'] ?>';
                form.appendChild(inputSid);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
