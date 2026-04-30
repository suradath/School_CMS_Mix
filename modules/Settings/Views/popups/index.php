<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 flex flex-col md:flex-row justify-between items-center border-b border-gray-50 gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800 heading-font">Entry Popup ทั้งหมด</h3>
            <p class="text-sm text-slate-500 mt-1">จัดการป๊อปอัพแจ้งเตือนเมื่อเข้าสู่หน้าแรกของเว็บไซต์</p>
        </div>
        <a href="<?= url('/settings/popups/create') ?>" class="inline-flex items-center px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-primary/20">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            เพิ่ม Entry Popup
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="popupTable" class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                    <th class="px-8 py-5">รูปภาพ</th>
                    <th class="px-8 py-5">ชื่อรายการ</th>
                    <th class="px-8 py-5">สถานะ</th>
                    <th class="px-8 py-5 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($popups as $p): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5">
                            <img src="<?= url($p['image_url']) ?>" class="h-16 w-auto rounded-lg shadow-sm border border-gray-100 object-cover" alt="">
                        </td>
                        <td class="px-8 py-5 font-bold text-slate-900">
                            <?= htmlspecialchars($p['title']) ?>
                            <?php if($p['link_url']): ?>
                                <div class="text-xs text-slate-400 font-medium truncate max-w-xs mt-1"><?= $p['link_url'] ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5">
                            <?php if ($p['is_active']): ?>
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-full">
                                    <span class="w-1 h-1 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> เปิดใช้งาน
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-full">ปิดใช้งาน</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end space-x-1">
                                <a href="<?= url('/settings/popups/edit/' . $p['id']) ?>" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all" title="แก้ไข">
                                    <i class="fa fa-pencil text-lg"></i>
                                </a>
                                <button onclick="confirmDelete(<?= $p['id'] ?>)" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all" title="ลบ">
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
        initPremiumDataTable('#popupTable', {
            order: [[1, 'asc']],
            columnDefs: [
                { orderable: false, targets: [0, 3] }
            ]
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบ Popup?',
            text: "คุณแน่ใจหรือไม่ว่าต้องการลบ Popup นี้?",
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
                window.location.href = '<?= url('/settings/popups/delete/') ?>' + id;
            }
        });
    }
</script>
