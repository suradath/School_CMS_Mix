<div class="max-w-3xl mx-auto">
    <div class="mb-10 flex items-center">
        <a href="/settings/popups" class="mr-4 p-2 bg-white rounded-xl shadow-sm border border-gray-100 text-slate-400 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <h3 class="text-3xl font-extrabold text-slate-900 heading-font">เพิ่ม Entry Popup</h3>
    </div>

    <form action="/settings/popups/store" method="POST" enctype="multipart/form-data" class="space-y-8">
        <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100">
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ชื่อรายการ <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required placeholder="เช่น ประชาสัมพันธ์รับสมัครนักเรียน"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">รูปภาพ <span class="text-red-500">*</span></label>
                    <input type="file" name="image" required accept="image/*"
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ลิงก์ URL (ถ้ามี)</label>
                    <input type="url" name="link_url" placeholder="https://..."
                        class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-900">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" checked value="1" class="w-5 h-5 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary focus:ring-2">
                    <label for="is_active" class="ml-3 text-sm font-bold text-slate-700">เปิดใช้งานทันที</label>
                </div>
            </div>

            <div class="mt-12 flex justify-end">
                <button type="submit" class="px-10 py-4 bg-primary text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-primary/20 transform hover:-translate-y-1">
                    บันทึกข้อมูล
                </button>
            </div>
        </div>
    </form>
</div>
