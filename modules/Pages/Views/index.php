<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
    <div>
        <h2 class="text-3xl font-black text-slate-800 heading-font">จัดการหน้าเว็บ</h2>
        <p class="text-slate-500 font-medium">สร้างและแก้ไขหน้าเนื้อหาแบบคงที่ (Static Pages)</p>
    </div>
    <a href="<?= url('/pages/create') ?>" class="inline-flex items-center px-6 py-3 bg-primary text-white text-sm font-bold rounded-2xl hover:shadow-lg hover:shadow-primary/30 transition-all">
        <i class="fa fa-plus mr-2"></i> สร้างหน้าใหม่
    </a>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden p-8">
    <div class="overflow-x-auto">
        <table id="pagesTable" class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] heading-font">
                    <th class="px-8 py-5">หัวข้อหน้าเว็บ</th>
                    <th class="px-8 py-5">URL Slug</th>
                    <th class="px-8 py-5">สถานะ</th>
                    <th class="px-8 py-5">ผู้เขียน</th>
                    <th class="px-8 py-5 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($pages as $p): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center">
                                <div class="h-12 w-12 flex-shrink-0 rounded-2xl overflow-hidden bg-slate-100 mr-4 border border-slate-100 flex items-center justify-center text-slate-300">
                                    <?php if (!empty($p['featured_image'])): ?>
                                        <img src="<?= url($p['featured_image']) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <i class="fa fa-file-text-o text-xl"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="font-bold text-slate-900 heading-font line-clamp-1 text-base"><?= htmlspecialchars($p['title']) ?></div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-mono">/p/<?= $p['slug'] ?></span>
                        </td>
                        <td class="px-8 py-5">
                            <?php if ($p['status'] === 'published'): ?>
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-full">เปิดใช้งาน</span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-widest rounded-full">ฉบับร่าง</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                            <?= htmlspecialchars($p['author_name'] ?? 'ระบบ') ?>
                            <div class="text-[10px] text-slate-400"><?= date('d/m/Y', strtotime($p['created_at'])) ?></div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end space-x-1">
                                <a href="<?= url('/p/' . $p['slug']) ?>" target="_blank" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all" title="ดูหน้าเว็บ">
                                    <i class="fa fa-external-link text-lg"></i>
                                </a>
                                <a href="<?= url('/pages/edit/' . $p['id']) ?>" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all" title="แก้ไข">
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
        initPremiumDataTable('#pagesTable', {
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [4] }
            ]
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบหน้าเว็บ?',
            text: "คุณแน่ใจหรือไม่ว่าต้องการลบหน้าเว็บนี้?",
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
                window.location.href = '<?= url('/pages/delete/') ?>' + id;
            }
        });
    }
</script>
