<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">จัดการทรัพยากร</h1>
            <p class="text-gray-600">จัดการข้อมูลห้องประชุมและยานพาหนะ</p>
        </div>
        <button onclick="openResourceModal()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center gap-2">
            <i class="fa fa-plus"></i> เพิ่มทรัพยากรใหม่
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($resources as $r): ?>
            <div
                class="bg-white rounded-2xl shadow-md border border-gray-300 overflow-hidden hover:shadow-lg transition duration-300">
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= $r['type'] === 'room' ? 'bg-indigo-100 text-indigo-700' : 'bg-purple-100 text-purple-700' ?>">
                            <?= $r['type'] === 'room' ? 'ห้องประชุม' : 'ยานพาหนะ' ?>
                        </span>
                        <span
                            class="px-3 py-1 rounded-full text-xs font-medium <?= $r['status'] === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                            <?= $r['status'] === 'available' ? 'พร้อมใช้งาน' : 'ปิดซ่อมบำรุง' ?>
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($r['name']) ?></h3>
                    <p class="text-gray-500 text-sm mb-4 h-12 overflow-hidden">
                        <?= htmlspecialchars($r['description'] ?? '-') ?>
                    </p>

                    <div class="space-y-2 mb-6">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fa fa-users w-6"></i>
                            <span>ความจุ: <?= $r['capacity'] ?> คน/ที่นั่ง</span>
                        </div>
                        <?php if ($r['type'] === 'vehicle'): ?>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fa fa-car w-6"></i>
                                <span>ทะเบียน: <?= htmlspecialchars($r['license_plate'] ?? '-') ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-50">
                        <button onclick="editResource(<?= htmlspecialchars(json_encode($r)) ?>)"
                            class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button onclick="deleteResource(<?= $r['id'] ?>)"
                            class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal for Resource CRUD -->
<div id="resourceModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true"
            onclick="closeResourceModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <form id="resourceForm">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-xl leading-6 font-bold text-gray-900 mb-6" id="modal-title">ข้อมูลทรัพยากร</h3>
                    <input type="hidden" name="id" id="res_id">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ประเภท</label>
                            <select name="type" id="res_type" onchange="toggleVehicleFields(this.value)"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border"
                                required>
                                <option value="room">ห้องประชุม</option>
                                <option value="vehicle">ยานพาหนะ</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ชื่อรายการ</label>
                            <input type="text" name="name" id="res_name"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ความจุ (คน)</label>
                            <input type="number" name="capacity" id="res_capacity"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border"
                                required>
                        </div>
                        <div id="vehicle_fields" class="hidden">
                            <label class="block text-sm font-medium text-gray-700">เลขทะเบียนรถ</label>
                            <input type="text" name="license_plate" id="res_license"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">สถานะ</label>
                            <select name="status" id="res_status"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border">
                                <option value="available">พร้อมใช้งาน</option>
                                <option value="maintenance">ปิดซ่อมบำรุง</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">รายละเอียด</label>
                            <textarea name="description" id="res_desc" rows="3"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button" onclick="saveResource()"
                        class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                        บันทึก
                    </button>
                    <button type="button" onclick="closeResourceModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        ยกเลิก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openResourceModal() {
        document.getElementById('resourceModal').classList.remove('hidden');
        document.getElementById('resourceForm').reset();
        document.getElementById('res_id').value = '';
        document.getElementById('modal-title').innerText = 'เพิ่มทรัพยากรใหม่';
        toggleVehicleFields('room');
    }

    function closeResourceModal() {
        document.getElementById('resourceModal').classList.add('hidden');
    }

    function toggleVehicleFields(type) {
        const fields = document.getElementById('vehicle_fields');
        if (type === 'vehicle') {
            fields.classList.remove('hidden');
        } else {
            fields.classList.add('hidden');
        }
    }

    function editResource(data) {
        openResourceModal();
        document.getElementById('modal-title').innerText = 'แก้ไขทรัพยากร';
        document.getElementById('res_id').value = data.id;
        document.getElementById('res_type').value = data.type;
        document.getElementById('res_name').value = data.name;
        document.getElementById('res_capacity').value = data.capacity;
        document.getElementById('res_license').value = data.license_plate || '';
        document.getElementById('res_status').value = data.status;
        document.getElementById('res_desc').value = data.description || '';
        toggleVehicleFields(data.type);
    }

    function saveResource() {
        const formData = new FormData(document.getElementById('resourceForm'));
        const isEdit = document.getElementById('res_id').value !== '';
        const url = isEdit ? '<?= url('/adminBooking/updateResource') ?>' : '<?= url('/adminBooking/storeResource') ?>';

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.success) {
                    Swal.fire({
                        title: 'สำเร็จ',
                        text: 'บันทึกข้อมูลเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonColor: '#3b82f6'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        title: 'ผิดพลาด',
                        text: data.message || 'ไม่สามารถบันทึกได้',
                        icon: 'error',
                        confirmButtonColor: '#3b82f6'
                    });
                }
            }
        });
    }

    function deleteResource(id) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลนี้จะถูกลบถาวรและไม่สามารถเรียกคืนได้",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= url('/adminBooking/deleteResource') ?>', {
                    id: id,
                    csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
                }, function (data) {
                    if (data.success) {
                        Swal.fire('ลบแล้ว!', 'ข้อมูลถูกลบเรียบร้อยแล้ว', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('ผิดพลาด', data.message || 'ไม่สามารถลบได้', 'error');
                    }
                });
            }
        });
    }
</script>