<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 flex flex-col md:flex-row justify-between items-center border-b border-gray-50 gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800 heading-font">รายการกิจกรรมทั้งหมด</h3>
            <p class="text-sm text-slate-500 mt-1">จัดการกำหนดการและปฏิทินวิชาการของโรงเรียน</p>
        </div>
        <a href="<?= url('/calendar/create') ?>" class="inline-flex items-center px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-primary/20">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            เพิ่มกิจกรรมใหม่
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="calendarTable" class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                    <th class="px-8 py-5">สี</th>
                    <th class="px-8 py-5">ชื่อกิจกรรม</th>
                    <th class="px-8 py-5">วันที่เริ่มต้น</th>
                    <th class="px-8 py-5">วันที่สิ้นสุด</th>
                    <th class="px-8 py-5">ผู้รับผิดชอบ</th>
                    <th class="px-8 py-5 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($events as $event): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="w-6 h-6 rounded-lg shadow-sm" style="background-color: <?= $event['color'] ?>"></div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="font-bold text-slate-900"><?= htmlspecialchars($event['title']) ?></div>
                            <?php if ($event['description']): ?>
                                <div class="text-xs text-slate-400 truncate max-w-[200px] mt-1"><?= htmlspecialchars($event['description']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-600 font-medium">
                            <?= date('d/m/Y', strtotime($event['start_date'])) ?>
                            <?php if ($event['start_time']): ?>
                                <span class="ml-1 text-[10px] bg-slate-100 px-1.5 py-0.5 rounded text-slate-500"><?= date('H:i', strtotime($event['start_time'])) ?> น.</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-600 font-medium">
                            <?= $event['end_date'] ? date('d/m/Y', strtotime($event['end_date'])) : '-' ?>
                            <?php if ($event['end_time']): ?>
                                <span class="ml-1 text-[10px] bg-slate-100 px-1.5 py-0.5 rounded text-slate-500"><?= date('H:i', strtotime($event['end_time'])) ?> น.</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                            <?= htmlspecialchars($event['responsible_person'] ?: '-') ?>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end space-x-1">
                                <a href="<?= url('/calendar/edit/' . $event['id']) ?>" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all" title="แก้ไข">
                                    <i class="fa fa-pencil text-lg"></i>
                                </a>
                                <button onclick="confirmDelete(<?= $event['id'] ?>)" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all" title="ลบ">
                                    <i class="fa fa-trash-o text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        initPremiumDataTable('#calendarTable', {
            order: [[2, 'desc']],
            columnDefs: [
                { orderable: false, targets: [0, 5] }
            ]
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบกิจกรรม?',
            text: "คุณแน่ใจหรือไม่ว่าต้องการลบกิจกรรมนี้?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ใช่, ลบเลย',
            cancelButtonText: 'ยกเลิก',
            borderRadius: '1.5rem',
            customClass: {
                confirmButton: 'rounded-xl font-bold px-6 py-3',
                cancelButton: 'rounded-xl font-bold px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= url('/calendar/delete/') ?>' + id;
            }
        });
    }
</script>
