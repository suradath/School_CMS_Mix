<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-12">
            <a href="<?= url('/gallery-view') ?>" class="text-sm font-bold text-primary hover:underline mb-4 inline-block">&larr; กลับไปหน้ารวมอัลบั้ม</a>
            <h2 class="text-4xl font-extrabold text-slate-900 heading-font"><?= $title ?></h2>
            <p class="text-gray-500 mt-2"><?= $album['description'] ?></p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="gallery-container">
            <?php 
            $preparedImages = array_map(function($img) {
                $img['image_url'] = url($img['image_url']);
                return $img;
            }, $images);
            foreach ($preparedImages as $img): 
            ?>
            <div class="relative aspect-square group overflow-hidden rounded-2xl bg-gray-200">
                <img src="<?= $img['image_url'] ?>" 
                     class="w-full h-full object-cover cursor-pointer transition duration-500 group-hover:scale-110" 
                     alt="<?= $img['caption'] ?>"
                     data-modal-target="lightbox-modal" 
                     data-modal-toggle="lightbox-modal"
                     onclick="openLightbox('<?= $img['image_url'] ?>', '<?= $img['caption'] ?>')">
                <div class="absolute inset-0 bg-black bg-opacity-20 opacity-0 group-hover:opacity-100 transition p-4 flex flex-col justify-end pointer-events-none">
                    <p class="text-white text-xs font-semibold drop-shadow-md"><?= $img['caption'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if(empty($images)): ?>
        <div class="py-24 text-center bg-white rounded-3xl border border-dashed border-gray-200 shadow-sm">
            <p class="text-gray-400 font-medium">ยังไม่มีรูปภาพในอัลบั้มนี้</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Simple Lightbox Modal -->
<div id="lightbox-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 h-screen max-h-full bg-black/95 p-4">
    <div class="relative w-full max-w-6xl max-h-full flex flex-col items-center justify-center">
        <!-- Close Button -->
        <button type="button" class="absolute top-0 -right-4 md:right-0 text-white hover:text-gray-300 z-[110]" data-modal-hide="lightbox-modal">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- Navigation Buttons -->
        <button type="button" onclick="changeImage(-1)" class="absolute left-0 md:-left-16 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-all p-4 z-[110]">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <button type="button" onclick="changeImage(1)" class="absolute right-0 md:-right-16 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-all p-4 z-[110]">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
        </button>

        <!-- Image and Caption -->
        <div class="w-full flex flex-col items-center">
            <img id="lightbox-img" src="" class="max-w-full max-h-[80vh] rounded-lg shadow-2xl object-contain animate-fade-in" alt="">
            <p id="lightbox-caption" class="text-white mt-8 text-xl font-bold heading-font bg-black/20 px-6 py-2 rounded-full"></p>
            <p id="lightbox-counter" class="text-white/40 mt-2 text-xs font-bold tracking-widest"></p>
        </div>
    </div>
</div>

<script>
let currentGalleryImages = <?= json_encode($preparedImages) ?>;
let currentImageIndex = 0;

function openLightbox(src, caption) {
    const modal = document.getElementById('lightbox-modal');
    
    // ค้นหา Index ของรูปที่ถูกกด
    currentImageIndex = currentGalleryImages.findIndex(img => img.image_url === src);
    
    updateLightbox();
    
    // ย้าย Modal ไปไว้ที่ชั้นนอกสุดของ Body
    document.body.appendChild(modal);
}

function updateLightbox() {
    const img = document.getElementById('lightbox-img');
    const cap = document.getElementById('lightbox-caption');
    const counter = document.getElementById('lightbox-counter');
    const imageData = currentGalleryImages[currentImageIndex];

    if (imageData) {
        // เพิ่ม Animation เล็กน้อยตอนเปลี่ยนรูป
        img.style.opacity = '0';
        setTimeout(() => {
            img.src = imageData.image_url;
            cap.innerText = imageData.caption || '';
            counter.innerText = `IMAGE ${currentImageIndex + 1} OF ${currentGalleryImages.length}`;
            img.style.opacity = '1';
        }, 150);
    }
}

function changeImage(direction) {
    currentImageIndex += direction;
    
    // วนลูปกลับไปเริ่มใหม่ถ้าเกินจำนวนรูป
    if (currentImageIndex >= currentGalleryImages.length) {
        currentImageIndex = 0;
    } else if (currentImageIndex < 0) {
        currentImageIndex = currentGalleryImages.length - 1;
    }
    
    updateLightbox();
}

// รองรับการกดปุ่มบนคีย์บอร์ด
document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('lightbox-modal');
    if (modal && !modal.classList.contains('hidden')) {
        if (e.key === 'ArrowLeft') changeImage(-1);
        if (e.key === 'ArrowRight') changeImage(1);
        if (e.key === 'Escape') {
            // โค้ดสำหรับปิด Modal ของ Flowbite ผ่าน JS (ถ้าต้องการ)
        }
    }
});
</script>
