<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8">
        <form action="/pages/store" method="POST" enctype="multipart/form-data" class="space-y-6">
            <?= \Core\Security::csrf_field() ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content Area -->
                <div class="lg:col-span-2 space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">หัวข้อหน้าเว็บ (Title)</label>
                        <input type="text" name="title" required class="bg-gray-50 border border-gray-300 text-gray-900 font-bold text-lg rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 transition" placeholder="ระบุชื่อหน้าเว็บ เช่น ประวัติโรงเรียน">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">เนื้อหา (Content)</label>
                        <textarea name="content" id="editor" rows="15" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 transition" placeholder="เขียนเนื้อหาที่ต้องการแสดงผลที่นี่..."></textarea>
                        <p class="mt-2 text-xs text-gray-500">หมายเหตุ: คุณสามารถใช้ HTML Tags เบื้องต้นในการตกแต่งเนื้อหาได้</p>
                    </div>
                </div>

                <!-- Sidebar / Publishing Area -->
                <div class="space-y-6">
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200">การเผยแพร่</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">สถานะ</label>
                                <select name="status" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                    <option value="published">เผยแพร่ (Public)</option>
                                    <option value="draft">ฉบับร่าง (Draft)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">URL Slug (เลือกเติมเองได้)</label>
                                <input type="text" name="slug" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="history-of-school">
                            </div>
                            <div>
                                <label class="block mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">รูปภาพหน้าปก</label>
                                <input type="file" name="featured_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md">
                                บันทึกหน้าเว็บ
                            </button>
                        </div>
                    </div>

                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b border-gray-200">SEO Settings</h4>
                        <div>
                            <label class="block mb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">คำอธิบายย่อ (Meta Description)</label>
                            <textarea name="meta_description" rows="4" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="สรุปเนื้อหาหน้าเว็บสั้นๆ เพื่อผลทาง SEO..."></textarea>
                        </div>
                    </div>
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

<script>
    ClassicEditor
        .create(document.querySelector('#editor'), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo' ],
            language: 'th',
        })
        .catch(error => {
            console.error(error);
        });
</script>
