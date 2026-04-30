<?php $primaryColor = \Core\Database::getSetting('primary_color', '#1d4ed8'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex items-center space-x-6 hover:shadow-xl transition-all group">
        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707.293l-2.414-2.414A1 1 0 006.5 13H4"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">จดหมายเข้าใหม่</p>
            <h4 class="text-3xl font-black text-slate-900 outfit"><?= $stats['unread'] ?> <span class="text-sm font-medium text-slate-500">รายการ</span></h4>
        </div>
    </div>
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex items-center space-x-6 hover:shadow-xl transition-all group">
        <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">หนังสือรับทั้งหมด</p>
            <h4 class="text-3xl font-black text-slate-900 outfit"><?= $stats['total_inbound'] ?> <span class="text-sm font-medium text-slate-500">รายการ</span></h4>
        </div>
    </div>
    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 flex items-center space-x-6 hover:shadow-xl transition-all group">
        <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">หนังสือส่งทั้งหมด</p>
            <h4 class="text-3xl font-black text-slate-900 outfit"><?= $stats['total_outbound'] ?> <span class="text-sm font-medium text-slate-500">รายการ</span></h4>
        </div>
    </div>
</div>

<!-- Search & Actions -->
<div class="flex flex-col lg:flex-row gap-6 mb-8">
    <form action="" method="GET" class="flex-grow bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-wrap items-end gap-4">
        <div class="flex-grow min-w-[200px]">
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">ค้นหาจากชื่อเรื่อง / เลขที่</label>
            <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="ค้นหาในกล่องขาเข้า..." class="w-full px-4 py-2.5 rounded-xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary transition-all text-sm">
        </div>
        <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-800 transition-all flex items-center h-[42px]">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            ค้นหา
        </button>
    </form>

    <?php if (\Core\Security::checkRole(['admin', 'director']) && ($userDeptId ?? 0) === 1): ?>
    <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between gap-4">
        <div class="hidden sm:block">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">การจัดการแบบกลุ่ม</p>
            <p class="text-xs text-slate-500">เลือกรายการที่ต้องการเกษียณด่วน</p>
        </div>
        <button type="button" onclick="submitBatch()" class="bg-amber-500 text-white px-8 py-3 rounded-xl font-bold text-xs hover:shadow-lg hover:shadow-amber-200 transition-all flex items-center whitespace-nowrap">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            เกษียณด่วน (ทราบ)
        </button>
    </div>
    <?php endif; ?>
</div>

<form id="batch-form" action="<?= url('/saraban/batch-endorse') ?>" method="POST">
    <?= \Core\Security::csrf_field() ?>
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-slate-800 heading-font">กล่องขาเข้า (Inbox)</h3>
                <p class="text-sm text-slate-500">รายการหนังสือเวียนที่ส่งถึงคุณหรือหน่วยงานของคุณ</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table id="inboxTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest w-10">
                            <input type="checkbox" id="selectAll" class="rounded border-slate-200 text-primary focus:ring-primary cursor-pointer">
                        </th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">สถานะ</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">เลขที่รับ/ลงวันที่</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">เรื่อง</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ความเร่งด่วน</th>
                        <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($inbox as $item): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors <?= $item['read_status'] === 'unread' ? 'bg-blue-50/10' : '' ?>">
                        <td class="px-8 py-5">
                            <input type="checkbox" name="doc_ids[]" value="<?= $item['id'] ?>" class="doc-checkbox rounded border-slate-200 text-primary focus:ring-primary cursor-pointer">
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center space-x-2">
                                <?php if ($item['read_status'] === 'unread'): ?>
                                    <span class="flex h-2 w-2 rounded-full bg-blue-500"></span>
                                    <span class="text-[10px] font-bold text-blue-500 uppercase">ใหม่</span>
                                <?php else: ?>
                                    <span class="text-emerald-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-xs font-bold text-slate-900 outfit"><?= $item['doc_no'] ?></div>
                            <div class="text-[9px] text-slate-400 uppercase"><?= date('d/m/Y', strtotime($item['doc_date'])) ?></div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-bold text-slate-800 line-clamp-1"><?= $item['title'] ?></div>
                            <div class="text-[10px] text-slate-400"><?= $item['origin'] ?></div>
                        </td>
                        <td class="px-8 py-5">
                            <?php 
                                $priorityLabel = [
                                    'normal' => 'ปกติ',
                                    'urgent' => 'ด่วน',
                                    'very_urgent' => 'ด่วนที่สุด'
                                ];
                                $priorityClass = [
                                    'normal' => 'bg-slate-50 text-slate-500 border-slate-200',
                                    'urgent' => 'bg-amber-50 text-amber-600 border-amber-200',
                                    'very_urgent' => 'bg-rose-50 text-rose-600 border-rose-200'
                                ];
                            ?>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold border <?= $priorityClass[$item['priority']] ?>">
                                <?= $priorityLabel[$item['priority']] ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <a href="<?= url('/saraban/view/' . $item['id']) ?>" class="p-2 text-primary hover:bg-primary/5 rounded-xl transition-all inline-flex items-center" title="เปิดดู">
                                <i class="fa fa-external-link text-lg"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    initPremiumDataTable('#inboxTable', {
        order: [[2, 'desc']],
        columnDefs: [
            { orderable: false, targets: [0, 1, 5] }
        ]
    });

    $('#selectAll').on('change', function() {
        $('.doc-checkbox').prop('checked', this.checked);
    });
});

function submitBatch() {
    const checkboxes = document.querySelectorAll('input[name="doc_ids[]"]:checked');
    if (checkboxes.length === 0) {
        Swal.fire({
            title: 'กรุณาเลือกรายการ',
            text: 'กรุณาเลือกรายการหนังสือที่ต้องการเกษียณด่วน',
            icon: 'warning',
            confirmButtonText: 'ตกลง',
            confirmButtonColor: '#1d4ed8',
            borderRadius: '1rem',
            customClass: {
                confirmButton: 'rounded-xl font-bold px-6 py-3',
            }
        });
        return;
    }

    Swal.fire({
        title: 'ยืนยันการเกษียณหนังสือแบบด่วน',
        text: 'คุณกำลังจะเกษียณหนังสือ ' + checkboxes.length + ' รายการ โดยลงความเห็นว่า "ทราบ/ถือปฏิบัติ" ยืนยันหรือไม่?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ยืนยันการรับทราบ',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#94a3b8',
        borderRadius: '1.5rem',
        customClass: {
            confirmButton: 'rounded-xl font-bold px-6 py-3',
            cancelButton: 'rounded-xl font-bold px-6 py-3'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('batch-form').submit();
        }
    });
}
</script>
