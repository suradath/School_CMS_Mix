<div class="max-w-4xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fa fa-tags text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">จัดการประเภทงานซ่อม</h1>
                <p class="text-slate-500 font-medium mt-1">กำหนดประเภทงานเพื่อให้ง่ายต่อการคัดกรองและมอบหมายงาน</p>
            </div>
        </div>
        <button onclick="addCategory()" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all flex items-center gap-2">
            <i class="fa fa-plus"></i> เพิ่มประเภทใหม่
        </a>
    </div>

    <!-- Categories List -->
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
        <div class="p-8">
            <table id="categoriesTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4">ชื่อประเภท</th>
                        <th class="px-6 py-4">Slug (สำหรับระบบ)</th>
                        <th class="px-6 py-4 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td class="px-6 py-4 font-bold text-slate-900"><?= $cat['name'] ?></td>
                            <td class="px-6 py-4 text-slate-400 font-medium"><?= $cat['slug'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="deleteCategory(<?= $cat['id'] ?>, '<?= $cat['name'] ?>')" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    initPremiumDataTable('#categoriesTable');
});

function addCategory() {
    Swal.fire({
        title: 'เพิ่มประเภทงานซ่อม',
        input: 'text',
        inputLabel: 'ชื่อประเภทงาน',
        inputPlaceholder: 'เช่น ระบบปรับอากาศ, งานไม้...',
        showCancelButton: true,
        confirmButtonText: 'บันทึก',
        confirmButtonColor: '#2563eb',
        cancelButtonText: 'ยกเลิก',
        inputValidator: (value) => {
            if (!value) return 'กรุณาระบุชื่อประเภท';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= url('/admin/helpdesk/categories/store') ?>', {
                name: result.value,
                csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
            }, function(data) {
                if (data.success) {
                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'เพิ่มประเภทเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', 'ไม่สามารถบันทึกได้', 'error');
                }
            });
        }
    });
}

function deleteCategory(id, name) {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: `คุณแน่ใจหรือไม่ว่าต้องการลบประเภท "${name}"`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'ใช่, ลบเลย',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= url('/admin/helpdesk/categories/delete') ?>', {
                id: id,
                csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
            }, function(data) {
                if (data.success) {
                    Swal.fire({
                        title: 'ลบแล้ว!',
                        text: 'ลบประเภทเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', data.message || 'ไม่สามารถลบได้', 'error');
                }
            });
        }
    });
}
</script>
