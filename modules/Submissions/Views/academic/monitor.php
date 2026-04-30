<div class="space-y-6">
    <!-- Header & Topic Selector -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h2 class="text-3xl font-black text-slate-800 heading-font">ติดตามการส่งเอกสาร</h2>
                <p class="text-slate-500 font-medium">ตรวจสอบสถานะการส่งงานของบุคลากรและให้ข้อเสนอแนะ</p>
            </div>
            
            <form action="<?= url('/submissions/monitor') ?>" method="GET" class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
                <select name="topic_id" class="px-6 py-3 rounded-2xl border border-slate-200 outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold text-slate-700 min-w-[250px]" onchange="this.form.submit()">
                    <option value="">-- เลือกหัวข้อการส่งเอกสาร --</option>
                    <?php foreach ($topics as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= ($selectedTopic && $selectedTopic['id'] == $t['id']) ? 'selected' : '' ?>>
                            <?= $t['academic_year'] ?>/<?= $t['semester'] ?> : <?= htmlspecialchars($t['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($selectedTopic): ?>
                    <a href="<?= url('/submissions/export?topic_id=' . $selectedTopic['id']) ?>" class="inline-flex items-center justify-center px-6 py-3 bg-emerald-500 text-white font-bold rounded-2xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        ส่งออก Excel
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <?php if (!$selectedTopic): ?>
        <div class="bg-white rounded-3xl p-20 text-center border border-slate-100 shadow-sm">
            <div class="w-24 h-24 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-6 text-primary">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-800 mb-2">กรุณาเลือกหัวข้อที่ต้องการตรวจสอบ</h3>
            <p class="text-slate-500 max-w-md mx-auto">เลือกหัวข้อจากเมนู Dropdown ด้านบนเพื่อดูรายการส่งงานและสถานะของบุคลากรรายบุคคล</p>
        </div>
    <?php else: ?>
        <!-- Summary Stats -->
        <?php
            $stats = [
                'total' => count($submissions),
                'submitted' => 0,
                'approved' => 0,
                'revision' => 0,
                'pending' => 0
            ];
            foreach ($submissions as $s) {
                if ($s['submission_id']) {
                    $stats['submitted']++;
                    $stats[$s['status']]++;
                }
            }
            $stats['not_submitted'] = $stats['total'] - $stats['submitted'];
        ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">ทั้งหมด</p>
                <p class="text-3xl font-black text-slate-800"><?= $stats['total'] ?></p>
            </div>
            <div class="bg-amber-50 p-6 rounded-3xl border border-amber-100 shadow-sm">
                <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-1">รอตรวจ</p>
                <p class="text-3xl font-black text-amber-600"><?= $stats['pending'] ?></p>
            </div>
            <div class="bg-emerald-50 p-6 rounded-3xl border border-emerald-100 shadow-sm">
                <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1">อนุมัติแล้ว</p>
                <p class="text-3xl font-black text-emerald-600"><?= $stats['approved'] ?></p>
            </div>
            <div class="bg-red-50 p-6 rounded-3xl border border-red-100 shadow-sm">
                <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest mb-1">ให้ปรับปรุง</p>
                <p class="text-3xl font-black text-red-600"><?= $stats['revision'] ?></p>
            </div>
            <div class="bg-slate-100 p-6 rounded-3xl border border-slate-200 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">ยังไม่ส่ง</p>
                <p class="text-3xl font-black text-slate-500"><?= $stats['not_submitted'] ?></p>
            </div>
        </div>

        <!-- Monitor Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left" id="monitorTable">
                    <thead>
                        <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                            <th class="px-8 py-5">รายชื่อบุคลากร</th>
                            <th class="px-8 py-5">ฝ่าย/กลุ่มสาระ</th>
                            <th class="px-8 py-5 text-center">วันที่ส่ง</th>
                            <th class="px-8 py-5 text-center">สถานะ</th>
                            <th class="px-8 py-5 text-right">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach ($submissions as $index => $s): ?>
                            <?php $statusInfo = \Modules\Submissions\Models\Submission::getStatusInfo($s['status'] ?? ''); ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-400 text-xs shrink-0">
                                            <?= mb_substr($s['full_name'], 0, 1) ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800"><?= $s['full_name'] ?></p>
                                            <p class="text-[11px] text-slate-400 font-medium uppercase"><?= $s['position'] ?? 'บุคลากร' ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                                    <?= $s['department'] ?? '-' ?>
                                </td>
                                <td class="px-8 py-5 text-center text-sm text-slate-500">
                                    <?php if ($s['submitted_at']): ?>
                                        <?= date('d/m/Y', strtotime($s['submitted_at'])) ?>
                                        <p class="text-[10px] text-slate-400"><?= date('H:i', strtotime($s['submitted_at'])) ?> น.</p>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <span class="inline-flex items-center px-3 py-1 <?= $statusInfo['bg'] ?> text-[10px] font-black rounded-full uppercase tracking-wider">
                                        <?= $statusInfo['label'] ?>
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <?php if ($s['submission_id']): ?>
                                        <button onclick="reviewSubmission(<?= htmlspecialchars(json_encode($s)) ?>)" class="inline-flex items-center px-4 py-2 bg-primary/5 text-primary font-bold rounded-xl hover:bg-primary hover:text-white transition-all text-xs border border-primary/10">
                                            ตรวจเอกสาร
                                        </button>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-300 italic font-medium">รอการส่ง...</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeReviewModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form action="<?= url('/submissions/update-status') ?>" method="POST" id="reviewForm">
                <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
                <input type="hidden" name="id" id="sub_id">
                <input type="hidden" name="topic_id" value="<?= $selectedTopic['id'] ?? 0 ?>">
                
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-800 heading-font">ตรวจสอบเอกสาร</h3>
                        <button type="button" onclick="closeReviewModal()" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">ข้อมูลผู้ส่ง</p>
                            <p class="font-bold text-slate-800" id="review_name">-</p>
                            <p class="text-xs text-slate-500" id="review_info">-</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-2">ไฟล์เอกสาร</p>
                            <a href="#" id="review_file_link" target="_blank" class="flex items-center text-primary font-bold text-sm hover:underline">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span id="review_filename" class="truncate">-</span>
                            </a>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">สถานะการตรวจสอบ</label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="relative flex flex-col items-center justify-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5 has-[:checked]:ring-2 has-[:checked]:ring-primary/20 group">
                                    <input type="radio" name="status" value="pending" class="absolute opacity-0">
                                    <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 mb-2 group-has-[:checked]:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-600">รอตรวจ</span>
                                </label>
                                <label class="relative flex flex-col items-center justify-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:ring-2 has-[:checked]:ring-emerald-500/20 group">
                                    <input type="radio" name="status" value="approved" class="absolute opacity-0">
                                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 mb-2 group-has-[:checked]:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-600">อนุมัติแล้ว</span>
                                </label>
                                <label class="relative flex flex-col items-center justify-center p-4 border border-slate-200 rounded-2xl cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-red-500 has-[:checked]:bg-red-50 has-[:checked]:ring-2 has-[:checked]:ring-red-500/20 group">
                                    <input type="radio" name="status" value="revision" class="absolute opacity-0">
                                    <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500 mb-2 group-has-[:checked]:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="text-xs font-bold text-slate-600">ให้ปรับปรุง</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">ข้อเสนอแนะ / หมายเหตุ (ถ้ามี)</label>
                            <textarea name="feedback" id="feedback" rows="4" placeholder="ระบุสิ่งที่ครูต้องปรับปรุง หรือข้อความชื่นชม..." class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"></textarea>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                    <button type="button" onclick="closeReviewModal()" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all">ยกเลิก</button>
                    <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-primary/20">บันทึกผลการตรวจ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function reviewSubmission(data) {
        document.getElementById('sub_id').value = data.submission_id;
        document.getElementById('review_name').innerText = data.full_name;
        document.getElementById('review_info').innerText = (data.position || 'บุคลากร') + ' | ' + (data.department || '-');
        document.getElementById('review_filename').innerText = data.original_filename;
        document.getElementById('review_file_link').href = '<?= url('') ?>' + data.file_path;
        document.getElementById('feedback').value = data.feedback || '';
        
        // Set radio button
        const radio = document.querySelector(`input[name="status"][value="${data.status || 'pending'}"]`);
        if (radio) radio.checked = true;

        document.getElementById('reviewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    $(document).ready(function() {
        if ($('#monitorTable').length) {
            initPremiumDataTable('#monitorTable', {
                pageLength: 25,
                order: [[0, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [4] }
                ]
            });
        }
    });
</script>
