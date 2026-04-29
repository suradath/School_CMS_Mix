<div class="mb-8">
    <a href="<?= url('/gallery') ?>" class="text-sm font-semibold text-blue-600 hover:underline mb-4 inline-block">&larr; กลับไปหน้ารวมอัลบั้ม</a>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h3 class="text-3xl font-bold text-gray-900 heading-font"><?= $album['title'] ?></h3>
            <p class="text-gray-500 mt-1"><?= $album['description'] ?></p>
        </div>
        <button data-modal-target="add-photo-modal" data-modal-toggle="add-photo-modal" class="mt-4 md:mt-0 px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow-md hover:bg-blue-700 transition">
            เพิ่มรูปภาพใหม่
        </button>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
    <?php foreach ($images as $img): ?>
    <div class="relative aspect-square group overflow-hidden rounded-2xl bg-gray-200">
        <img src="<?= url($img['image_url']) ?>" class="w-full h-full object-cover transition duration-300 group-hover:scale-110" alt="<?= $img['caption'] ?>">
        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition p-4 flex flex-col justify-end">
            <p class="text-white text-xs font-medium truncate"><?= $img['caption'] ?></p>
            <div class="mt-2 flex space-x-2">
                <a href="<?= url($img['image_url']) ?>" target="_blank" class="p-1.5 bg-white text-gray-700 rounded-lg hover:bg-gray-100"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                <a href="<?= url('/gallery/deletePhoto/' . $img['id']) ?>" class="p-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600" onclick="return confirm('คุณต้องการลบรูปภาพนี้ใช่หรือไม่?')"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if (empty($images)): ?>
    <div class="col-span-full py-20 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
        <p class="text-gray-400">ยังไม่มีรูปภาพในอัลบั้มนี้</p>
    </div>
    <?php endif; ?>
</div>

<!-- Add Photo Modal -->
<div id="add-photo-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[9999] justify-center items-center w-full md:inset-0 h-full">
    <div class="relative p-4 w-full max-w-md h-auto">
        <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-gray-50 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 heading-font">เพิ่มรูปภาพใหม่</h3>
                <button type="button" class="text-gray-400 bg-white hover:bg-gray-100 hover:text-gray-900 rounded-xl text-sm w-8 h-8 inline-flex justify-center items-center shadow-sm border border-gray-200 transition" data-modal-toggle="add-photo-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>
            <form action="<?= url('/gallery/addPhoto/' . $album['id']) ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                <?= \Core\Security::csrf_field() ?>
                <div>
                    <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-widest">เลือกไฟล์รูปภาพ (เลือกได้หลายรูป)</label>
                    <input type="file" name="photos[]" multiple required class="block w-full text-sm text-gray-900 border border-gray-200 rounded-2xl cursor-pointer bg-gray-50 focus:outline-none p-2 transition hover:bg-gray-100" accept="image/*">
                </div>
                <div>
                    <label class="block mb-2 text-xs font-bold text-gray-400 uppercase tracking-widest">คำอธิบายภาพ (Caption)</label>
                    <input type="text" name="caption" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-4 transition" placeholder="ระบุสิ่งที่อยู่ในภาพ">
                </div>
                <button type="submit" class="w-full text-white inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-2xl text-sm px-5 py-4 text-center transition shadow-lg shadow-blue-500/20">
                    <svg class="me-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    เริ่มอัปโหลดรูปภาพ
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // ย้าย Modal ไปไว้ที่ชั้นนอกสุดของ Body เพื่อแก้ปัญหาโดนทับ
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('add-photo-modal');
        if (modal) {
            document.body.appendChild(modal);
        }
    });
</script>
