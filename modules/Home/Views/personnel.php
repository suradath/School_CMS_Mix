<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-extrabold text-slate-900 heading-font"><?= $title ?></h2>
            <p class="mt-4 text-gray-500 max-w-2xl mx-auto">คณะครูและบุคลากรทางการศึกษาผู้มุ่งมั่นพัฒนาเยาวชนและสร้างสรรค์สังคมการเรียนรู้</p>
        </div>

        <?php foreach ($departments as $dept): ?>
            <?php if (!empty($dept['members'])): ?>
            <div class="mb-20">
                <div class="flex items-center mb-8">
                    <h3 class="text-2xl font-bold text-primary heading-font mr-4"><?= $dept['name'] ?></h3>
                    <div class="flex-grow h-[2px] bg-primary opacity-10"></div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php foreach ($dept['members'] as $person): ?>
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition flex flex-col items-center p-8">
                        <div class="relative mb-6">
                            <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-white shadow-md ring-4 ring-primary ring-opacity-10">
                                <img src="<?= $person['image_url'] ? url($person['image_url']) : 'https://ui-avatars.com/api/?name='.urlencode($person['name']).'&background=random' ?>" 
                                     class="w-full h-full object-cover transition duration-500 group-hover:scale-110" 
                                     alt="<?= $person['name'] ?>">
                            </div>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 text-center mb-1"><?= $person['name'] ?></h4>
                        <p class="text-sm text-primary font-semibold mb-4 text-center"><?= $person['position'] ?></p>
                        
                        <?php if($person['bio']): ?>
                        <p class="text-xs text-gray-400 text-center line-clamp-2 italic mb-4">"<?= $person['bio'] ?>"</p>
                        <?php endif; ?>

                        <div class="mt-auto flex space-x-3 pt-4 border-t border-gray-50 w-full justify-center">
                            <?php if($person['email']): ?>
                            <a href="mailto:<?= $person['email'] ?>" class="text-gray-400 hover:text-primary transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </a>
                            <?php endif; ?>
                            <?php if($person['phone']): ?>
                            <a href="tel:<?= $person['phone'] ?>" class="text-gray-400 hover:text-primary transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
