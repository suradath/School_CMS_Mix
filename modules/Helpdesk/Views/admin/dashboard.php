<div class="space-y-8">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fa fa-tasks text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">รายการแจ้งซ่อมทั้งหมด</h1>
                <p class="text-slate-500 font-medium mt-1">บริหารจัดการและติดตามสถานะงานซ่อมบำรุงในโรงเรียน</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('/admin/helpdesk/categories') ?>" class="px-6 py-3 bg-slate-50 text-slate-600 font-bold rounded-2xl hover:bg-slate-100 transition-all flex items-center gap-2 border border-slate-100">
                <i class="fa fa-tags"></i> จัดการประเภทงาน
            </a>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <?php
            $counts = [
                'pending' => 0,
                'in_progress' => 0,
                'fixed' => 0,
                'total' => count($requests)
            ];
            foreach ($requests as $r) {
                if (isset($counts[$r['status']])) $counts[$r['status']]++;
            }
        ?>
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">งานใหม่</p>
            <p class="text-3xl font-black text-amber-500"><?= $counts['pending'] ?></p>
        </div>
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">กำลังดำเนินการ</p>
            <p class="text-3xl font-black text-blue-500"><?= $counts['in_progress'] ?></p>
        </div>
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">ซ่อมเสร็จแล้ว</p>
            <p class="text-3xl font-black text-green-500"><?= $counts['fixed'] ?></p>
        </div>
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">งานทั้งหมด</p>
            <p class="text-3xl font-black text-slate-800"><?= $counts['total'] ?></p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
        <div class="p-8">
            <!-- Custom Filters -->
            <div class="flex flex-col md:flex-row gap-4 mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="flex-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">กรองตามสถานะ</label>
                    <select id="filterStatus" class="w-full rounded-xl border-slate-200 bg-white text-sm focus:ring-4 focus:ring-blue-500/10 transition-all">
                        <option value="">ทั้งหมด</option>
                        <option value="รอดำเนินการ">รอดำเนินการ</option>
                        <option value="กำลังดำเนินการ">กำลังดำเนินการ</option>
                        <option value="ซ่อมเสร็จสิ้น">ซ่อมเสร็จสิ้น</option>
                        <option value="ยกเลิกแล้ว">ยกเลิกแล้ว</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 ml-1">กรองตามประเภท</label>
                    <select id="filterCategory" class="w-full rounded-xl border-slate-200 bg-white text-sm focus:ring-4 focus:ring-blue-500/10 transition-all">
                        <option value="">ทั้งหมด</option>
                        <?php 
                        $cats = array_unique(array_column($requests, 'category_name'));
                        foreach ($cats as $c): ?>
                            <option value="<?= $c ?>"><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <table id="adminRepairsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4">รหัส/วันที่</th>
                        <th class="px-6 py-4">ผู้แจ้ง/ประเภท</th>
                        <th class="px-6 py-4">สถานที่</th>
                        <th class="px-6 py-4">สถานะ</th>
                        <th class="px-6 py-4 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $r): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">#<?= str_pad((string)$r['id'], 5, '0', STR_PAD_LEFT) ?></p>
                                <p class="text-[10px] text-slate-400 font-bold"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-700"><?= $r['reporter_name'] ?></p>
                                <p class="text-[10px] text-blue-500 font-black uppercase tracking-wider"><?= $r['category_name'] ?></p>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium"><?= $r['location'] ?></td>
                            <td class="px-6 py-4">
                                <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    switch($r['status']) {
                                        case 'pending': 
                                            $statusClass = 'bg-amber-100 text-amber-700 border-amber-200'; 
                                            $statusText = 'รอดำเนินการ';
                                            break;
                                        case 'in_progress': 
                                            $statusClass = 'bg-blue-100 text-blue-700 border-blue-200'; 
                                            $statusText = 'กำลังดำเนินการ';
                                            break;
                                        case 'fixed': 
                                            $statusClass = 'bg-green-100 text-green-700 border-green-200'; 
                                            $statusText = 'ซ่อมเสร็จสิ้น';
                                            break;
                                        case 'cancelled': 
                                            $statusClass = 'bg-red-100 text-red-700 border-red-200'; 
                                            $statusText = 'ยกเลิกแล้ว';
                                            break;
                                    }
                                ?>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center flex items-center justify-center gap-2">
                                <button onclick="manageRepair(<?= htmlspecialchars(json_encode($r)) ?>)" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition-all">
                                    <i class="fa fa-cog mr-2"></i> จัดการ
                                </button>
                                <button onclick="deleteRepair(<?= $r['id'] ?>)" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
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
    const table = initPremiumDataTable('#adminRepairsTable', {
        order: [[0, 'desc']]
    });

    // Custom filtering logic
    $('#filterStatus').on('change', function() {
        table.column(3).search(this.value).draw();
    });

    $('#filterCategory').on('change', function() {
        table.column(1).search(this.value).draw();
    });
});

