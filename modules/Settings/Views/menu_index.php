<div class="mb-8 flex justify-between items-center">
    <div>
        <p class="text-slate-500 text-sm">จัดการลำดับและรายการเมนูที่แสดงบนหน้าเว็บไซต์หลัก</p>
    </div>
    <a href="<?= url('/settings/menu/create') ?>" class="bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-2xl transition shadow-lg shadow-blue-500/20 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        เพิ่มเมนู
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table id="menuTable" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-gray-100">
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">ลำดับ</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">ไอคอน</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">เมนูหลัก</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">ชื่อเมนู</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">URL / Path</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">สถานะ</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($menus as $menu): ?>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-6">
                        <span class="text-sm font-bold text-slate-400 outfit"><?= $menu['sort_order'] ?></span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600">
                            <i class="fa <?= $menu['icon'] ?: 'fa-bars' ?> text-lg"></i>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-xs font-bold text-slate-500"><?= htmlspecialchars($menu['parent_title']) ?></span>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-slate-900 font-bold"><?= $menu['title'] ?></span>
                    </td>
                    <td class="px-8 py-6">
                        <code class="text-xs font-bold text-primary bg-primary/5 px-2 py-1 rounded"><?= $menu['url'] ?></code>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <?php if ($menu['is_active']): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-tighter">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> เปิดใช้งาน
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-slate-50 text-slate-400 border border-slate-100 uppercase tracking-tighter">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-2"></span> ปิดใช้งาน
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex justify-end space-x-1">
                            <a href="<?= url('/settings/menu/edit/' . $menu['id']) ?>" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all" title="แก้ไข">
                                <i class="fa fa-pencil text-lg"></i>
                            </a>
                            <button onclick="confirmDelete(<?= $menu['id'] ?>)" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all" title="ลบ">
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
        initPremiumDataTable('#menuTable', {
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [1, 6] }
            ]
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบเมนู?',
            text: "คุณแน่ใจหรือไม่ว่าต้องการลบเมนูนี้?",
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
                window.location.href = '<?= url('/settings/menu/delete/') ?>' + id;
            }
        });
    }
</script>
    </div>
</div>
