<article class="bg-white py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex mb-8 text-sm font-medium text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="hover:text-primary font-semibold">หน้าแรก</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                        <a href="/news-all" class="ml-1 md:ml-2 hover:text-primary font-semibold">ข่าวประชาสัมพันธ์</a>
                    </div>
                </li>
            </ol>
        </nav>

        <?php if(!empty($item['featured_image'])): ?>
            <header class="mb-10 text-center">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 heading-font mb-6 leading-tight"><?= $title ?></h1>
                <div class="flex items-center justify-center text-sm font-medium text-gray-400 space-x-4">
                    <span class="flex items-center"><svg class="w-4 h-4 mr-1 pb-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><?= date('d/m/Y H:i', strtotime($item['published_at'])) ?></span>
                    <span>•</span>
                    <span>โดย <?= $item['author_name'] ?? 'Admin' ?></span>
                </div>
            </header>
            <div class="mb-12 rounded-3xl overflow-hidden shadow-2xl ring-1 ring-gray-100 animate-fade-in">
                <img src="<?= $item['featured_image'] ?>" class="w-full h-auto object-cover max-h-[500px]" alt="<?= $title ?>">
            </div>
        <?php else: ?>
            <div class="mb-12 rounded-3xl overflow-hidden shadow-2xl bg-primary text-white p-12 md:p-20 text-center animate-fade-in">
                <div class="flex items-center justify-center text-xs font-bold text-white/70 space-x-4 mb-6 uppercase tracking-widest">
                    <span class="flex items-center"><svg class="w-4 h-4 mr-1 pb-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg><?= date('d/m/Y H:i', strtotime($item['published_at'])) ?></span>
                    <span class="opacity-30">•</span>
                    <span>โดย <?= $item['author_name'] ?? 'Admin' ?></span>
                </div>
                <h1 class="text-4xl md:text-6xl font-extrabold heading-font mb-0 leading-tight drop-shadow-lg"><?= $title ?></h1>
            </div>
        <?php endif; ?>

        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed space-y-6 mb-16">
            <?= nl2br($item['content']) ?>
        </div>

        <div class="mt-16 pt-10 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-8">
            <a href="/news-all" class="text-primary font-bold flex items-center hover:translate-x-[-4px] transition group">
                <div class="w-8 h-8 bg-blue-50 rounded-full flex items-center justify-center mr-3 group-hover:bg-primary group-hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </div>
                กลับไปดูข่าวทั้งหมด
            </a>
            <div class="flex items-center space-x-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mr-2">แชร์ข่าวนี้:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="w-10 h-10 bg-[#1877F2] text-white rounded-full flex items-center justify-center hover:scale-110 transition shadow-lg shadow-blue-500/20">
                    <i class="fa fa-facebook"></i>
                </a>
                <a href="https://social-plugins.line.me/lineit/share?url=<?= urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" target="_blank" class="w-10 h-10 bg-[#06C755] text-white rounded-full flex items-center justify-center hover:scale-110 transition shadow-lg shadow-green-500/20">
                    <i class="fa fa-comment"></i>
                </a>
                <a href="https://x.com/intent/tweet?url=<?= urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>&text=<?= urlencode($title) ?>" target="_blank" class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center hover:scale-110 transition shadow-lg shadow-slate-900/20">
                    <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932 6.064-6.932zm-1.292 19.49h2.039L6.486 3.24H4.298l13.311 17.403z"/></svg>
                </a>
                <div class="w-px h-6 bg-gray-200 mx-2"></div>
                <button onclick="window.print()" class="flex items-center space-x-2 px-5 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>พิมพ์</span>
                </button>
            </div>
        </div>
    </div>
</article>

<style>
@media print {
    nav, footer, .flex.items-center.space-x-3, .group.flex.items-center { display: none !important; }
    article { padding: 0 !important; }
    .max-w-4xl { max-width: 100% !important; }
}
</style>
