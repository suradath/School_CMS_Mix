<div class="mb-6 flex justify-between items-center text-right">
    <a href="<?= url('/gallery/create') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition shadow-md">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        สร้างอัลบั้มใหม่
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php foreach ($albums as $album): ?>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition group">
        <div class="relative h-48 overflow-hidden">
            <img src="<?= $album['cover_image'] ? url($album['cover_image']) : 'https://via.placeholder.com/400x300?text=No+Cover' ?>" 
                 class="w-full h-full object-cover transition duration-500 group-hover:scale-110" 
                 alt="<?= $album['title'] ?>">
            <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                <a href="<?= url('/gallery/view/' . $album['id']) ?>" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-bold text-sm shadow-xl">ดูอัลบั้ม</a>
            </div>
        </div>
        <div class="p-5">
            <h4 class="font-bold text-gray-900 mb-1"><?= $album['title'] ?></h4>
            <p class="text-xs text-gray-500 line-clamp-2"><?= $album['description'] ?></p>
            <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between items-center text-[10px] text-gray-400 uppercase tracking-widest font-bold">
                <span><?= date('d M Y', strtotime($album['created_at'])) ?></span>
                <div class="space-x-2">
                    <a href="<?= url('/gallery/edit/' . $album['id']) ?>" class="hover:text-blue-600 transition">แก้ไข</a>
                    <a href="<?= url('/gallery/delete/' . $album['id']) ?>" class="hover:text-red-600 transition" onclick="return confirm('ลบอัลบั้มนี้?')">ลบ</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (empty($albums)): ?>
    <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-gray-300">
        <p class="text-gray-400">ยังไม่ได้รับการสร้างอัลบั้ม</p>
    </div>
    <?php endif; ?>
</div>
