<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gradient-to-r from-white to-slate-50/50">
        <div>
            <h3 class="text-xl font-bold text-slate-800 heading-font">รายการประเภทพฤติกรรม</h3>
            <p class="text-sm text-slate-500 mt-1">จัดการเกณฑ์การให้คะแนนและความผิดของนักเรียน</p>
        </div>
        <button onclick="openModal()" class="px-6 py-3 bg-primary text-white rounded-2xl font-bold text-sm hover:shadow-lg hover:shadow-primary/30 transition-all flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            เพิ่มประเภทใหม่
        </button>
    </div>

    <div class="p-8">
        <div class="overflow-x-auto">
            <table id="categoryTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">ชื่อพฤติกรรม</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">ค่าคะแนน</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">หมวดหมู่</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($categories as $cat): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-700"><?= $cat['name'] ?></td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold <?= $cat['points'] > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= $cat['points'] > 0 ? '+' . $cat['points'] : $cat['points'] ?> คะแนน
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($cat['type'] === 'good'): ?>
                                <span class="flex items-center text-green-600 text-xs font-bold">
                                    <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div> ความดี
                                </span>
                            <?php else: ?>
                                <span class="flex items-center text-red-600 text-xs font-bold">
                                    <div class="w-2 h-2 rounded-full bg-red-500 mr-2"></div> ความผิด
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button onclick='editCategory(<?= json_encode($cat) ?>)' class="p-2 text-blue-500 hover:bg-blue-50 rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button onclick="deleteCategory(<?= $cat['id'] ?>)" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="categoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-white/20">
            <form action="<?= url('/discipline/saveCategory') ?>" method="POST">
                <?= \Core\Security::csrf_field() ?>
                <input type="hidden" name="id" id="modal_id" value="">
                <div class="bg-white p-8">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6 heading-font" id="modalTitle">เพิ่มประเภทพฤติกรรม</h3>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">ชื่อพฤติกรรม</label>
                            <input type="text" name="name" id="modal_name" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3 text-slate-700 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none" placeholder="เช่น มาสาย, จิตอาสา">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">คะแนน (เช่น -5 หรือ 10)</label>
                                <input type="number" name="points" id="modal_points" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3 text-slate-700 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">ประเภท</label>
                                <select name="type" id="modal_type" required class="w-full bg-slate-50 border border-slate-100 rounded-2xl px-4 py-3 text-slate-700 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none appearance-none">
                                    <option value="good">ความดี (บวก)</option>
                                    <option value="bad">ความผิด (ลบ)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-8 py-6 flex flex-row-reverse space-x-reverse space-x-3">
                    <button type="submit" class="px-8 py-3 bg-primary text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 hover:-translate-y-0.5 transition-all">บันทึกข้อมูล</button>
                    <button type="button" onclick="closeModal()" class="px-6 py-3 bg-white text-slate-500 border border-slate-200 rounded-2xl font-bold text-sm hover:bg-slate-50 transition-all">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        initPremiumDataTable('#categoryTable', {
            order: [[2, 'asc'], [1, 'desc']]
        });
    });

    function openModal() {
        $('#modal_id').val('');
        $('#modal_name').val('');
        $('#modal_points').val('');
        $('#modal_type').val('bad');
        $('#modalTitle').text('เพิ่มประเภทพฤติกรรม');
        $('#categoryModal').removeClass('hidden');
    }

    function closeModal() {
        $('#categoryModal').addClass('hidden');
    }

    function editCategory(cat) {
        $('#modal_id').val(cat.id);
        $('#modal_name').val(cat.name);
        $('#modal_points').val(cat.points);
        $('#modal_type').val(cat.type);
        $('#modalTitle').text('แก้ไขประเภทพฤติกรรม');
        $('#categoryModal').removeClass('hidden');
    }

    function deleteCategory(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบประเภทพฤติกรรมนี้ใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= url('/discipline/deleteCategory') ?>';
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = 'csrf_token';
                csrf.value = '<?= \Core\Security::csrf_token() ?>';
                form.appendChild(csrf);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id';
                input.value = id;
                form.appendChild(input);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
