<!-- Hero / Header Section -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<?php if ($home_header_mode === 'carousel' && !empty($home_carousel_data)): ?>
    <section class="relative w-full h-[600px] md:h-[800px] overflow-hidden">
        <div class="swiper mainCarousel h-full">
            <div class="swiper-wrapper">
                <?php foreach ($home_carousel_data as $slide): ?>
                    <div class="swiper-slide relative">
                        <img src="<?= $slide['image'] ?>" class="absolute inset-0 w-full h-full object-cover"
                            alt="<?= htmlspecialchars($slide['title']) ?>">
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
                                <h2
                                    class="text-4xl md:text-7xl font-extrabold heading-font mb-6 leading-tight drop-shadow-2xl animate-fade-in-up">
                                    <?= htmlspecialchars($slide['title']) ?>
                                </h2>
                                <p class="text-lg md:text-2xl opacity-90 max-w-3xl mx-auto font-light leading-relaxed mb-10 animate-fade-in-up"
                                    style="animation-delay: 0.1s">
                                    <?= htmlspecialchars($slide['subtitle']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Pagination/Navigation -->
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next !text-white !opacity-50 hover:!opacity-100 transition-opacity"></div>
            <div class="swiper-button-prev !text-white !opacity-50 hover:!opacity-100 transition-opacity"></div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (document.querySelector('.mainCarousel')) {
                new Swiper('.mainCarousel', {
                    loop: true,
                    autoplay: { delay: 5000, disableOnInteraction: false },
                    pagination: { el: '.swiper-pagination', clickable: true },
                    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                    effect: 'fade',
                    fadeEffect: { crossFade: true }
                });
            }
        });
    </script>

