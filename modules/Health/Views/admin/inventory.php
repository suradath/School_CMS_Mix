<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">คลังยาและเวชภัณฑ์</h1>
            <p class="mt-2 text-gray-600">จัดการรายการยา จำนวนสต๊อกคงเหลือ และการตั้งค่าจุดสั่งซื้อ</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= url('/admin/health') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors">
                <i class="fa fa-arrow-left mr-2"></i> กลับหน้า Dashboard
            </a>
            <button onclick="openModal('addMedModal')" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition-all transform hover:scale-[1.02] active:scale-95">
                <i class="fa fa-plus-circle mr-2"></i> เพิ่มยาใหม่
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">รหัสยา</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">ชื่อยา/เวชภัณฑ์</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">สรรพคุณ</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">คงเหลือ</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">ขั้นต่ำ</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($medicines as $med): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">
                                <?= htmlspecialchars($med['code']) ?>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($med['name']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-500 line-clamp-1"><?= htmlspecialchars($med['properties']) ?></p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-4 py-1 rounded-full text-sm font-bold <?= $med['stock_quantity'] <= $med['min_stock_level'] ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' ?>">
                                    <?= number_format($med['stock_quantity']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-500">
                                <?= number_format($med['min_stock_level']) ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button onclick='editMed(<?= json_encode($med) ?>)' class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button onclick="deleteMed(<?= $med['id'] ?>)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="fa fa-trash"></i>
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

<!-- Add Medicine Modal -->
<div id="addMedModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-fade-in">
            <form action="<?= url('/admin/health/medicine/store') ?>" method="POST">
                <?= \Core\Security::csrf_field() ?>
                <div class="bg-white px-8 pt-8 pb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">เพิ่มยา/เวชภัณฑ์ใหม่</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">รหัสยา</label>
                            <input type="text" name="code" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-3 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">ชื่อยา</label>
                            <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-3 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">สรรพคุณ</label>
                            <textarea name="properties" rows="2" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-3 focus:ring-blue-500"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">จำนวนเริ่มต้น</label>
                                <input type="number" name="stock_quantity" value="0" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-3 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">จุดสั่งซื้อ (Min)</label>
                                <input type="number" name="min_stock_level" value="10" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-3 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-8 py-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('addMedModal')" class="px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-100 transition-colors">ยกเลิก</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Medicine Modal -->
<div id="editMedModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="<?= url('/admin/health/medicine/update') ?>" method="POST">
                <?= \Core\Security::csrf_field() ?>
                <input type="hidden" name="id" id="edit_id">
                <div class="bg-white px-8 pt-8 pb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">แก้ไขข้อมูลยา</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">ชื่อยา</label>
                            <input type="text" name="name" id="edit_name" required class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-3">
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">สรรพคุณ</label>
                            <textarea name="properties" id="edit_properties" rows="2" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-3"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">จำนวนคงเหลือ</label>
                                <input type="number" name="stock_quantity" id="edit_qty" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-3">
                            </div>
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-700">จุดสั่งซื้อ (Min)</label>
                                <input type="number" name="min_stock_level" id="edit_min" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl p-3">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-8 py-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal('editMedModal')" class="px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-100 transition-colors">ยกเลิก</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
}
function editMed(med) {
    document.getElementById('edit_id').value = med.id;
    document.getElementById('edit_name').value = med.name;
    document.getElementById('edit_properties').value = med.properties;
    document.getElementById('edit_qty').value = med.stock_quantity;
    document.getElementById('edit_min').value = med.min_stock_level;
    openModal('editMedModal');
}
function deleteMed(id) {
    if (confirm('ยืนยันการลบรายการยานี้?')) {
        window.location.href = `<?= url('/admin/health/medicine/delete') ?>?id=${id}`;
    }
}
</script>
<style>
@keyframes fade-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
.animate-fade-in { animation: fade-in 0.2s ease-out forwards; }
</style>
