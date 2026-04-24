<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-16 text-center">
            <h2 class="text-4xl font-extrabold text-slate-900 heading-font"><?= $title ?></h2>
            <p class="text-gray-500 mt-4 max-w-2xl mx-auto">รวบรวมภาพความประทับใจและกิจกรรมต่างๆ ของโรงเรียนที่เราภาคภูมิใจ</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($albums as $album): ?>
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-2xl transition group h-full flex flex-col">
                <div class="relative h-60 overflow-hidden">
                    <img src="<?= $album['cover_image'] ?: 'https://via.placeholder.com/600x450?text=No+Cover' ?>" 
                         class="w-full h-full object-cover transition duration-500 group-hover:scale-110" 
                         alt="<?= $album['title'] ?>">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center p-6">
                        <a href="/gallery-detail/<?= $album['id'] ?>" class="px-6 py-3 bg-white text-primary font-bold rounded-2xl shadow-2xl transform translate-y-4 group-hover:translate-y-0 transition duration-300">เข้าชมอัลบั้ม</a>
                    </div>
                </div>
                <div class="p-8 flex-grow">
                    <h4 class="text-xl font-bold text-gray-900 mb-2 leading-tight"><?= $album['title'] ?></h4>
                    <p class="text-sm text-gray-400 line-clamp-2"><?= $album['description'] ?></p>
                </div>
                <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    <span><?= date('d M Y', strtotime($album['created_at'])) ?></span>
                    <span class="text-primary">อัลบั้มกิจกรรม</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if(empty($albums)): ?>
        <div class="py-24 text-center bg-white rounded-3xl border border-dashed border-gray-200">
            <p class="text-gray-400">ยังไม่ได้รับการสร้างอัลบั้มภาพกิจกรรม</p>
        </div>
        <?php endif; ?>
    </div>
</section>