function manageRepair(data) {
    let photosHtml = '';
    if (data.photos) {
        const photos = JSON.parse(data.photos);
        if (photos.length > 0) {
            photosHtml = `
                <div class="mt-4">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-2">รูปภาพประกอบ</p>
                    <div class="grid grid-cols-3 gap-2">
                        ${photos.map(p => `<img src="<?= url('') ?>/${p}" class="w-full h-24 object-cover rounded-lg border border-slate-100 cursor-pointer" onclick="window.open('<?= url('') ?>/${p}')">`).join('')}
                    </div>
                </div>
            `;
        }
    }

    Swal.fire({
        title: 'จัดการรายการแจ้งซ่อม',
        html: `
            <div class="text-left space-y-4">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-2">
                    <p class="text-sm"><strong>ผู้แจ้ง:</strong> ${data.reporter_name}</p>
                    <p class="text-sm"><strong>สถานที่:</strong> ${data.location}</p>
                    <p class="text-sm italic">"${data.description}"</p>
                    ${photosHtml}
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">อัปเดตสถานะ</label>
                    <select id="update_status" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm">
                        <option value="pending" ${data.status === 'pending' ? 'selected' : ''}>รอดำเนินการ (Pending)</option>
                        <option value="in_progress" ${data.status === 'in_progress' ? 'selected' : ''}>กำลังดำเนินการ (In Progress)</option>
                        <option value="fixed" ${data.status === 'fixed' ? 'selected' : ''}>ซ่อมเสร็จสิ้น (Fixed)</option>
                        <option value="cancelled" ${data.status === 'cancelled' ? 'selected' : ''}>ยกเลิก (Cancelled)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">หมายเหตุ/บันทึกการซ่อม</label>
                    <textarea id="update_remarks" rows="3" class="w-full rounded-xl border-slate-200 bg-slate-50 p-3 text-sm" placeholder="ระบุอะไหล่ที่ใช้ หรือข้อมูลเพิ่มเติม...">${data.remarks || ''}</textarea>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'บันทึกการเปลี่ยนแปลง',
        confirmButtonColor: '#2563eb',
        cancelButtonText: 'ยกเลิก',
        preConfirm: () => {
            return {
                status: document.getElementById('update_status').value,
                remarks: document.getElementById('update_remarks').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            updateRepairStatus(data.id, result.value.status, result.value.remarks);
        }
    });
}

function updateRepairStatus(id, status, remarks) {
    $.post('<?= url('/admin/helpdesk/update-status') ?>', {
        id: id,
        status: status,
        remarks: remarks,
        csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
    }, function(data) {
        if (data.success) {
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'อัปเดตสถานะเรียบร้อยแล้ว',
                icon: 'success',
                confirmButtonColor: '#2563eb'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'ผิดพลาด!',
                text: data.message,
                icon: 'error',
                confirmButtonColor: '#2563eb'
            });
        }
    });
}

function deleteRepair(id) {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: 'คุณแน่ใจหรือไม่ว่าต้องการลบรายการแจ้งซ่อมนี้?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'ใช่, ลบเลย',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= url('/admin/helpdesk/delete-request') ?>', {
                id: id,
                csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
            }, function(data) {
                if (data.success) {
                    Swal.fire({
                        title: 'ลบแล้ว!',
                        text: 'ลบรายการแจ้งซ่อมเรียบร้อยแล้ว',
                        icon: 'success',
                        confirmButtonColor: '#2563eb'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', 'ไม่สามารถลบข้อมูลได้', 'error');
                }
            });
        }
    });
}
</script>
