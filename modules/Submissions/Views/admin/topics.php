<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 flex flex-col md:flex-row justify-between items-center border-b border-gray-50 gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-800 heading-font">จัดการหัวข้อการส่งเอกสาร</h3>
            <p class="text-sm text-slate-500 mt-1">กำหนดหัวข้อ ภาคเรียน และเงื่อนไขการส่งไฟล์สำหรับบุคลากร</p>
        </div>
        <button onclick="openModal()" class="inline-flex items-center px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-primary/20">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            เพิ่มหัวข้อใหม่
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left" id="topicsTable">
            <thead>
                <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
                    <th class="px-8 py-5">ปีการศึกษา/ภาคเรียน</th>
                    <th class="px-8 py-5">หัวข้อ</th>
                    <th class="px-8 py-5">นามสกุลไฟล์ที่รองรับ</th>
                    <th class="px-8 py-5">ขนาดไฟล์สูงสุด</th>
                    <th class="px-8 py-5">สถานะ</th>
                    <th class="px-8 py-5 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php if (empty($topics)): ?>
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center text-slate-400">
                            ยังไม่มีข้อมูลหัวข้อการส่งเอกสารในระบบ
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($topics as $t): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5 font-medium text-slate-600">
                                <?= $t['academic_year'] ?> / <?= $t['semester'] ?>
                            </td>
                            <td class="px-8 py-5 font-bold text-slate-900">
                                <?= htmlspecialchars($t['title']) ?>
                                <p class="text-xs text-slate-400 font-normal mt-1"><?= htmlspecialchars($t['description'] ?? '') ?></p>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-wrap gap-1">
                                    <?php foreach($t['allowed_files'] as $ext): ?>
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded uppercase">.<?= $ext ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-slate-600">
                                <?= $t['max_file_size'] ?> MB
                            </td>
                            <td class="px-8 py-5">
                                <?php if ($t['status'] === 'active'): ?>
                                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">เปิดรับ</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-500 text-xs font-bold rounded-full">ปิด</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="editTopic(<?= htmlspecialchars(json_encode($t)) ?>)" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button onclick="confirmDelete(<?= $t['id'] ?>)" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Topic Modal -->
<div id="topicModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-fade-in-up">
            <form action="<?= url('/submissions/topics/store') ?>" method="POST" id="topicForm">
                <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
                <input type="hidden" name="id" id="topic_id">
                
                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-800 heading-font" id="modalTitle">เพิ่มหัวข้อการส่งเอกสาร</h3>
                        <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">หัวข้อการส่งเอกสาร <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" required placeholder="เช่น แผนการจัดการเรียนรู้, PLC" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">ปีการศึกษา</label>
                                <select name="academic_year" id="academic_year" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                    <?php for($y = 2568; $y <= 2580; $y++): ?>
                                        <option value="<?= $y ?>" <?= ($y == (date('Y')+543)) ? 'selected' : '' ?>><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">ภาคเรียน</label>
                                <select name="semester" id="semester" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">รายละเอียด (ถ้ามี)</label>
                            <textarea name="description" id="description" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">ประเภทไฟล์ที่อนุญาต</label>
                            <div class="flex flex-wrap gap-4 mt-2">
                                <?php foreach(['pdf', 'docx', 'xlsx', 'jpg', 'png', 'zip'] as $ext): ?>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="allowed_files[]" value="<?= $ext ?>" class="form-checkbox h-5 w-5 text-primary border-slate-300 rounded-md focus:ring-primary">
                                        <span class="ml-2 text-sm text-slate-600 font-medium uppercase">.<?= $ext ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">ขนาดไฟล์สูงสุด (MB)</label>
                                <input type="number" name="max_file_size" id="max_file_size" value="20" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">สถานะ</label>
                                <select name="status" id="status" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                                    <option value="active">เปิดรับการส่ง</option>
                                    <option value="inactive">ปิดรับ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all">ยกเลิก</button>
                    <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-primary/20">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form (Hidden) -->
<form id="deleteForm" action="<?= url('/submissions/topics/delete') ?>" method="POST" class="hidden">
    <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
    <input type="hidden" name="id" id="delete_id">
</form>

<script>
    $(document).ready(function() {
        initPremiumDataTable('#topicsTable', {
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: [2, 5] }
            ]
        });
    });

    function openModal() {
        document.getElementById('modalTitle').innerText = 'เพิ่มหัวข้อการส่งเอกสาร';
        document.getElementById('topicForm').reset();
        document.getElementById('topic_id').value = '';
        document.getElementById('topicModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('topicModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function editTopic(data) {
        document.getElementById('modalTitle').innerText = 'แก้ไขหัวข้อการส่งเอกสาร';
        document.getElementById('topic_id').value = data.id;
        document.getElementById('title').value = data.title;
        document.getElementById('description').value = data.description;
        document.getElementById('academic_year').value = data.academic_year;
        document.getElementById('semester').value = data.semester;
        document.getElementById('max_file_size').value = data.max_file_size;
        document.getElementById('status').value = data.status;
        
        // Check checkboxes
        const checkboxes = document.querySelectorAll('input[name="allowed_files[]"]');
        checkboxes.forEach(cb => {
            cb.checked = data.allowed_files.includes(cb.value);
        });

        document.getElementById('topicModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบหัวข้อนี้?',
            text: "ข้อมูลการส่งงานที่เกี่ยวข้องทั้งหมดจะถูกลบออกด้วย!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ใช่, ลบเลย',
            cancelButtonText: 'ยกเลิก',
            borderRadius: '1.5rem',
            customClass: {
                confirmButton: 'rounded-xl font-bold px-6 py-3',
                cancelButton: 'rounded-xl font-bold px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete_id').value = id;
                document.getElementById('deleteForm').submit();
            }
        });
    }
</script>

<style>
.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
