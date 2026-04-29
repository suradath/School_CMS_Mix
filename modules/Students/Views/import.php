<div class="mb-6">
    <a href="<?= url('/students') ?>" class="text-sm text-gray-500 hover:text-primary transition-colors font-bold">
        <i class="fa fa-arrow-left mr-1"></i> กลับไปหน้าภาพรวม
    </a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 flex items-center">
        <i class="fa fa-exclamation-circle mr-2 text-lg"></i> <?= $_SESSION['error'] ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-blue-50 p-6 rounded-3xl border border-blue-100">
            <h3 class="text-lg font-bold text-blue-900 heading-font mb-4">
                <i class="fa fa-info-circle mr-2"></i> คำแนะนำในการเตรียมไฟล์
            </h3>
            <ul class="space-y-3 text-sm text-blue-800 leading-relaxed">
                <li class="flex"><i class="fa fa-check text-green-500 mt-1 mr-2 shrink-0"></i> บันทึกไฟล์จากระบบ DMC เป็นนามสกุล <span class="font-bold ml-1">.csv (Comma delimited)</span></li>
                <li class="flex"><i class="fa fa-check text-green-500 mt-1 mr-2 shrink-0"></i> ระบบจะนำเข้าข้อมูลโดยยึดตามลำดับคอลัมน์มาตรฐานของไฟล์ DMC</li>
                <li class="flex"><i class="fa fa-check text-green-500 mt-1 mr-2 shrink-0"></i> หากมีรหัสนักเรียนหรือเลขบัตรประชาชนซ้ำในระบบ จะเป็นการ <strong>อัปเดตข้อมูล</strong> แทนการเพิ่มใหม่</li>
            </ul>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 heading-font mb-6 border-b pb-4">อัปโหลดไฟล์ CSV</h3>
            
            <form action="<?= url('/students/import-process') ?>" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="document.getElementById('submitBtn').disabled = true; document.getElementById('submitBtn').innerHTML = '<i class=\'fa fa-spinner fa-spin mr-2\'></i> กำลังนำเข้าข้อมูล...';">
                <?= \Core\Security::csrf_field() ?>
                
                <div class="flex items-center justify-center w-full">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed rounded-3xl cursor-pointer border-primary/40 bg-primary/5 hover:bg-primary/10 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                            <i class="fa fa-cloud-upload text-5xl text-primary/60 mb-4"></i>
                            <p class="mb-2 text-sm text-gray-700 font-bold"><span class="font-semibold text-primary">คลิกเพื่อเลือกไฟล์</span> หรือลากไฟล์มาวางที่นี่</p>
                            <p class="text-xs text-gray-500">รองรับเฉพาะไฟล์ .csv เท่านั้น (ขนาดไม่เกิน 10MB)</p>
                            <p id="file-name" class="mt-4 text-sm font-bold text-primary hidden"></p>
                        </div>
                        <input id="dropzone-file" type="file" name="csv_file" accept=".csv" class="hidden" required onchange="document.getElementById('file-name').textContent = 'ไฟล์ที่เลือก: ' + this.files[0].name; document.getElementById('file-name').classList.remove('hidden');" />
                    </label>
                </div> 

                <div class="flex justify-end pt-4">
                    <button type="submit" id="submitBtn" class="px-8 py-3 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-colors shadow-sm shadow-primary/30 flex items-center">
                        <i class="fa fa-upload mr-2"></i> ยืนยันการนำเข้าข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
