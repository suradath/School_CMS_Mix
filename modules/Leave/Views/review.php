<div class="mb-6">
    <h3 class="text-xl font-bold text-slate-800 heading-font">รายการพิจารณาใบลา</h3>
    <p class="text-sm text-slate-500 mt-1">รายการคำขอลาที่รอการตรวจสอบและอนุมัติ</p>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ผู้ขอลา</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">รายละเอียด</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">จำนวนวัน</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">สถานะ</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">การจัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($requests)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-400 font-medium">ไม่มีรายการใบลาที่ต้องพิจารณา</td>
                </tr>
                <?php endif; ?>
                <?php foreach ($requests as $req): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs mr-3 overflow-hidden border border-gray-100">
                            <?php if (!empty($req['image_url'])): ?>
                                <img src="<?= $req['image_url'] ?>" class="w-full h-full object-cover" alt="<?= $req['personnel_name'] ?>">
                            <?php else: ?>
                                <?= mb_substr($req['personnel_name'], 0, 2, 'UTF-8') ?>
                            <?php endif; ?>
                        </div>
                            <div>
                                <div class="text-sm font-bold text-slate-800"><?= $req['personnel_name'] ?></div>
                                <div class="text-[10px] font-bold text-primary uppercase tracking-tight"><?= $req['department_name'] ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center mb-1">
                            <span class="w-2 h-2 rounded-full mr-2" style="background-color: <?= $req['leave_type_color'] ?>"></span>
                            <span class="text-xs font-bold text-slate-600"><?= $req['leave_type_name'] ?></span>
                        </div>
                        <div class="text-xs font-medium text-slate-500"><?= date('d/m/Y', strtotime($req['start_date'])) ?> - <?= date('d/m/Y', strtotime($req['end_date'])) ?></div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs"><?= $req['total_days'] ?> วัน</span>
                    </td>
                    <td class="px-6 py-4">
                        <?php 
                        $statusColors = [
                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                            'cancelled' => 'bg-slate-50 text-slate-400 border-slate-100'
                        ];
                        $statusText = [
                            'pending' => 'รอพิจารณา',
                            'approved' => 'อนุมัติแล้ว',
                            'rejected' => 'ปฏิเสธ',
                            'cancelled' => 'ยกเลิก'
                        ];
                        ?>
                        <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest border <?= $statusColors[$req['status']] ?>">
                            <?= $statusText[$req['status']] ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button onclick="openModal(<?= $req['id'] ?>)" class="bg-slate-900 text-white px-4 py-2 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all shadow-sm">
                            จัดการ
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for approval (Simplified) -->
<div id="approvalModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h4 class="text-lg font-bold text-slate-800">พิจารณาใบลา</h4>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="approvalForm" method="POST" class="p-6 space-y-4">
            <?= \Core\Security::csrf_field() ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ความเห็นเพิ่มเติม</label>
                    <textarea name="<?= \Core\Security::checkRole('admin') ? 'admin_comment' : 'dept_head_comment' ?>" rows="3" class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm" placeholder="ระบุเหตุผลในการอนุมัติหรือปฏิเสธ..."></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <button type="submit" name="status" value="rejected" class="w-full py-3 bg-rose-50 text-rose-600 font-bold rounded-2xl border border-rose-100 hover:bg-rose-100 transition-all">ปฏิเสธ</button>
                    <button type="submit" name="status" value="approved" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:shadow-lg hover:shadow-emerald-200 transition-all">อนุมัติ</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    const modal = document.getElementById('approvalModal');
    const form = document.getElementById('approvalForm');
    form.action = '/leave/updateStatus/' + id;
    modal.classList.remove('hidden');
}
function closeModal() {
    document.getElementById('approvalModal').classList.add('hidden');
}
</script>
