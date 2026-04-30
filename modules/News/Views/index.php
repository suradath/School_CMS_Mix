<div class="mb-6 flex justify-between items-center text-right">
    <a href="<?= url('/news/create') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition shadow-md">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        ลงข่าวใหม่
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table id="newsTable" class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    <th class="px-8 py-5">ข่าวประชาสัมพันธ์</th>
                    <th class="px-8 py-5">หมวดหมู่</th>
                    <th class="px-8 py-5">วันที่เผยแพร่</th>
                    <th class="px-8 py-5 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($news as $item): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-8 py-5">
                        <div class="flex items-center">
                            <div class="h-10 w-16 shrink-0 rounded-xl overflow-hidden bg-gray-100 mr-4 border border-gray-100">
                                <?php if ($item['featured_image']): ?>
                                    <img src="<?= url($item['featured_image']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="flex items-center justify-center h-full text-slate-300">
                                        <i class="fa fa-picture-o"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-800 line-clamp-1"><?= htmlspecialchars($item['title']) ?></div>
                                <div class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-tight"><?= htmlspecialchars($item['category_name']) ?> • โดย <?= htmlspecialchars($item['author_name']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-5">
                        <?php if ($item['status'] === 'published'): ?>
                            <span class="px-3 py-1 text-[10px] font-bold bg-emerald-50 text-emerald-600 rounded-full border border-emerald-100">เผยแพร่แล้ว</span>
                        <?php else: ?>
                            <span class="px-3 py-1 text-[10px] font-bold bg-amber-50 text-amber-600 rounded-full border border-amber-100">ฉบับร่าง</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-8 py-5 text-xs text-slate-500 font-medium">
                        <?= date('d/m/Y H:i', strtotime($item['published_at'] ?? 'now')) ?>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end space-x-1">
                            <a href="<?= url('/news/edit/' . $item['id']) ?>" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all" title="แก้ไข">
                                <i class="fa fa-pencil text-lg"></i>
                            </a>
                            <button onclick="confirmDelete(<?= $item['id'] ?>)" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all" title="ลบ">
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
        initPremiumDataTable('#newsTable', {
            order: [[2, 'desc']],
            columnDefs: [
                { orderable: false, targets: [3] }
            ]
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบข่าว?',
            text: "คุณต้องการลบข่าวนี้ใช่หรือไม่?",
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
                window.location.href = '<?= url('/news/delete/') ?>' + id;
            }
        });
    }
</script>
