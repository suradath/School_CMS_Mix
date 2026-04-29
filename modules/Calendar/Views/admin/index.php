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
        <table class="w-full text-left">
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
                <?php if (empty($events)): ?>
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <i class="fa fa-calendar-o text-5xl mb-4 opacity-20"></i>
                                <p class="text-lg font-medium">ยังไม่มีข้อมูลกิจกรรมในระบบ</p>
                                <a href="<?= url('/calendar/create') ?>" class="mt-4 text-primary font-bold hover:underline">เริ่มเพิ่มกิจกรรมแรกของคุณ</a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
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
                                <div class="flex justify-end space-x-2">
                                    <a href="<?= url('/calendar/edit/' . $event['id']) ?>" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-xl transition-all" title="แก้ไข">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <button onclick="confirmDelete(<?= $event['id'] ?>)" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all" title="ลบ">
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
    if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบกิจกรรมนี้?')) {
        window.location.href = '<?= url('/calendar/delete/') ?>' + id;
    }
}
</script>
