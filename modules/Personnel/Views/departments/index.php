<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 flex flex-col md:flex-row justify-between items-center border-b border-gray-50 gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800 heading-font">กลุ่มสาระฯ / ฝ่ายงาน ทั้งหมด</h3>
            <p class="text-sm text-slate-500 mt-1">จัดการโครงสร้างหน่วยงานและลำดับการแสดงผลของบุคลากร</p>
        </div>
        <a href="<?= url('/personnel/departments/create') ?>" class="inline-flex items-center px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-primary/20">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            เพิ่มกลุ่มสาระฯ / ฝ่ายงาน
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="deptTable" class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                    <th class="px-8 py-5 w-20">ลำดับ</th>
                    <th class="px-8 py-5">ชื่อกลุ่มสาระฯ / ฝ่ายงาน</th>
                    <th class="px-8 py-5 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($departments as $dept): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center justify-center w-8 h-8 bg-slate-100 text-slate-500 rounded-lg text-xs font-bold">
                                <?= $dept['sort_order'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 font-bold text-slate-900">
                            <?= htmlspecialchars($dept['name']) ?>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end space-x-1">
                                <a href="<?= url('/personnel/departments/edit/' . $dept['id']) ?>" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all" title="แก้ไข">
                                    <i class="fa fa-pencil text-lg"></i>
                                </a>
                                <button onclick="confirmDelete(<?= $dept['id'] ?>)" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all" title="ลบ">
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
        initPremiumDataTable('#deptTable', {
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [2] }
            ]
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบกลุ่มสาระฯ?',
            text: "จะไม่สามารถลบได้หากยังมีบุคลากรอยู่ในกลุ่มนี้",
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
                window.location.href = "<?= url('/personnel/departments/delete/') ?>" + id;
            }
        });
    }
</script>
