<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fa fa-wrench text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">แจ้งซ่อม / บำรุงรักษา</h1>
                <p class="text-slate-500 font-medium mt-1">กรอกข้อมูลปัญหาที่พบเพื่อให้เจ้าหน้าที่เข้าดำเนินการ</p>
            </div>
        </div>
        <a href="<?= url('/helpdesk/my-repairs') ?>" class="px-6 py-3 bg-slate-50 text-slate-600 font-bold rounded-2xl hover:bg-slate-100 transition-all flex items-center gap-2">
            <i class="fa fa-history"></i> ประวัติการแจ้งของฉัน
        </a>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
        <form id="helpdeskForm" class="p-8 space-y-6" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ประเภทงานซ่อม <span class="text-rose-500">*</span></label>
                    <select name="category_id" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 p-4 transition-all" required>
                        <option value="">-- เลือกประเภท --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Location -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">พิกัดห้อง / สถานที่ <span class="text-rose-500">*</span></label>
                    <input type="text" name="location" placeholder="เช่น ห้องประชุมอาคาร 1, ห้องพักครูคณิตศาสตร์" 
                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 p-4 transition-all" required>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">รายละเอียดปัญหา <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="4" placeholder="ระบุรายละเอียดของปัญหาที่พบ..." 
                          class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 p-4 transition-all" required></textarea>
            </div>

            <!-- Photo Upload -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">รูปภาพประกอบ (สูงสุด 3 รูป)</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="file" name="photos[]" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <input type="file" name="photos[]" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <input type="file" name="photos[]" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <p class="mt-2 text-xs text-slate-400 italic font-medium">รองรับไฟล์ภาพ JPG, PNG ขนาดไม่เกิน 5MB ต่อรูป</p>
            </div>

            <div class="pt-6 border-t border-slate-50">
                <button type="submit" class="w-full md:w-auto px-10 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/30 transition-all flex items-center justify-center gap-3 group">
                    <span>ส่งข้อมูลแจ้งซ่อม</span>
                    <i class="fa fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('helpdeskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    Swal.fire({
        title: 'กำลังส่งข้อมูล...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    fetch('<?= url('/helpdesk/store') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'สำเร็จ!',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#2563eb'
            }).then(() => {
                window.location.href = '<?= url('/helpdesk/my-repairs') ?>';
            });
        } else {
            Swal.fire({
                title: 'ผิดพลาด!',
                text: data.message,
                icon: 'error',
                confirmButtonColor: '#2563eb'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'ผิดพลาด!',
            text: 'เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์',
            icon: 'error',
            confirmButtonColor: '#2563eb'
        });
    });
});
</script>
