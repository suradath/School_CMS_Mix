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
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                    <th class="px-8 py-5">รูปภาพ</th>
                    <th class="px-8 py-5">ชื่อรายการ</th>
                    <th class="px-8 py-5">สถานะ</th>
                    <th class="px-8 py-5 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($popups)): ?>
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center text-slate-400">
                            ยังไม่มีข้อมูล Popup ในระบบ
                        </td>
                    </tr>
                <?php else: ?>
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
                                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">เปิดใช้งาน</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-500 text-xs font-bold rounded-full">ปิด</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end space-x-2">
                                    <a href="<?= url('/settings/popups/edit/' . $p['id']) ?>" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <button onclick="confirmDelete(<?= $p['id'] ?>)" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ Popup นี้?')) {
        window.location.href = '<?= url('/settings/popups/delete/') ?>' + id;
    }
}
</script>