<?php else: ?>
    <!-- Fallback Hero Section with Cover Image -->
    <section
        class="hero-gradient text-white py-32 relative overflow-hidden <?= !empty($home_cover_image) ? 'has-cover' : '' ?>"
        style="<?= !empty($home_cover_image) ? "background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{$home_cover_image}') center/cover no-repeat;" : "" ?>">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-64 h-64 bg-white rounded-full blur-3xl scale-150"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl scale-150"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <span
                class="inline-block px-4 py-1.5 mb-6 text-sm font-bold tracking-widest uppercase bg-white/10 backdrop-blur-md border border-white/20 rounded-full animate-fade-in-up">
                Official School CMS
            </span>
            <h1 class="text-6xl md:text-8xl font-extrabold heading-font mb-8 leading-tight drop-shadow-2xl animate-fade-in-up"
                style="animation-delay: 0.1s">
                <?= nl2br(htmlspecialchars($home_hero_title)) ?>
            </h1>
            <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto font-light leading-relaxed mb-12 animate-fade-in-up"
                style="animation-delay: 0.2s">
                <?= htmlspecialchars($home_hero_subtitle) ?>
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-6 animate-fade-in-up" style="animation-delay: 0.3s">
                <a href="<?= htmlspecialchars($home_hero_button_url) ?>"
                    class="px-10 py-4 bg-white text-primary font-bold rounded-2xl shadow-2xl hover:bg-blue-50 transition-all hover:scale-105">
                    <?= htmlspecialchars($home_hero_button_text) ?>
                </a>
                <a href="<?= url('/about-us') ?>"
                    class="px-10 py-4 bg-transparent border-2 border-white/50 text-white font-bold rounded-2xl hover:bg-white/10 backdrop-blur-sm transition-all hover:border-white">
                    รู้จักโรงเรียนของเรา
                </a>
            </div>

            <!-- Social Links in Hero -->
            <div class="mt-12 flex justify-center space-x-6 animate-fade-in-up" style="animation-delay: 0.4s">
                <?php if (!empty($socials['facebook'])): ?>
                    <a href="<?= htmlspecialchars($socials['facebook']) ?>" target="_blank"
                        class="text-white/70 hover:text-white transition-colors"><i class="fa fa-facebook fa-lg"></i></a>
                <?php endif; ?>
                <?php if (!empty($socials['line'])): ?>
                    <a href="<?= htmlspecialchars($socials['line']) ?>" target="_blank"
                        class="text-white/70 hover:text-white transition-colors"><i class="fa fa-comment fa-lg"></i></a>
                <?php endif; ?>
                <?php if (!empty($socials['youtube'])): ?>
                    <a href="<?= htmlspecialchars($socials['youtube']) ?>" target="_blank"
                        class="text-white/70 hover:text-white transition-colors"><i class="fa fa-youtube-play fa-lg"></i></a>
                <?php endif; ?>
                <?php if (!empty($socials['tiktok'])): ?>
                    <a href="<?= htmlspecialchars($socials['tiktok']) ?>" target="_blank"
                        class="text-white/70 hover:text-white transition-colors"><i class="fa fa-music fa-lg"></i></a>
                <?php endif; ?>
                <?php if (!empty($socials['twitter'])): ?>
                    <a href="<?= htmlspecialchars($socials['twitter']) ?>" target="_blank"
                        class="text-white/70 hover:text-white transition-colors" title="X (Twitter)">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932 6.064-6.932zm-1.292 19.49h2.039L6.486 3.24H4.298l13.311 17.403z" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Stats / Highlights -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 relative z-20">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="card-glass p-8 rounded-5xl shadow-2xl transition-all hover:-translate-y-2 group text-center">
            <div
                class="w-14 h-14 bg-blue-500/10 text-primary rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:bg-primary group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path
                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
            </div>
            <h4 class="text-4xl font-bold text-slate-900 mb-1 outfit"><?= $student_count ?>+</h4>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">นักเรียนทั้งหมด</p>
        </div>

        <div class="card-glass p-8 rounded-5xl shadow-2xl transition-all hover:-translate-y-2 group text-center">
            <div
                class="w-14 h-14 bg-green-500/10 text-green-600 rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:bg-green-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
            <h4 class="text-4xl font-bold text-slate-900 mb-1 outfit"><?= $personnel_count ?>+</h4>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">คณะครูและบุคลากร</p>
        </div>

        <div class="card-glass p-8 rounded-5xl shadow-2xl transition-all hover:-translate-y-2 group text-center">
            <div
                class="w-14 h-14 bg-purple-500/10 text-purple-600 rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:bg-purple-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
            <h4 class="text-4xl font-bold text-slate-900 mb-1 outfit"><?= $classroom_count ?>+</h4>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">ห้องเรียนคุณภาพ</p>
        </div>

        <div class="card-glass p-8 rounded-5xl shadow-2xl transition-all hover:-translate-y-2 group text-center">
            <div
                class="w-14 h-14 bg-orange-500/10 text-orange-600 rounded-2xl flex items-center justify-center mb-4 mx-auto group-hover:bg-orange-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                    </path>
                </svg>
            </div>
            <h4 class="text-4xl font-bold text-slate-900 mb-1 outfit"><?= number_format($visitor_count) ?></h4>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">ผู้เข้าชม</p>
        </div>
    </div>
</div>

