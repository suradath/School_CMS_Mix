<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-gray-900">สร้างอัลบั้มภาพกิจกรรมใหม่</h3>
                <p class="text-sm text-gray-500 mt-1">กรอกข้อมูลเพื่อสร้างอัลบั้มสำหรับเก็บรวบรวมรูปภาพกิจกรรมต่างๆ</p>
            </div>
            <a href="/gallery" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                ย้อนกลับ
            </a>
        </div>
        
        <form action="/gallery/store" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            <?= \Core\Security::csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Side: Basic Info -->
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block mb-2 text-sm font-bold text-gray-700 uppercase tracking-widest">ชื่ออัลบั้ม <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" required
                            class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-4 transition" 
                            placeholder="เช่น กิจกรรมวันไหว้ครู ประจำปี 2567">
                    </div>

                    <div>
                        <label for="description" class="block mb-2 text-sm font-bold text-gray-700 uppercase tracking-widest">คำอธิบายอัลบั้ม</label>
                        <textarea id="description" name="description" rows="4" 
                            class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-4 transition" 
                            placeholder="รายละเอียดเกี่ยวกับกิจกรรมในอัลบั้มนี้..."></textarea>
                    </div>
                </div>

                <!-- Right Side: Cover Image -->
                <div class="space-y-6">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700 uppercase tracking-widest">รูปหน้าปกอัลบั้ม</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="cover_image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-200 border-dashed rounded-3xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                    <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-bold">คลิกเพื่ออัปโหลด</span> หรือลากไฟล์มาวาง</p>
                                    <p class="text-xs text-gray-400">PNG, JPG หรือ WEBP (ขนาดแนะนำ 800x600px)</p>
                                </div>
                                <img id="preview-image" src="#" alt="Preview" class="hidden absolute inset-0 w-full h-full object-cover">
                                <input id="cover_image" name="cover_image" type="file" class="hidden" accept="image/*" onchange="previewFile(this)" />
                            </label>
                        </div>
                        <p class="mt-2 text-xs text-gray-400 italic">* หากไม่เลือกรูปภาพ ระบบจะใช้รูปภาพเริ่มต้น</p>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-50 flex justify-end">
                <button type="submit" class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:outline-none focus:ring-primary/30 font-bold rounded-2xl text-sm px-8 py-4 text-center transition shadow-lg shadow-primary/20 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    สร้างอัลบั้มและไปขั้นตอนถัดไป
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewFile(input) {
    const preview = document.getElementById('preview-image');
    const placeholder = document.getElementById('upload-placeholder');
    const file = input.files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
        preview.classList.remove('hidden');
        placeholder.classList.add('opacity-0');
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "";
        preview.classList.add('hidden');
        placeholder.classList.remove('opacity-0');
    }
}
</script>
