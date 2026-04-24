<!DOCTYPE html>
<html lang="th" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $siteName = \Core\Database::getSetting('site_name', 'School CMS Mix');
    $siteLogo = \Core\Database::getSetting('site_logo', '');
    $siteFavicon = \Core\Database::getSetting('site_favicon', '');
    $primaryColor = \Core\Database::getSetting('primary_color', '#1d4ed8');
    $currentPath = $_SERVER['REQUEST_URI'];

    $socials = [
        'facebook' => \Core\Database::getSetting('social_facebook', ''),
        'line' => \Core\Database::getSetting('social_line', ''),
        'youtube' => \Core\Database::getSetting('social_youtube', ''),
        'tiktok' => \Core\Database::getSetting('social_tiktok', ''),
        'x' => \Core\Database::getSetting('social_twitter', ''),
    ];
    ?>
    <title><?= $title ?? 'ยินดีต้อนรับ' ?> - <?= $siteName ?></title>
    <?php if ($siteFavicon): ?>
        <link rel="icon" type="image/x-icon" href="<?= $siteFavicon ?>">
    <?php endif; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '<?= $primaryColor ?>',
                    },
                    borderRadius: {
                        '4xl': '2rem',
                        '5xl': '3rem',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&family=Outfit:wght@400;600;700&family=K2D:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        :root {
            --primary-color:
                <?= $primaryColor ?>
            ;
        }

        body {
            font-family: 'Sarabun', sans-serif;
        }

        h1,
        h2,
        h3,
        .heading-font {
            font-family: 'K2D', sans-serif;
        }

        .outfit {
            font-family: 'Outfit', sans-serif;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .hero-gradient {
            background: radial-gradient(circle at top right,
                    <?= $primaryColor ?>
                    dd, transparent),
                radial-gradient(circle at bottom left,
                    <?= $primaryColor ?>
                    ,
                    <?= $primaryColor ?>
                    cc);
            background-color:
                <?= $primaryColor ?>
            ;
        }

        .btn-premium {
            background: linear-gradient(135deg,
                    <?= $primaryColor ?>
                    0%, #1e40af 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px
                <?= $primaryColor ?>
            ;
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .card-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>

<body class="bg-slate-50 flex flex-col min-h-screen text-slate-800">

    <!-- Navigation -->
    <nav class="glass-nav border-b border-white/20 sticky top-0 z-50 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="/" class="flex-shrink-0 flex items-center group">
                        <?php if ($siteLogo): ?>
                            <img src="<?= $siteLogo ?>" class="h-10 w-auto mr-3 transition-transform group-hover:scale-105"
                                alt="<?= $siteName ?>">
                        <?php endif; ?>
                        <span
                            class="text-2xl font-bold text-slate-900 heading-font tracking-tight"><?= $siteName ?></span>
                    </a>
                </div>
                <div class="hidden md:ml-6 md:flex md:items-center md:space-x-8">
                    <?php
                    $dynamicMenus = \Modules\Settings\Models\Menu::getAllActive();
                    foreach ($dynamicMenus as $m):
                        $hasChildren = !empty($m['children']);
                        $isActiveMenu = $currentPath === $m['url'];
                        ?>

                        <?php if (!$hasChildren): ?>
                            <a href="<?= $m['url'] ?>"
                                class="<?= $isActiveMenu ? 'text-primary' : 'text-slate-600' ?> hover:text-primary font-bold transition-colors flex items-center">
                                <?php if ($m['icon']): ?>
                                    <i class="fa <?= $m['icon'] ?> fa-fw mr-1.5"></i>
                                <?php endif; ?>
                                <?= $m['title'] ?>
                            </a>
                        <?php else: ?>
                            <div class="relative group">
                                <button
                                    class="<?= $isActiveMenu ? 'text-primary' : 'text-slate-600' ?> group-hover:text-primary font-bold transition-colors flex items-center py-4 focus:outline-none">
                                    <?php if ($m['icon']): ?>
                                        <i class="fa <?= $m['icon'] ?> fa-fw mr-1.5"></i>
                                    <?php endif; ?>
                                    <?= $m['title'] ?>
                                    <svg class="w-4 h-4 ml-1.5 transition-transform group-hover:rotate-180" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div
                                    class="absolute left-0 mt-0 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-left -translate-y-2 group-hover:translate-y-0 z-50">
                                    <div
                                        class="bg-white rounded-2xl shadow-xl border border-slate-100 py-3 mt-2 overflow-hidden">
                                        <?php foreach ($m['children'] as $child): ?>
                                            <a href="<?= $child['url'] ?>"
                                                class="block px-6 py-2.5 text-sm font-bold text-slate-600 hover:text-primary hover:bg-slate-50 transition-colors flex items-center">
                                                <?php if ($child['icon']): ?>
                                                    <i class="fa <?= $child['icon'] ?> fa-fw mr-3 opacity-50"></i>
                                                <?php endif; ?>
                                                <?= $child['title'] ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                    <a href="/auth"
                        class="btn-premium text-white px-6 py-2.5 rounded-full font-bold shadow-lg shadow-blue-500/20">เข้าสู่ระบบ</a>
                </div>
                <!-- Mobile button -->
                <div class="flex items-center md:hidden">
                    <button data-collapse-toggle="mobile-menu" type="button"
                        class="inline-flex items-center p-2 text-slate-500 rounded-lg hover:bg-slate-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-lg border-t border-slate-100">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <?php foreach ($dynamicMenus as $m):
                    $hasChildren = !empty($m['children']);
                    ?>
                    <div class="block">
                        <?php if (!$hasChildren): ?>
                            <a href="<?= $m['url'] ?>"
                                class="px-3 py-2 text-slate-700 font-bold hover:bg-slate-50 rounded-lg flex items-center">
                                <?php if ($m['icon']): ?>
                                    <i class="fa <?= $m['icon'] ?> fa-fw mr-3 text-slate-400"></i>
                                <?php endif; ?>
                                <?= $m['title'] ?>
                            </a>
                        <?php else: ?>
                            <button type="button"
                                class="w-full text-left px-3 py-2 text-slate-700 font-bold hover:bg-slate-50 rounded-lg flex items-center justify-between"
                                data-collapse-toggle="sub-menu-<?= $m['id'] ?>">
                                <span class="flex items-center">
                                    <?php if ($m['icon']): ?>
                                        <i class="fa <?= $m['icon'] ?> fa-fw mr-3 text-slate-400"></i>
                                    <?php endif; ?>
                                    <?= $m['title'] ?>
                                </span>
                                <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                            <div id="sub-menu-<?= $m['id'] ?>" class="hidden bg-slate-50/50 rounded-lg mt-1 space-y-1">
                                <?php foreach ($m['children'] as $child): ?>
                                    <a href="<?= $child['url'] ?>"
                                        class="block px-10 py-2 text-sm font-bold text-slate-600 hover:text-primary transition-colors">
                                        <?= $child['title'] ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <a href="/auth" class="block px-3 py-2 text-blue-600 font-bold">Admin</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow animate-fade-in">
        <?php if (isset($content))
            echo $content; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-white mt-20 relative overflow-hidden">
        <div
            class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-primary to-transparent opacity-50">
        </div>
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="md:col-span-2">
                    <div class="flex items-center mb-6">
                        <?php if ($siteLogo): ?>
                            <img src="<?= $siteLogo ?>" class="h-8 w-auto mr-3 brightness-0 invert" alt="<?= $siteName ?>">
                        <?php endif; ?>
                        <h3 class="text-2xl font-bold heading-font tracking-tight"><?= $siteName ?></h3>
                    </div>
                    <p class="text-slate-400 text-base max-w-md leading-relaxed mb-8">
                        <?= \Core\Database::getSetting('footer_description', 'ยกระดับการศึกษาด้วยเทคโนโลยีที่ทันสมัย ระบบบริหารจัดการเนื้อหาโรงเรียน (School CMS) ที่ออกแบบมาเพื่อความง่ายและประสิทธิภาพสูงสุด') ?>
                    </p>
                    <div class="flex space-x-3">
                        <!-- ... (Social Icons stay same) ... -->
                        <?php /* Social icons code below */ ?>
                        <?php if ($socials['facebook']): ?>
                            <a href="<?= $socials['facebook'] ?>" target="_blank"
                                class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center hover:bg-blue-600 transition-all border border-slate-800"
                                title="Facebook">
                                <i class="fa fa-facebook text-white"></i>
                            </a>
                        <?php endif; ?>

                        <?php if ($socials['line']): ?>
                            <a href="<?= $socials['line'] ?>" target="_blank"
                                class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center hover:bg-green-500 transition-all border border-slate-800"
                                title="Line">
                                <i class="fa fa-comment text-white"></i>
                            </a>
                        <?php endif; ?>

                        <?php if ($socials['youtube']): ?>
                            <a href="<?= $socials['youtube'] ?>" target="_blank"
                                class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center hover:bg-red-600 transition-all border border-slate-800"
                                title="YouTube">
                                <i class="fa fa-youtube-play text-white"></i>
                            </a>
                        <?php endif; ?>

                        <?php if ($socials['tiktok']): ?>
                            <a href="<?= $socials['tiktok'] ?>" target="_blank"
                                class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center hover:bg-black transition-all border border-slate-800"
                                title="TikTok">
                                <i class="fa fa-music text-white"></i>
                            </a>
                        <?php endif; ?>

                        <?php if ($socials['x']): ?>
                            <a href="<?= $socials['x'] ?>" target="_blank"
                                class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center hover:bg-black transition-all border border-slate-800"
                                title="X (Twitter)">
                                <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24">
                                    <path
                                        d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932 6.064-6.932zm-1.292 19.49h2.039L6.486 3.24H4.298l13.311 17.403z" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-widest text-slate-500 mb-6">เมนูหลัก</h4>
                    <ul class="text-slate-400 space-y-4 font-medium">
                        <?php foreach ($dynamicMenus as $m): ?>
                            <li><a href="<?= $m['url'] ?>"
                                    class="hover:text-primary transition-colors"><?= $m['title'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-widest text-slate-500 mb-6">ติดต่อหน่วยงาน</h4>
                    <ul class="text-slate-400 space-y-4 text-sm leading-relaxed">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-slate-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <?= \Core\Database::getSetting('school_address', '719 หมู่ 3 ต.หนองคู อ.ลำปลายมาศ จ.บุรีรัมย์ 31130') ?>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-slate-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <?= \Core\Database::getSetting('school_phone', '043-xxx-xxxx') ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div
                class="mt-16 pt-8 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center text-xs text-slate-500 space-y-4 md:space-y-0">
                <p><?= \Core\Database::getSetting('footer_copyright', '&copy; ' . date('Y') . ' ' . $siteName . '. All rights reserved.') ?>
                </p>
                <div class="flex space-x-6">
                    <a href="#" class="hover:text-slate-300">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-300">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        window.addEventListener('scroll', function () {
            const nav = document.querySelector('nav');
            if (window.scrollY > 20) {
                nav.classList.add('py-2', 'shadow-md');
                nav.classList.remove('py-0');
            } else {
                nav.classList.remove('py-2', 'shadow-md');
            }
        });
    </script>
</body>

</html>