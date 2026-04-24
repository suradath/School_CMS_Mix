<div class="mb-6 flex justify-between items-center text-right">
    <a href="/news/create" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition shadow-md">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        ลงข่าวใหม่
    </a>
</div>

<div class="relative overflow-x-auto shadow-sm sm:rounded-2xl border border-gray-100 bg-white">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
            <tr>
                <th scope="col" class="px-6 py-4">ข่าวประชาสัมพันธ์</th>
                <th scope="col" class="px-6 py-4">หมวดหมู่</th>
                <th scope="col" class="px-6 py-4">วันที่เผยแพร่</th>
                <th scope="col" class="px-6 py-4">สถานะ</th>
                <th scope="col" class="px-6 py-4 text-right">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($news as $item): ?>
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="h-10 w-16 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 mr-4 border border-gray-100">
                            <?php if ($item['featured_image']): ?>
                                <img src="<?= $item['featured_image'] ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="flex items-center justify-center h-full text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-800 line-clamp-1"><?= htmlspecialchars($item['title']) ?></div>
                            <div class="text-xs text-slate-400 mt-1"><?= $item['category_name'] ?> • โดย <?= $item['author_name'] ?></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <?php if ($item['status'] === 'published'): ?>
                        <span class="px-3 py-1 text-xs font-bold bg-green-50 text-green-600 rounded-full">เผยแพร่แล้ว</span>
                    <?php else: ?>
                        <span class="px-3 py-1 text-xs font-bold bg-amber-50 text-amber-600 rounded-full">ฉบับร่าง</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                    <?= date('d/m/Y H:i', strtotime($item['published_at'])) ?>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <a href="/news/edit/<?= $item['id'] ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="แก้ไข">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <button onclick="confirmDelete(<?= $item['id'] ?>)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="ลบ">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($news)): ?>
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-gray-400">ยังไม่มีข่าวประชาสัมพันธ์</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function confirmDelete(id) {
    if (confirm('คุณต้องการลบข่าวนี้ใช่หรือไม่?')) {
        window.location.href = '/news/delete/' + id;
    }
}
</script>
