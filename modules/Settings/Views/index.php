<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8">
        <form action="<?= url('/settings/update') ?>" method="POST" enctype="multipart/form-data" class="space-y-12">
            <?= \Core\Security::csrf_field() ?>
            <!-- Section 1: General Info -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    ข้อมูลพื้นฐานโรงเรียน
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900">ชื่อโรงเรียน (Site Name)</label>
                        <input type="text" name="site_name" value="<?= $settings['site_name'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 shadow-sm">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">ที่อยู่โรงเรียน</label>
                        <textarea name="school_address" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 shadow-sm"><?= $settings['school_address'] ?></textarea>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">เบอร์โทรศัพท์ติดต่อ</label>
                        <input type="text" name="school_phone" value="<?= $settings['school_phone'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 shadow-sm">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">จำนวนนักเรียน (Student Count)</label>
                        <input type="number" name="stat_student_count" value="<?= $settings['stat_student_count'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 shadow-sm">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">จำนวนห้องเรียน (Classroom Count)</label>
                        <input type="number" name="stat_classroom_count" value="<?= $settings['stat_classroom_count'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Section 2: School Identity -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    โลโก้และไอคอน (Logo & Favicon)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100 flex items-center space-x-6">
                        <div class="flex-shrink-0 w-24 h-24 bg-white rounded-2xl border border-gray-200 flex items-center justify-center p-2">
                            <?php if ($settings['site_logo']): ?>
                                <img src="<?= url($settings['site_logo']) ?>" class="max-w-full max-h-full object-contain">
                            <?php else: ?>
                                <span class="text-gray-300 text-xs">ไม่มีโลโก้</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow">
                            <label class="block mb-2 text-sm font-bold text-gray-700">อัพโหลดโลโก้โรงเรียน</label>
                            <input type="file" name="site_logo" class="block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none" accept="image/*">
                            <p class="mt-1 text-[10px] text-gray-500">แนะนำ: PNG หรือ SVG (Transparent)</p>
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100 flex items-center space-x-6">
                        <div class="flex-shrink-0 w-16 h-16 bg-white rounded-2xl border border-gray-200 flex items-center justify-center p-2">
                            <?php if ($settings['site_favicon']): ?>
                                <img src="<?= url($settings['site_favicon']) ?>" class="w-8 h-8 object-contain">
                            <?php else: ?>
                                <span class="text-gray-300 text-xs text-center leading-tight">ไม่มี<br>ไอคอน</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow">
                            <label class="block mb-2 text-sm font-bold text-gray-700">อัพโหลด Favicon</label>
                            <input type="file" name="site_favicon" class="block w-full text-xs text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none" accept="image/x-icon,image/png">
                            <p class="mt-1 text-[10px] text-gray-500">แนะนำ: .ico หรือ .png (32x32px)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Branding & Colors -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                    โทนสีของโรงเรียน (School Branding)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">สีหลัก (Primary Color)</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" name="primary_color" value="<?= $settings['primary_color'] ?>" class="w-12 h-12 rounded-lg cursor-pointer bg-white border border-gray-300 p-1">
                            <input type="text" value="<?= $settings['primary_color'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl flex-grow p-3" readonly>
                        </div>
                        <p class="mt-2 text-xs text-gray-400 italic">* สีนี้จะถูกใช้กับ Hero, ปุ่ม และส่วนประกอบหลักของเว็ปไซต์</p>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">คำที่ปรากฏในส่วนท้าย (Footer Text)</label>
                        <input type="text" name="footer_text" value="<?= $settings['footer_text'] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl block w-full p-3 shadow-sm">
                    </div>
                </div>
            </div>

            <!-- Section 4: Social Media Links -->
            <div>
                <h3 class="text-xl font-bold text-gray-900 border-b border-gray-100 pb-4 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    โซเชียลมีเดีย (Social Media Links)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Facebook -->
                    <div class="relative">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Facebook Page URL</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-xl">
                                <svg class="w-5 h-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </span>
                            <input type="text" name="social_facebook" value="<?= $settings['social_facebook'] ?>" placeholder="https://facebook.com/yourpage" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3">
                        </div>
                    </div>

                    <!-- Line -->
                    <div class="relative">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Line Official Account</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-xl">
                                <svg class="w-5 h-5 text-[#06C755]" fill="currentColor" viewBox="0 0 24 24"><path d="M21.993 11.43c0-4.69-4.034-8.506-8.993-8.506s-8.993 3.816-8.993 8.506c0 4.205 3.2 7.72 7.522 8.39l-1.077 2s-.13.354.542.188c.67-.166 6.303-3.708 8.563-6.38 1.458-1.5 1.432-3.13 1.432-4.2z"/></svg>
                            </span>
                            <input type="text" name="social_line" value="<?= $settings['social_line'] ?>" placeholder="@yourid" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3">
                        </div>
                    </div>

                    <!-- YouTube -->
                    <div class="relative">
                        <label class="block mb-2 text-sm font-medium text-gray-900">YouTube Channel</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-xl">
                                <svg class="w-5 h-5 text-[#FF0000]" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.872.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </span>
                            <input type="text" name="social_youtube" value="<?= $settings['social_youtube'] ?>" placeholder="https://youtube.com/c/yourchannel" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3">
                        </div>
                    </div>

                    <!-- TikTok -->
                    <div class="relative">
                        <label class="block mb-2 text-sm font-medium text-gray-900">TikTok Account</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-xl">
                                <svg class="w-5 h-5 text-[#010101]" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31 0 2.591.214 3.796.61a8.132 8.132 0 0 0-3.577 3.708c-.141.311-.25.631-.326.96h.006c-.034.152-.057.31-.069.47V15.5c0 1.933-1.567 3.5-3.5 3.5a3.501 3.501 0 0 1-3.414-2.735l-.015-.071a3.507 3.507 0 0 1 3.429-4.194c.15 0 .298.01.444.03v-4.292a7.713 7.713 0 0 0-1.92-.244c-4.252 0-7.7 3.448-7.7 7.7s3.448 7.7 7.7 7.7 7.7-3.448 7.7-7.7V6.03a11.538 11.538 0 0 0 5.23 1.252V3.034a7.24 7.24 0 0 1-5.005-2.1c-.244-.244-.464-.51-.658-.792l-.18-.288a.045.045 0 0 0-.051-.019l-.014.004H12.525z"/></svg>
                            </span>
                            <input type="text" name="social_tiktok" value="<?= $settings['social_tiktok'] ?>" placeholder="@youraccount" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3">
                        </div>
                    </div>

                    <!-- X (Twitter) -->
                    <div class="relative">
                        <label class="block mb-2 text-sm font-medium text-gray-900">X (Twitter) URL</label>
                        <div class="flex">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-xl">
                                <svg class="w-5 h-5 text-black fill-current" viewBox="0 0 24 24"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932 6.064-6.932zm-1.292 19.49h2.039L6.486 3.24H4.298l13.311 17.403z"/></svg>
                            </span>
                            <input type="text" name="social_twitter" value="<?= $settings['social_twitter'] ?>" placeholder="https://x.com/youraccount" class="rounded-none rounded-r-xl bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-10 flex justify-end">
                <button type="submit" class="px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-xl transition transform hover:scale-105 active:scale-95">
                    บันทึกการตั้งค่าทั้งหมด
                </button>
            </div>
        </form>
    </div>
</div>