<!-- About Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 overflow-hidden">
    <div class="flex flex-col lg:flex-row items-center gap-20">
        <div class="lg:w-1/2 relative">
            <div class="absolute -top-10 -left-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
            <div
                class="relative z-10 rounded-5xl overflow-hidden shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-700 bg-white flex items-center justify-center p-12">
                <?php
                $aboutImage = !empty($home_about_image) ? $home_about_image : (!empty($home_cover_image) ? $home_cover_image : \Core\Database::getSetting('site_logo'));
                if (!empty($aboutImage)): ?>
                    <img src="<?= $aboutImage ?>" class="w-full h-[500px] object-contain" alt="About School">
                <?php else: ?>
                    <div class="w-full h-[500px] bg-slate-100 flex items-center justify-center">
                        <i class="fa fa-graduation-cap text-8xl text-slate-200"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="lg:w-1/2">
            <div class="h-1 w-20 bg-primary mb-8"></div>
            <h2 class="text-5xl font-extrabold text-slate-900 heading-font mb-8 leading-tight tracking-tight">
                <?= nl2br(htmlspecialchars($home_about_title)) ?></h2>
            <p class="text-xl text-slate-600 leading-relaxed mb-10">
                <?= nl2br(htmlspecialchars($home_about_content)) ?>
            </p>
            <div class="grid grid-cols-2 gap-8 mb-12">
                <?php foreach ($home_about_features as $feature): ?>
                    <?php if (!empty($feature)): ?>
                        <div class="flex items-start">
                            <div
                                class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-primary shrink-0 mr-4">
                                <i class="fa fa-check"></i>
                            </div>
                            <span class="text-slate-800 font-bold"><?= htmlspecialchars($feature) ?></span>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <a href="<?= htmlspecialchars($home_about_button_url) ?>" class="inline-flex items-center text-primary font-bold text-lg group">
                <?= htmlspecialchars($home_about_button_text) ?>
                <svg class="w-6 h-6 ml-2 transition-transform group-hover:translate-x-2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                    </path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="bg-slate-50 py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-16 space-y-4 md:space-y-0">
            <div>
                <div class="h-1 w-20 bg-primary mb-6"></div>
                <h2 class="text-5xl font-bold text-slate-900 heading-font tracking-tight">ข่าวประชาสัมพันธ์</h2>
                <p class="text-lg text-slate-500 mt-4 max-w-xl leading-relaxed">
                    เกาะติดข่าวสารกิจกรรมและประกาศสำคัญของโรงเรียนก่อนใคร</p>
            </div>
            <a href="<?= url('/news-all') ?>" class="group flex items-center text-primary font-bold text-lg">
                ดูข่าวทั้งหมด
                <span class="ml-2 transition-transform group-hover:translate-x-2">&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php foreach ($latest_news as $item): ?>
                <article
                    class="bg-white rounded-5xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-2xl transition-all duration-500 flex flex-col group">
                    <div class="h-64 overflow-hidden relative">
                        <?php if (!empty($item['featured_image'])): ?>
                            <img src="<?= $item['featured_image'] ?>"
                                class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="">
                        <?php else: ?>
                            <div
                                class="w-full h-full bg-primary flex items-center justify-center p-8 transition duration-700 group-hover:scale-110">
                                <span class="text-white text-xl font-bold text-center line-clamp-3 heading-font">
                                    <?= htmlspecialchars($item['title']) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        <div class="absolute top-4 left-4">
                            <span
                                class="px-3 py-1 bg-white/90 backdrop-blur text-[10px] font-bold text-primary uppercase tracking-widest rounded-full shadow-sm">NEWS</span>
                        </div>
                    </div>
                    <div class="p-10 flex-grow flex flex-col">
                        <div
                            class="flex items-center text-xs text-slate-400 mb-4 font-bold outfit tracking-wider uppercase">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2V12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <?= date('d M Y', strtotime($item['published_at'])) ?>
                        </div>
                        <h3
                            class="text-2xl font-bold text-slate-900 line-clamp-2 mb-4 leading-tight group-hover:text-primary transition-colors">
                            <a href="<?= url('/news-detail/' . $item['id']) ?>"><?= $item['title'] ?></a>
                        </h3>
                        <p class="text-slate-500 text-sm line-clamp-3 mb-8 leading-relaxed">
                            <?= strip_tags($item['content']) ?>
                        </p>
                        <div class="mt-auto pt-6 border-t border-slate-50">
                            <a href="<?= url('/news-detail/' . $item['id']) ?>"
                                class="text-slate-900 font-bold text-sm inline-flex items-center group/btn">
                                อ่านรายละเอียด
                                <svg class="w-4 h-4 ml-1 transition-transform group-hover/btn:translate-x-1" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Personnel Preview Section -->
