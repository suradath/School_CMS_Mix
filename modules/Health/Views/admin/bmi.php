<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">จัดการข้อมูลน้ำหนัก-ส่วนสูง (BMI)</h1>
            <p class="mt-2 text-gray-600">ดาวน์โหลดเทมเพลตรายห้องเรียนเพื่อบันทึกข้อมูลและนำเข้ากลับสู่ระบบ</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= url('/admin/health') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors">
                <i class="fa fa-arrow-left mr-2"></i> กลับหน้าหลักพยาบาล
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Section 1: Export CSV -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center bg-blue-50/30">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 mr-4">
                    <i class="fa fa-download text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">1. ดาวน์โหลด Template (Export)</h2>
                    <p class="text-sm text-gray-500">ส่งออกรายชื่อนักเรียนแยกตามห้องเรียน</p>
                </div>
            </div>
            <div class="p-8">
                <form action="<?= url('/admin/health/export-csv') ?>" method="GET" class="space-y-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">เลือกระดับชั้น</label>
                        <select name="class" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3">
                            <option value="">-- เลือกชั้นเรียน --</option>
                            <?php foreach ($classes as $c): ?>
                                <option value="<?= htmlspecialchars($c['class_level']) ?>"><?= htmlspecialchars($c['class_level']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">ระบุห้องเรียน (ตัวเลข)</label>
                        <input type="number" name="room" required placeholder="เช่น 1" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3">
                    </div>
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-200 transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center">
                        <i class="fa fa-file-csv mr-2"></i> ดาวน์โหลดไฟล์ CSV
                    </button>
                </form>
                <div class="mt-6 p-4 bg-yellow-50 rounded-2xl border border-yellow-100">
                    <p class="text-xs text-yellow-800 leading-relaxed">
                        <i class="fa fa-info-circle mr-1"></i> <strong>คำแนะนำ:</strong> ไฟล์ที่ดาวน์โหลดจะมีข้อมูล รหัส, ชื่อ-สกุล และค่าเดิมที่มีอยู่ คุณสามารถเปิดไฟล์ด้วย Excel เพื่อกรอกน้ำหนักและส่วนสูงใหม่ได้ทันที
                    </p>
                </div>
            </div>
        </div>

        <!-- Section 2: Import CSV -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center bg-emerald-50/30">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 mr-4">
                    <i class="fa fa-upload text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">2. นำเข้าข้อมูล (Import)</h2>
                    <p class="text-sm text-gray-500">อัปโหลดไฟล์ที่กรอกข้อมูลเสร็จแล้ว</p>
                </div>
            </div>
            <div class="p-8">
                <form action="<?= url('/admin/health/import-csv') ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?= \Core\Security::csrf_field() ?>
                    <div class="relative group">
                        <label class="block mb-2 text-sm font-medium text-gray-700">เลือกไฟล์ CSV</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-3xl p-8 flex flex-col items-center justify-center bg-gray-50 group-hover:border-emerald-400 transition-colors">
                            <i class="fa fa-cloud-upload text-3xl text-gray-400 mb-4 group-hover:text-emerald-500"></i>
                            <input type="file" name="csv_file" accept=".csv" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <p class="text-sm text-gray-500 text-center">คลิกหรือลากไฟล์มาวางที่นี่<br><span class="text-xs">(เฉพาะไฟล์ .csv เท่านั้น)</span></p>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-lg shadow-emerald-200 transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center">
                        <i class="fa fa-check-circle mr-2"></i> อัปเดตข้อมูลนักเรียน
                    </button>
                </form>
                <div class="mt-6 space-y-3">
                    <h4 class="text-sm font-bold text-gray-700 flex items-center">
                        <i class="fa fa-shield-check text-emerald-500 mr-2"></i> ความปลอดภัยของข้อมูล
                    </h4>
                    <ul class="text-xs text-gray-500 space-y-1 list-disc list-inside">
                        <li>ระบบจะอ้างอิง "รหัสนักเรียน" เป็นหลัก</li>
                        <li>ข้อมูลน้ำหนักและส่วนสูงจะถูกเขียนทับทันที</li>
                        <li>รองรับภาษาไทยสมบูรณ์ (UTF-8 with BOM)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
