<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <h2 class="text-4xl font-extrabold text-slate-900 heading-font"><?= $title ?></h2>
            <p class="text-gray-500 mt-3">ติดตามข่าวสารกิจกรรมและความเคลื่อนไหวต่างๆ ของโรงเรียน</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($news as $item): ?>
            <article class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition flex flex-col h-full">
                <div class="h-56 overflow-hidden relative">
                    <?php if (!empty($item['featured_image'])): ?>
                        <img src="<?= url($item['featured_image']) ?>" class="w-full h-full object-cover transition duration-300 hover:scale-105" alt="">
                    <?php else: ?>
                        <div class="w-full h-full bg-primary flex items-center justify-center p-6 transition duration-300 hover:scale-105">
                            <span class="text-white text-lg font-bold text-center line-clamp-3 heading-font">
                                <?= htmlspecialchars($item['title']) ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <span class="absolute top-4 left-4 bg-primary text-white text-[10px] uppercase font-bold px-3 py-1 rounded-full shadow-lg">
                        <?= $item['category_name'] ?>
                    </span>
                </div>
                <div class="p-8 flex-grow flex flex-col">
                    <div class="flex items-center text-xs text-gray-400 mb-3">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <?= date('d M Y', strtotime($item['published_at'])) ?>
                        <span class="mx-2">•</span>
                        โดย <?= $item['author_name'] ?>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4 leading-tight group-hover:text-primary transition">
                        <a href="<?= url('/news-detail/' . $item['id']) ?>"><?= $item['title'] ?></a>
                    </h3>
                    <p class="text-gray-500 text-sm line-clamp-3 mb-6"><?= strip_tags($item['content']) ?></p>
                    <div class="mt-auto pt-6 border-t border-gray-50">
                        <a href="<?= url('/news-detail/' . $item['id']) ?>" class="text-primary text-sm font-bold hover:underline inline-flex items-center">
                            อ่านรายละเอียดเพิ่มเติม
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if(empty($news)): ?>
        <div class="py-20 text-center bg-white rounded-3xl shadow-sm border border-dashed border-gray-200">
            <p class="text-gray-400">ยังไม่พบข้อมูลข่าวสารในขณะนี้</p>
        </div>
        <?php endif; ?>
    </div>
</section>
