<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8">
        <form action="<?= url('/news/update/' . $item['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= \Core\Security::csrf_field() ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content Area -->
                <div class="lg:col-span-2 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">หัวข้อข่าว</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required class="bg-gray-50 border border-gray-300 text-gray-900 font-bold text-lg rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 transition">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">รายละเอียดข่าว</label>
                        <textarea name="content" rows="12" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 transition"><?= htmlspecialchars($item['content']) ?></textarea>
                    </div>
                </div>

                <!-- Sidebar Area -->
                <div class="space-y-6">
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200">ตั้งค่าการเผยแพร่</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">หมวดหมู่</label>
                                <select name="category_id" required class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $item['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= $cat['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">สถานะ</label>
                                <select name="status" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="published" <?= $item['status'] == 'published' ? 'selected' : '' ?>>เผยแพร่ทันที</option>
                                    <option value="draft" <?= $item['status'] == 'draft' ? 'selected' : '' ?>>บันทึกฉบับร่าง</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">วันที่ลงข่าว</label>
                                <input type="datetime-local" name="published_at" value="<?= date('Y-m-d\TH:i', strtotime($item['published_at'])) ?>" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200">รูปภาพหน้าปก</h4>
                        <?php if($item['featured_image']): ?>
                        <div class="mb-4 relative rounded-xl overflow-hidden shadow-sm">
                            <img src="<?= url($item['featured_image']) ?>" class="w-full h-auto">
                        </div>
                        <?php endif; ?>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-white hover:bg-gray-50 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-6 h-6 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-xs text-gray-500 font-semibold">เปลี่ยนรูปหน้าปก</p>
                                </div>
                                <input type="file" name="featured_image" class="hidden" accept="image/*" />
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md">
                        บันทึกการเปลี่ยนแปลง
                    </button>
                    <a href="<?= url('/news') ?>" class="block w-full text-center text-gray-500 hover:text-gray-700 font-semibold py-2">ยกเลิก</a>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .ck-editor__editable {
        min-height: 500px !important;
        border-bottom-left-radius: 1rem !important;
        border-bottom-right-radius: 1rem !important;
    }
    .ck-toolbar {
        border-top-left-radius: 1rem !important;
        border-top-right-radius: 1rem !important;
        background-color: #f9fafb !important;
    }
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('textarea[name="content"]'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo'],
            language: 'th'
        })
        .catch(error => {
            console.error(error);
        });
</script>