<?php if (!empty($featured_personnel)): ?>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
        <div class="text-center mb-20">
            <div class="h-1 w-20 bg-primary mx-auto mb-6"></div>
            <h2 class="text-5xl font-extrabold text-slate-900 heading-font mb-4">ฝ่ายบริหาร</h2>
            <p class="text-slate-500 text-lg font-medium">คณะผู้บริหารและหัวหน้าฝ่ายงานโรงเรียนของเรา</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($featured_personnel as $p): ?>
                <div
                    class="group relative bg-white rounded-5xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 p-2">
                    <div class="aspect-[4/5] rounded-4xl overflow-hidden relative">
                        <img src="<?= $p['image_url'] ?: 'https://via.placeholder.com/400x500' ?>"
                            class="w-full h-full object-cover transition duration-700 group-hover:scale-110"
                            alt="<?= htmlspecialchars($p['name']) ?>">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-end p-8">
                            <div class="flex space-x-4 justify-center">
                                <a href="#"
                                    class="w-10 h-10 bg-white/20 backdrop-blur text-white rounded-full flex items-center justify-center hover:bg-primary transition"><i
                                        class="fa fa-envelope-o"></i></a>
                                <a href="#"
                                    class="w-10 h-10 bg-white/20 backdrop-blur text-white rounded-full flex items-center justify-center hover:bg-primary transition"><i
                                        class="fa fa-facebook"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 text-center">
                        <h4 class="text-xl font-extrabold text-slate-900 mb-1 group-hover:text-primary transition-colors">
                            <?= htmlspecialchars($p['name']) ?>
                        </h4>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                            <?= htmlspecialchars($p['position']) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-16 text-center">
            <a href="<?= url('/personnel-view') ?>"
                class="px-10 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition shadow-xl inline-flex items-center">
                ทำความรู้จักบุคลากรทั้งหมด
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </a>
        </div>
    </section>
<?php endif; ?>

<!-- Gallery Preview Section -->
<?php if (!empty($latest_albums)): ?>
    <section class="bg-slate-950 py-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 space-y-4 md:space-y-0">
                <div class="text-white">
                    <div class="h-1 w-20 bg-primary mb-6"></div>
                    <h2 class="text-5xl font-extrabold heading-font tracking-tight mb-4">ภาพกิจกรรมล่าสุด</h2>
                    <p class="text-slate-400 text-lg max-w-xl font-medium italic">
                        ประมวลภาพความประทับใจและกิจกรรมที่เกิดขึ้นในโรงเรียน</p>
                </div>
                <a href="<?= url('/gallery-view') ?>" class="group flex items-center text-primary font-bold text-lg">
                    ดูคลังภาพทั้งหมด
                    <span class="ml-2 transition-transform group-hover:translate-x-2">&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($latest_albums as $album): ?>
                    <a href="<?= url('/gallery-detail/' . $album['id']) ?>"
                        class="group relative aspect-square rounded-4xl overflow-hidden shadow-2xl">
                        <img src="<?= $album['cover_image'] ?: 'https://via.placeholder.com/600x600' ?>"
                            class="w-full h-full object-cover transition duration-700 group-hover:scale-110 brightness-75 group-hover:brightness-50"
                            alt="<?= $album['title'] ?>">
                        <div class="absolute inset-0 p-8 flex flex-col justify-end">
                            <h4
                                class="text-white font-extrabold text-xl leading-tight opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500">
                                <?= $album['title'] ?>
                            </h4>
                            <span
                                class="text-primary text-xs font-bold uppercase tracking-widest mt-2 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-500"
                                style="transition-delay: 0.1s">
                                ดูอัลบั้มภาพ &rarr;
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<!-- Journals Section -->
<?php if (!empty($journals)): ?>
    <section class="bg-white py-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 space-y-4 md:space-y-0">
                <div>
                    <div class="h-1 w-20 bg-primary mb-6"></div>
                    <h2 class="text-5xl font-extrabold text-slate-900 heading-font tracking-tight">วารสารประชาสัมพันธ์</h2>
                    <p class="text-lg text-slate-500 mt-4 max-w-xl leading-relaxed">
                        ติดตามวารสารและข้อมูลข่าวสารล่าสุดผ่านสื่อสิ่งพิมพ์ดิจิทัลของโรงเรียน</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php foreach ($journals as $item): ?>
                    <div class="group bg-white rounded-5xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 flex flex-col">
                        <div class="aspect-[3/4] overflow-hidden relative">
                            <img src="<?= $item['image_url'] ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="<?= htmlspecialchars($item['title']) ?>">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <a href="<?= $item['image_url'] ?>" target="_blank" class="px-6 py-3 bg-white text-primary font-bold rounded-2xl shadow-xl hover:bg-blue-50 transition-all hover:scale-105 text-sm">
                                    ดูรูปขนาดเต็ม
                                </a>
                            </div>
                        </div>
                        <div class="p-8 text-center">
                            <h4 class="text-lg font-bold text-slate-800 line-clamp-1 group-hover:text-primary transition-colors">
                                <?= htmlspecialchars($item['title']) ?>
                            </h4>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Academic Calendar Section -->
