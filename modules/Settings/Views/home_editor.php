<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8">
        <form action="/settings/update-home" method="POST" enctype="multipart/form-data" class="space-y-8">
            <?= \Core\Security::csrf_field() ?>
            <!-- Hero Section Settings (Cover & Text) -->
            <div class="space-y-8 bg-slate-50/50 p-8 rounded-[2.5rem] border border-slate-100">
                <div class="flex items-center justify-between pb-6 border-b border-slate-100">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-indigo-600 text-white rounded-2xl shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 heading-font">การตั้งค่าส่วนหัว (Hero Section)</h3>
                            <p class="text-sm text-slate-400">เลือกรูปแบบหน้าปกและข้อความต้อนรับ</p>
                        </div>
                    </div>

                    <div class="flex bg-white p-1.5 rounded-2xl border border-slate-200 shadow-sm">
                        <label class="relative flex items-center cursor-pointer group">
                            <input type="radio" name="home_header_mode" value="single" class="sr-only peer" <?= ($settings['home_header_mode'] ?? 'single') === 'single' ? 'checked' : '' ?> onchange="toggleHeaderMode('single')">
                            <span class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white transition-all">ภาพนิ่ง</span>
                        </label>
                        <label class="relative flex items-center cursor-pointer group">
                            <input type="radio" name="home_header_mode" value="carousel" class="sr-only peer" <?= ($settings['home_header_mode'] ?? 'single') === 'carousel' ? 'checked' : '' ?> onchange="toggleHeaderMode('carousel')">
                            <span class="px-6 py-2.5 rounded-xl text-sm font-bold text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white transition-all">Carousel</span>
                        </label>
                    </div>
                </div>

                <!-- Single Image Mode Settings -->
                <div id="header-single-settings" class="<?= ($settings['home_header_mode'] ?? 'single') === 'carousel' ? 'hidden' : '' ?> space-y-8 animate-fadeIn">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="md:col-span-1">
                            <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">ภาพหน้าปกปัจจุบัน</label>
                            <div class="relative group aspect-video bg-white rounded-[2rem] overflow-hidden border-2 border-dashed border-slate-200 shadow-sm">
                                <?php if (!empty($settings['home_cover_image'])): ?>
                                    <img src="<?= $settings['home_cover_image'] ?>" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-sm">
                                        <button type="button" onclick="confirmDeleteCover()" class="bg-red-500 text-white p-4 rounded-2xl hover:bg-red-600 shadow-2xl transition-transform hover:scale-110">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <div class="flex flex-col items-center justify-center h-full text-slate-300">
                                        <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-xs font-bold uppercase tracking-widest">ยังไม่ได้เลือกภาพ</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="hidden" name="delete_cover" id="delete_cover_input" value="0">
                        </div>
                        <div class="md:col-span-2 space-y-6">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">อัปโหลดภาพปกใหม่</label>
                                <input type="file" name="home_cover_image" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-6 file:py-3 file:px-8 file:rounded-2xl file:border-0 file:text-sm file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer">
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">หัวข้อหลัก (Title)</label>
                                <input type="text" name="home_hero_title" value="<?= htmlspecialchars($settings['home_hero_title'] ?? '') ?>" class="w-full bg-white border-2 border-slate-100 focus:border-indigo-500 focus:ring-0 rounded-2xl p-4 font-bold text-lg">
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">คำอธิบาย (Subtitle)</label>
                                <textarea name="home_hero_subtitle" rows="2" class="w-full bg-white border-2 border-slate-100 focus:border-indigo-500 focus:ring-0 rounded-2xl p-4 text-sm"><?= htmlspecialchars($settings['home_hero_subtitle'] ?? '') ?></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">ข้อความปุ่ม</label>
                                    <input type="text" name="home_hero_button_text" value="<?= htmlspecialchars($settings['home_hero_button_text'] ?? '') ?>" class="w-full bg-white border-2 border-slate-100 focus:border-indigo-500 focus:ring-0 rounded-2xl p-4 font-bold">
                                </div>
                                <div>
                                    <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">ลิงก์ปุ่ม</label>
                                    <input type="text" name="home_hero_button_url" value="<?= htmlspecialchars($settings['home_hero_button_url'] ?? '') ?>" class="w-full bg-white border-2 border-slate-100 focus:border-indigo-500 focus:ring-0 rounded-2xl p-4 font-bold">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carousel Mode Settings -->
                <div id="header-carousel-settings" class="<?= ($settings['home_header_mode'] ?? 'single') === 'single' ? 'hidden' : '' ?> space-y-6 animate-fadeIn">
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-bold text-slate-500 uppercase tracking-widest">รายการรูปภาพสไลด์</h4>
                        <button type="button" onclick="addCarouselSlide()" class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl transition-all text-sm font-bold shadow-lg shadow-indigo-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            <span>เพิ่มสไลด์ใหม่</span>
                        </button>
                    </div>

                    <div id="carousel-slides-container" class="space-y-4">
                        <?php if (!empty($settings['home_carousel_data'])): ?>
                            <?php foreach ($settings['home_carousel_data'] as $idx => $slide): ?>
                                <div class="carousel-slide-item bg-white p-6 rounded-[2rem] border-2 border-slate-100 shadow-sm relative group">
                                    <div class="flex flex-col md:flex-row gap-6">
                                        <div class="md:w-1/3">
                                            <div class="aspect-video rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 mb-3">
                                                <img src="<?= $slide['image'] ?>" class="w-full h-full object-cover">
                                            </div>
                                            <input type="hidden" name="carousel[<?= $idx ?>][existing_image]" value="<?= $slide['image'] ?>">
                                            <input type="file" name="carousel_image[<?= $idx ?>]" accept="image/*" class="text-xs text-slate-500 w-full file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-slate-100 file:text-slate-600">
                                        </div>
                                        <div class="md:w-2/3 space-y-4">
                                            <div class="flex justify-between items-start">
                                                <div class="flex-grow space-y-4">
                                                    <input type="text" name="carousel[<?= $idx ?>][title]" value="<?= htmlspecialchars($slide['title']) ?>" placeholder="หัวข้อสไลด์" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-xl p-3 font-bold">
                                                    <textarea name="carousel[<?= $idx ?>][subtitle]" placeholder="คำบรรยายสไลด์" rows="2" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-xl p-3 text-sm"><?= htmlspecialchars($slide['subtitle']) ?></textarea>
                                                </div>
                                                <button type="button" onclick="this.closest('.carousel-slide-item').remove()" class="ml-4 text-red-400 hover:text-red-600 transition-colors p-2">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- About Section Settings -->
            <div class="space-y-8 bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="flex items-center space-x-4 pb-6 border-b border-slate-100">
                    <div class="p-3 bg-emerald-600 text-white rounded-2xl shadow-lg shadow-emerald-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 heading-font">ข้อมูลเกี่ยวกับเรา (About Section)</h3>
                        <p class="text-sm text-slate-400">แนะนำโรงเรียนในส่วนเนื้อหาหลัก</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div>
                            <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">หัวข้อส่วนนี้</label>
                            <input type="text" name="home_about_title" value="<?= htmlspecialchars($settings['home_about_title'] ?? 'มุ่งมั่นสร้างสรรค์ อนาคตที่ยั่งยืนให้เยาวชน') ?>" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-emerald-500 rounded-2xl p-4 font-bold text-lg">
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">เนื้อหาบรรยาย</label>
                            <textarea name="home_about_content" rows="4" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-emerald-500 rounded-2xl p-4 text-sm leading-relaxed"><?= htmlspecialchars($settings['home_about_content'] ?? '') ?></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">ข้อความปุ่ม</label>
                                <input type="text" name="home_about_button_text" value="<?= htmlspecialchars($settings['home_about_button_text'] ?? 'อ่านประวัติโรงเรียนเพิ่มเติม') ?>" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-emerald-500 rounded-2xl p-4 text-sm font-bold">
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">ลิงก์ปุ่ม</label>
                                <input type="text" name="home_about_button_url" value="<?= htmlspecialchars($settings['home_about_button_url'] ?? '/about-us') ?>" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-emerald-500 rounded-2xl p-4 text-sm font-bold">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                         <div>
                            <label class="block mb-4 text-xs font-bold text-slate-400 uppercase tracking-widest">จุดเด่น / คุณสมบัติ (Features)</label>
                            <div class="grid grid-cols-1 gap-3">
                                <?php 
                                $aboutFeatures = $settings['home_about_features'] ?? ['เทคโนโลยีทันสมัย', 'สภาพแวดล้อมปลอดภัย', 'เน้นคุณธรรม จริยธรรม', 'กิจกรรมเสริมทักษะ'];
                                for ($i = 0; $i < 4; $i++): ?>
                                    <div class="flex items-center space-x-3 group">
                                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                                            <i class="fa fa-check"></i>
                                        </div>
                                        <input type="text" name="home_about_features[]" value="<?= htmlspecialchars($aboutFeatures[$i] ?? '') ?>" class="flex-grow bg-slate-50 border-none focus:ring-2 focus:ring-emerald-500 rounded-xl p-3 text-sm font-bold" placeholder="เพิ่มจุดเด่นที่นี่...">
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">รูปภาพประกอบ</label>
                            <div class="flex items-start space-x-4">
                                <div class="w-32 aspect-square rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                    <img src="<?= $settings['home_about_image'] ?: 'https://via.placeholder.com/300' ?>" class="w-full h-full object-cover" id="about-img-preview">
                                </div>
                                <div class="flex-grow">
                                    <input type="file" name="home_about_image" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-600 hover:file:bg-emerald-100 transition-all cursor-pointer">
                                    <p class="text-[10px] text-slate-400 mt-2 italic">* แนะนำรูปขนาด 800x1000px หรืออัตราส่วนแนวตั้ง</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Blocks Section -->
            <div class="space-y-6 pt-12 border-t-4 border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 bg-indigo-600 text-white rounded-2xl shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 heading-font">ส่วนเนื้อหาเพิ่มเติม (Dynamic Sections)</h3>
                            <p class="text-sm text-slate-400">เพิ่ม ลบ และจัดลำดับเนื้อหาหน้าแรก</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="addBlock('text')" class="inline-flex items-center space-x-2 bg-white border border-gray-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl transition-all text-xs font-bold shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                            <span>เพิ่มข้อความ</span>
                        </button>
                        <button type="button" onclick="addBlock('image')" class="inline-flex items-center space-x-2 bg-white border border-gray-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl transition-all text-xs font-bold shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>เพิ่มรูปภาพ</span>
                        </button>
                        <button type="button" onclick="addBlock('carousel')" class="inline-flex items-center space-x-2 bg-white border border-gray-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl transition-all text-xs font-bold shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            <span>เพิ่มรูปสไลด์</span>
                        </button>
                        <button type="button" onclick="addBlock('image_text')" class="inline-flex items-center space-x-2 bg-white border border-gray-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl transition-all text-xs font-bold shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>ภาพคู่ข้อความ</span>
                        </button>
                        <button type="button" onclick="addBlock('grid')" class="inline-flex items-center space-x-2 bg-white border border-gray-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl transition-all text-xs font-bold shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            <span>คอลัมน์/ตาราง</span>
                        </button>
                        <button type="button" onclick="addBlock('embed')" class="inline-flex items-center space-x-2 bg-white border border-gray-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl transition-all text-xs font-bold shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                            <span>Embed / HTML</span>
                        </button>
                        <button type="button" onclick="addBlock('cta')" class="inline-flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl transition-all text-xs font-bold shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>ปุ่ม CTA</span>
                        </button>
                    </div>
                </div>

                <div id="blocks-container" class="space-y-6 min-h-[100px] py-4">
                    <?php if (!empty($settings['home_custom_content'])): ?>
                        <?php foreach ($settings['home_custom_content'] as $index => $block): ?>
                            <div class="block-item bg-white p-8 rounded-3xl border-2 border-slate-100 shadow-sm relative group cursor-move hover:border-indigo-300 transition-all" data-id="<?= $index ?>">
                                <div class="absolute -left-3 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-indigo-500 text-white p-1 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                                </div>
                                <input type="hidden" name="custom_blocks[<?= $index ?>][type]" value="<?= $block['type'] ?>">
                                
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center space-x-2">
                                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-full"><?= $block['type'] ?> Block</span>
                                        <h4 class="font-bold text-slate-800">ส่วนที่ <?= $index + 1 ?></h4>
                                    </div>
                                    <button type="button" onclick="this.closest('.block-item').remove()" class="text-red-400 hover:text-red-600 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="<?= in_array($block['type'], ['text', 'cta', 'image', 'carousel', 'embed', 'grid']) ? 'md:col-span-2' : '' ?>">
                                        <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">หัวข้อ (Title)</label>
                                        <input type="text" name="custom_blocks[<?= $index ?>][title]" value="<?= htmlspecialchars($block['title'] ?? '') ?>" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl p-4 font-bold text-lg">
                                        
                                        <?php if (!in_array($block['type'], ['image', 'carousel', 'grid', 'embed'])): ?>
                                        <label class="block mt-4 mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">เนื้อหา (Content / Subtitle)</label>
                                        <textarea name="custom_blocks[<?= $index ?>][content]" rows="4" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl p-4 text-sm"><?= htmlspecialchars($block['content'] ?? '') ?></textarea>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($block['type'] == 'image_text' || $block['type'] == 'image'): ?>
                                    <div class="<?= $block['type'] == 'image' ? 'md:col-span-2' : '' ?>">
                                        <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">รูปภาพ</label>
                                        <div class="aspect-video rounded-2xl overflow-hidden bg-slate-100 mb-3 border border-slate-200">
                                            <img src="<?= $block['image'] ?>" class="w-full h-full object-cover">
                                        </div>
                                        <input type="hidden" name="custom_blocks[<?= $index ?>][existing_image]" value="<?= $block['image'] ?>">
                                        <input type="file" name="block_image[<?= $index ?>]" accept="image/*" class="text-xs text-slate-500 w-full file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:text-indigo-600">
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($block['type'] == 'carousel'): ?>
                                    <div class="md:col-span-2">
                                        <label class="block mb-4 text-xs font-bold text-slate-400 uppercase tracking-widest">รูปภาพสไลด์ (Carousel)</label>
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                            <?php if (!empty($block['images'])): ?>
                                                <?php foreach ($block['images'] as $imgUrl): ?>
                                                <div class="relative group aspect-video rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                                                    <img src="<?= $imgUrl ?>" class="w-full h-full object-cover">
                                                    <input type="hidden" name="custom_blocks[<?= $index ?>][existing_images][]" value="<?= $imgUrl ?>">
                                                    <button type="button" onclick="this.parentElement.remove()" class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" name="block_carousel_images[<?= $index ?>][]" multiple accept="image/*" class="text-xs text-slate-500 w-full file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-600 file:text-white">
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($block['type'] == 'cta'): ?>
                                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                                        <div>
                                            <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">ข้อความปุ่ม</label>
                                            <input type="text" name="custom_blocks[<?= $index ?>][button_text]" value="<?= htmlspecialchars($block['button_text'] ?? '') ?>" class="w-full bg-slate-50 border-none rounded-2xl p-4">
                                        </div>
                                        <div>
                                            <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">ลิงก์ปุ่ม</label>
                                            <input type="text" name="custom_blocks[<?= $index ?>][button_url]" value="<?= htmlspecialchars($block['button_url'] ?? '') ?>" class="w-full bg-slate-50 border-none rounded-2xl p-4">
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($block['type'] == 'embed'): ?>
                                    <div class="md:col-span-2 space-y-4">
                                        <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Embed Code / iFrame / HTML</label>
                                        <textarea name="custom_blocks[<?= $index ?>][embed_code]" rows="6" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl p-4 text-xs font-mono"><?= htmlspecialchars($block['embed_code'] ?? '') ?></textarea>
                                        <div class="flex items-center space-x-4">
                                            <label class="text-xs font-bold text-slate-400">ความสูง (px):</label>
                                            <input type="number" name="custom_blocks[<?= $index ?>][height]" value="<?= $block['height'] ?? '500' ?>" class="bg-slate-50 border-none rounded-xl p-2 text-sm w-24">
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($block['type'] == 'grid'): ?>
                                    <div class="md:col-span-2 space-y-6">
                                        <div class="flex justify-between items-center mb-4">
                                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">รายการคอลัมน์ (Grid Items)</label>
                                            <div class="flex items-center space-x-2">
                                                <label class="text-xs font-bold text-slate-400">จำนวนคอลัมน์:</label>
                                                <select name="custom_blocks[<?= $index ?>][cols]" class="bg-slate-50 border-none rounded-xl p-2 text-xs">
                                                    <option value="2" <?= ($block['cols'] ?? 2) == 2 ? 'selected' : '' ?>>2 คอลัมน์</option>
                                                    <option value="3" <?= ($block['cols'] ?? 2) == 3 ? 'selected' : '' ?>>3 คอลัมน์</option>
                                                    <option value="4" <?= ($block['cols'] ?? 2) == 4 ? 'selected' : '' ?>>4 คอลัมน์</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <?php for($i=0; $i<4; $i++): 
                                                $item = $block['items'][$i] ?? [];
                                            ?>
                                            <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 space-y-4">
                                                <h5 class="text-xs font-black text-indigo-300 uppercase tracking-widest italic">Item <?= $i+1 ?></h5>
                                                <input type="text" name="custom_blocks[<?= $index ?>][items][<?= $i ?>][title]" value="<?= htmlspecialchars($item['title'] ?? '') ?>" placeholder="หัวข้อ" class="w-full bg-white border-none rounded-xl p-3 text-sm font-bold">
                                                <textarea name="custom_blocks[<?= $index ?>][items][<?= $i ?>][content]" rows="2" placeholder="รายละเอียด" class="w-full bg-white border-none rounded-xl p-3 text-xs"><?= htmlspecialchars($item['content'] ?? '') ?></textarea>
                                                <div class="flex items-center space-x-2">
                                                    <input type="text" name="custom_blocks[<?= $index ?>][items][<?= $i ?>][icon]" value="<?= htmlspecialchars($item['icon'] ?? '') ?>" placeholder="FontAwesome Icon (เช่น fa-star)" class="flex-grow bg-white border-none rounded-xl p-3 text-xs">
                                                    <input type="text" name="custom_blocks[<?= $index ?>][items][<?= $i ?>][url]" value="<?= htmlspecialchars($item['url'] ?? '') ?>" placeholder="Link" class="flex-grow bg-white border-none rounded-xl p-3 text-xs">
                                                </div>
                                            </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (empty($settings['home_custom_content'])): ?>
                <div id="no-blocks-msg" class="text-center py-20 bg-slate-50 rounded-[40px] border-4 border-dashed border-slate-100">
                    <h4 class="text-xl font-bold text-slate-400">ยังไม่มีเนื้อหาเพิ่มเติม</h4>
                    <p class="text-sm text-slate-300 mt-2">กดปุ่มด้านบนเพื่อเพิ่มส่วนเนื้อหาใหม่</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="pt-12 border-t border-gray-100 flex items-center justify-center">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black py-5 px-16 rounded-[2rem] transition-all shadow-2xl text-xl">
                    บันทึกข้อมูลทั้งหมด
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let blockIndex = <?= !empty($settings['home_custom_content']) ? count($settings['home_custom_content']) : 0 ?>;
let carouselIndex = <?= !empty($settings['home_carousel_data']) ? count($settings['home_carousel_data']) : 0 ?>;

const el = document.getElementById('blocks-container');
if (el) {
    Sortable.create(el, {
        animation: 150,
        handle: '.block-item',
        ghostClass: 'bg-indigo-50'
    });
}

function toggleHeaderMode(mode) {
    const singleSet = document.getElementById('header-single-settings');
    const carouselSet = document.getElementById('header-carousel-settings');
    
    if (mode === 'single') {
        singleSet.classList.remove('hidden');
        carouselSet.classList.add('hidden');
    } else {
        singleSet.classList.add('hidden');
        carouselSet.classList.remove('hidden');
    }
}

function addCarouselSlide() {
    const container = document.getElementById('carousel-slides-container');
    const html = `
        <div class="carousel-slide-item bg-white p-6 rounded-[2rem] border-2 border-slate-100 shadow-sm relative group animate-fadeIn">
            <div class="flex flex-col md:flex-row gap-6">
                <div class="md:w-1/3">
                    <div class="aspect-video rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 mb-3">
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                    <input type="file" name="carousel_image[${carouselIndex}]" accept="image/*" class="text-xs text-slate-500 w-full file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:text-indigo-600" required>
                </div>
                <div class="md:w-2/3 space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-grow space-y-4">
                            <input type="text" name="carousel[${carouselIndex}][title]" placeholder="หัวข้อสไลด์" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-xl p-3 font-bold">
                            <textarea name="carousel[${carouselIndex}][subtitle]" placeholder="คำบรรยายสไลด์" rows="2" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-xl p-3 text-sm"></textarea>
                        </div>
                        <button type="button" onclick="this.closest('.carousel-slide-item').remove()" class="ml-4 text-red-400 hover:text-red-600 transition-colors p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
    carouselIndex++;
}

function addBlock(type) {
    const container = document.getElementById('blocks-container');
    const noMsg = document.getElementById('no-blocks-msg');
    if (noMsg) noMsg.style.display = 'none';

    let contentHtml = '';
    
    if (type === 'text' || type === 'cta' || type === 'image_text' || type === 'image') {
        contentHtml += `
            <div class="${(type === 'text' || type === 'cta' || type === 'image') ? 'md:col-span-2' : ''}">
                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">หัวข้อ (Title)</label>
                <input type="text" name="custom_blocks[${blockIndex}][title]" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl p-4 font-bold text-lg" placeholder="ใส่หัวข้อที่นี่...">
                
                ${(type !== 'image') ? `
                <label class="block mt-4 mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">เนื้อหา (Content)</label>
                <textarea name="custom_blocks[${blockIndex}][content]" rows="4" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl p-4 text-sm" placeholder="ใส่รายละเอียดที่นี่..."></textarea>
                ` : ''}
            </div>
        `;
    }

    if (type === 'image_text' || type === 'image') {
        contentHtml += `
            <div class="${type === 'image' ? 'md:col-span-2' : ''}">
                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">รูปภาพ</label>
                <input type="file" name="block_image[${blockIndex}]" accept="image/*" class="text-xs text-slate-500 w-full file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-50 file:text-indigo-600" required>
            </div>
        `;
    }

    if (type === 'carousel') {
        contentHtml = `
            <div class="md:col-span-2">
                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">หัวข้อ (Title)</label>
                <input type="text" name="custom_blocks[${blockIndex}][title]" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl p-4 font-bold text-lg mb-6">
                <div class="bg-indigo-50 p-6 rounded-3xl border-2 border-dashed border-indigo-200 text-center">
                    <label class="block mb-2 text-sm font-bold text-indigo-700">เลือกรูปภาพสำหรับสไลด์ (เลือกได้หลายรูป)</label>
                    <input type="file" name="block_carousel_images[${blockIndex}][]" multiple accept="image/*" class="text-xs text-slate-500 w-full max-w-xs mx-auto file:mr-2 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-600 file:text-white" required>
                </div>
            </div>
        `;
    }

    if (type === 'cta') {
        contentHtml += `
            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">ข้อความปุ่ม</label>
                    <input type="text" name="custom_blocks[${blockIndex}][button_text]" class="w-full bg-slate-50 border-none rounded-2xl p-4" placeholder="เช่น คลิกที่นี่">
                </div>
                <div>
                    <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">ลิงก์ปุ่ม</label>
                    <input type="text" name="custom_blocks[${blockIndex}][button_url]" class="w-full bg-slate-50 border-none rounded-2xl p-4" placeholder="เช่น /news-all">
                </div>
            </div>
        `;
    }

    if (type === 'embed') {
        contentHtml += `
            <div class="md:col-span-2 space-y-4">
                <label class="block mb-2 text-xs font-bold text-slate-400 uppercase tracking-widest">Embed Code / iFrame / HTML</label>
                <textarea name="custom_blocks[${blockIndex}][embed_code]" rows="6" class="w-full bg-slate-50 border-none focus:ring-2 focus:ring-indigo-500 rounded-2xl p-4 text-xs font-mono" placeholder="วางโค้ด iFrame หรือ HTML ที่นี่..."></textarea>
                <div class="flex items-center space-x-4">
                    <label class="text-xs font-bold text-slate-400">ความสูง (px):</label>
                    <input type="number" name="custom_blocks[${blockIndex}][height]" value="500" class="bg-slate-50 border-none rounded-xl p-2 text-sm w-24">
                </div>
            </div>
        `;
    }

    if (type === 'grid') {
        let itemsHtml = '';
        for(let i=0; i<4; i++) {
            itemsHtml += `
                <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 space-y-4">
                    <h5 class="text-xs font-black text-indigo-300 uppercase tracking-widest italic">Item ${i+1}</h5>
                    <input type="text" name="custom_blocks[${blockIndex}][items][${i}][title]" placeholder="หัวข้อ" class="w-full bg-white border-none rounded-xl p-3 text-sm font-bold">
                    <textarea name="custom_blocks[${blockIndex}][items][${i}][content]" rows="2" placeholder="รายละเอียด" class="w-full bg-white border-none rounded-xl p-3 text-xs"></textarea>
                    <div class="flex items-center space-x-2">
                        <input type="text" name="custom_blocks[${blockIndex}][items][${i}][icon]" placeholder="Icon (fa-star)" class="flex-grow bg-white border-none rounded-xl p-3 text-xs">
                        <input type="text" name="custom_blocks[${blockIndex}][items][${i}][url]" placeholder="Link" class="flex-grow bg-white border-none rounded-xl p-3 text-xs">
                    </div>
                </div>
            `;
        }
        contentHtml += `
            <div class="md:col-span-2 space-y-6">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">รายการคอลัมน์ (Grid Items)</label>
                    <div class="flex items-center space-x-2">
                        <label class="text-xs font-bold text-slate-400">จำนวนคอลัมน์:</label>
                        <select name="custom_blocks[${blockIndex}][cols]" class="bg-slate-50 border-none rounded-xl p-2 text-xs">
                            <option value="2">2 คอลัมน์</option>
                            <option value="3">3 คอลัมน์</option>
                            <option value="4">4 คอลัมน์</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    ${itemsHtml}
                </div>
            </div>
        `;
    }


    const html = `
        <div class="block-item bg-white p-8 rounded-3xl border-2 border-slate-100 shadow-sm relative group cursor-move hover:border-indigo-300 transition-all animate-fadeIn">
            <div class="absolute -left-3 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-indigo-500 text-white p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
            </div>
            <input type="hidden" name="custom_blocks[${blockIndex}][type]" value="${type}">
            <div class="flex items-center justify-between mb-6">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase rounded-full">${type} Block</span>
                <button type="button" onclick="this.closest('.block-item').remove()" class="text-red-400 hover:text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                ${contentHtml}
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
    blockIndex++;
}

function confirmDeleteCover() {
    if (confirm('คุณต้องการลบภาพหน้าปกใช่หรือไม่?')) {
        document.getElementById('delete_cover_input').value = '1';
        document.querySelector('form').submit();
    }
}
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.3s ease-out forwards;
}
</style>