<section class="py-32 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <div class="h-1 w-20 bg-primary mx-auto mb-6"></div>
            <h2 class="text-5xl font-extrabold text-slate-900 heading-font mb-4">ปฏิทินวิชาการ</h2>
            <p class="text-slate-500 text-lg font-medium">ติดตามกิจกรรมและกำหนดการสำคัญต่างๆ ของโรงเรียน</p>
        </div>

        <div class="bg-white p-8 md:p-12 rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100">
            <div id="calendar"></div>
        </div>
    </div>
</section>

<!-- Calendar Event Modal -->
<div id="eventModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEventModal()"></div>
    <div class="relative bg-white w-full max-w-xl rounded-[3rem] shadow-2xl overflow-hidden animate-fade-in-up">
        <div id="modalHeader" class="h-4 w-full"></div>
        <div class="p-10">
            <div class="flex justify-between items-start mb-6">
                <h3 id="modalTitle" class="text-3xl font-extrabold text-slate-900 heading-font leading-tight"></h3>
                <button onclick="closeEventModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="space-y-6">
                <div class="flex items-center text-slate-600">
                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-primary mr-4">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">วันที่และเวลา</p>
                        <p id="modalDate" class="font-bold text-slate-800"></p>
                    </div>
                </div>

                <div id="modalResponsibleContainer" class="flex items-center text-slate-600">
                    <div class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-primary mr-4">
                        <i class="fa fa-user"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ผู้รับผิดชอบ</p>
                        <p id="modalResponsible" class="font-bold text-slate-800"></p>
                    </div>
                </div>

                <div id="modalDescContainer" class="bg-slate-50 p-6 rounded-2xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">รายละเอียด</p>
                    <p id="modalDescription" class="text-slate-600 leading-relaxed"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'th',
            themeSystem: 'standard',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                today: 'วันนี้',
                month: 'เดือน',
                week: 'สัปดาห์',
                day: 'วัน',
                list: 'รายการ'
            },
            events: '<?= url('/api/calendar/events') ?>',
            eventClick: function(info) {
                const event = info.event;
                const props = event.extendedProps;
                
                document.getElementById('modalTitle').innerText = event.title;
                document.getElementById('modalHeader').style.backgroundColor = event.backgroundColor;
                
                // Format Date
                let dateStr = props.startDate;
                if (props.endDate && props.endDate !== props.startDate) {
                    dateStr += ' ถึง ' + props.endDate;
                }
                if (props.startTime) {
                    dateStr += ' (' + props.startTime.substring(0, 5);
                    if (props.endTime) dateStr += ' - ' + props.endTime.substring(0, 5);
                    dateStr += ' น.)';
                }
                document.getElementById('modalDate').innerText = dateStr;
                
                // Responsible
                if (props.responsible) {
                    document.getElementById('modalResponsible').innerText = props.responsible;
                    document.getElementById('modalResponsibleContainer').classList.remove('hidden');
                } else {
                    document.getElementById('modalResponsibleContainer').classList.add('hidden');
                }
                
                // Description
                if (props.description) {
                    document.getElementById('modalDescription').innerText = props.description;
                    document.getElementById('modalDescContainer').classList.remove('hidden');
                } else {
                    document.getElementById('modalDescContainer').classList.add('hidden');
                }
                
                document.getElementById('eventModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        });
        calendar.render();
    });

    function closeEventModal() {
        document.getElementById('eventModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>

<style>
    :root {
        --fc-button-bg-color: #f8fafc;
        --fc-button-text-color: #64748b;
        --fc-button-border-color: #e2e8f0;
        --fc-button-hover-bg-color: #f1f5f9;
        --fc-button-hover-border-color: #cbd5e1;
        --fc-button-active-bg-color: #e2e8f0;
        --fc-button-active-border-color: #cbd5e1;
        --fc-today-bg-color: #eff6ff;
        --fc-border-color: #f1f5f9;
    }
    .fc { font-family: 'Sarabun', sans-serif; }
    .fc .fc-toolbar-title { font-family: 'K2D', sans-serif; font-weight: 800; color: #0f172a; font-size: 1.5rem; }
    .fc .fc-button { font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem; padding: 0.75rem 1.25rem; border-radius: 1rem !important; transition: all 0.3s; }
    .fc .fc-button-primary:not(:disabled).fc-button-active, .fc .fc-button-primary:not(:disabled):active { background-color: var(--tw-color-primary, #1d4ed8) !important; color: white !important; border-color: transparent !important; }
    .fc .fc-daygrid-event { border-radius: 0.5rem; padding: 0.25rem 0.5rem; font-weight: 600; font-size: 0.85rem; border: none; margin-top: 2px; }
    .fc .fc-daygrid-day.fc-day-today { background-color: var(--fc-today-bg-color); }
    .fc .fc-col-header-cell { padding: 1rem 0; font-weight: 700; color: #94a3b8; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.1em; }
    .fc-theme-standard td, .fc-theme-standard th { border: 1px solid #f8fafc; }
    .fc .fc-list-event:hover td { background-color: #f8fafc !important; }
</style>

<!-- Dynamic Custom Content Blocks -->
<?php if (!empty($home_custom_content)): ?>
    <?php foreach ($home_custom_content as $block): ?>
        <section class="py-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <?php if ($block['type'] === 'text'): ?>
                    <div class="text-center max-w-4xl mx-auto">
                        <div class="h-1.5 w-24 bg-primary mx-auto mb-10 rounded-full"></div>
                        <h2 class="text-5xl md:text-6xl font-black text-slate-900 heading-font mb-10 leading-tight">
                            <?= nl2br(htmlspecialchars($block['title'])) ?>
                        </h2>
                        <div class="text-xl text-slate-500 leading-relaxed font-medium">
                            <?= nl2br(htmlspecialchars($block['content'])) ?>
                        </div>
                    </div>

                <?php elseif ($block['type'] === 'image'): ?>
                    <div class="text-center">
                        <?php if(!empty($block['title'])): ?>
                            <h2 class="text-4xl font-black text-slate-900 heading-font mb-10 leading-tight"><?= htmlspecialchars($block['title']) ?></h2>
                        <?php endif; ?>
                        <div class="rounded-[3rem] overflow-hidden shadow-2xl inline-block max-w-full">
                            <img src="<?= $block['image'] ?>" class="w-full max-h-[80vh] object-contain bg-slate-50" alt="<?= htmlspecialchars($block['title'] ?? '') ?>">
                        </div>
                    </div>

                <?php elseif ($block['type'] === 'carousel'): ?>
                    <div class="space-y-10">
                        <?php if(!empty($block['title'])): ?>
                            <h2 class="text-4xl font-black text-slate-900 heading-font text-center leading-tight"><?= htmlspecialchars($block['title']) ?></h2>
                        <?php endif; ?>
                        <div class="swiper blockCarousel rounded-[3rem] overflow-hidden shadow-2xl aspect-video md:aspect-[21/9]">
                            <div class="swiper-wrapper">
                                <?php if(!empty($block['images'])): ?>
                                    <?php foreach ($block['images'] as $imgUrl): ?>
                                        <div class="swiper-slide">
                                            <img src="<?= $imgUrl ?>" class="w-full h-full object-cover">
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next !text-white !opacity-50 hover:!opacity-100 transition-opacity"></div>
                            <div class="swiper-button-prev !text-white !opacity-50 hover:!opacity-100 transition-opacity"></div>
                        </div>
                    </div>

                <?php elseif ($block['type'] === 'image_text'): ?>
                    <div class="flex flex-col lg:flex-row items-center gap-20">
                        <div class="lg:w-1/2 relative group">
                            <div class="absolute -inset-4 bg-primary/5 rounded-[4rem] blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-1000"></div>
                            <div class="relative rounded-[3rem] overflow-hidden shadow-2xl transition-transform duration-700 group-hover:scale-[1.02]">
                                <img src="<?= $block['image'] ?>" class="w-full h-full object-cover aspect-[4/3]" alt="<?= htmlspecialchars($block['title']) ?>">
                            </div>
                        </div>
                        <div class="lg:w-1/2">
                            <div class="h-1.5 w-20 bg-primary mb-10 rounded-full"></div>
                            <h2 class="text-5xl font-black text-slate-900 heading-font mb-8 leading-tight">
                                <?= htmlspecialchars($block['title']) ?>
                            </h2>
                            <p class="text-xl text-slate-600 leading-relaxed mb-10 font-medium">
                                <?= nl2br(htmlspecialchars($block['content'])) ?>
                            </p>
                        </div>
                    </div>

                <?php elseif ($block['type'] === 'cta'): ?>
                    <div class="bg-primary rounded-[4rem] p-16 md:p-24 text-center relative overflow-hidden shadow-2xl shadow-primary/20">
                        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                            <div class="absolute top-10 left-10 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
                        </div>
                        <div class="relative z-10">
                            <h2 class="text-4xl md:text-6xl font-black text-white heading-font mb-8 leading-tight">
                                <?= htmlspecialchars($block['title']) ?>
                            </h2>
                            <p class="text-xl text-white/80 max-w-2xl mx-auto mb-12 font-medium">
                                <?= htmlspecialchars($block['content']) ?>
                            </p>
                            <?php if (!empty($block['button_text'])): ?>
                                <a href="<?= htmlspecialchars($block['button_url']) ?>" class="inline-flex items-center px-12 py-5 bg-white text-primary font-black rounded-3xl hover:bg-blue-50 transition-all hover:scale-105 shadow-2xl shadow-black/10 text-xl tracking-tight">
                                    <?= htmlspecialchars($block['button_text']) ?>
                                    <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php elseif ($block['type'] === 'embed'): ?>
                    <div class="space-y-10">
                        <?php if(!empty($block['title'])): ?>
                            <h2 class="text-4xl font-black text-slate-900 heading-font text-center leading-tight"><?= htmlspecialchars($block['title']) ?></h2>
                        <?php endif; ?>
                        <div class="rounded-[3rem] overflow-hidden shadow-2xl bg-white">
                            <div style="height: <?= $block['height'] ?? '500' ?>px">
                                <?= $block['embed_code'] ?>
                            </div>
                        </div>
                    </div>

                <?php elseif ($block['type'] === 'grid'): ?>
                    <div class="space-y-12">
                        <?php if(!empty($block['title'])): ?>
                            <h2 class="text-4xl md:text-5xl font-black text-slate-900 heading-font text-center leading-tight"><?= htmlspecialchars($block['title']) ?></h2>
                        <?php endif; ?>
                        <?php $cols = $block['cols'] ?? 2; ?>
                        <div class="grid grid-cols-1 md:grid-cols-<?= $cols ?> lg:grid-cols-<?= $cols ?> gap-8">
                            <?php foreach ($block['items'] as $item): ?>
                                <?php if (!empty($item['title'])): ?>
                                    <div class="bg-white p-10 rounded-[3rem] shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2 border border-slate-50 group">
                                        <div class="w-16 h-16 bg-primary/5 rounded-2xl flex items-center justify-center text-primary mb-8 group-hover:bg-primary group-hover:text-white transition-colors duration-500">
                                            <i class="fa <?= htmlspecialchars($item['icon'] ?: 'fa-star') ?> text-2xl"></i>
                                        </div>
                                        <h3 class="text-2xl font-black text-slate-900 mb-4 heading-font"><?= htmlspecialchars($item['title']) ?></h3>
                                        <p class="text-slate-600 leading-relaxed mb-8 font-medium"><?= nl2br(htmlspecialchars($item['content'])) ?></p>
                                        <?php if (!empty($item['url'])): ?>
                                            <a href="<?= htmlspecialchars($item['url']) ?>" class="inline-flex items-center text-primary font-bold hover:underline">
                                                อ่านเพิ่มเติม
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endforeach; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Dynamic Carousel Blocks (Handle multiple)
            document.querySelectorAll('.blockCarousel').forEach(el => {
                new Swiper(el, {
                    loop: true,
                    autoplay: { delay: 4000 },
                    pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
                    navigation: { 
                        nextEl: el.querySelector('.swiper-button-next'), 
                        prevEl: el.querySelector('.swiper-button-prev') 
                    },
                });
            });
        });
    </script>
<?php endif; ?>