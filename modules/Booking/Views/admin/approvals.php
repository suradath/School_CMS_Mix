<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800 heading-font">จัดการคำขอจอง</h2>
            <p class="text-slate-500 font-medium">รายการคำขอจองห้องประชุมและยานพาหนะทั้งหมด</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= url('/adminBooking/resources') ?>" class="inline-flex items-center px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-2xl hover:bg-slate-50 transition-all shadow-sm">
                <i class="fa fa-cog mr-2"></i> จัดการทรัพยากร
            </a>
        </div>
    </div>
</div>

<style>
    /* Force Swal button visibility */
    .swal2-confirm {
        background-color: #2563eb !important;
        color: white !important;
    }
    .swal2-cancel {
        background-color: #6b7280 !important;
        color: white !important;
    }
    .swal2-deny {
        background-color: #ef4444 !important;
        color: white !important;
    }
</style>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-8">
            <table id="approvalsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-4 font-bold text-gray-700">วันที่จอง</th>
                        <th class="px-4 py-4 font-bold text-gray-700">ทรัพยากร</th>
                        <th class="px-4 py-4 font-bold text-gray-700">ผู้จอง</th>
                        <th class="px-4 py-4 font-bold text-gray-700">วัน-เวลาที่ใช้</th>
                        <th class="px-4 py-4 font-bold text-gray-700">สถานะ</th>
                        <th class="px-4 py-4 font-bold text-gray-700 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($bookings as $b): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-4 text-sm text-gray-600">
                            <?= date('d/m/Y H:i', strtotime($b['created_at'])) ?>
                        </td>
                        <td class="px-4 py-4">
                            <div class="font-medium text-gray-900"><?= htmlspecialchars($b['resource_name']) ?></div>
                            <div class="text-xs text-gray-500 uppercase"><?= $b['resource_type'] === 'room' ? 'ห้องประชุม' : 'ยานพาหนะ' ?></div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                            <?= htmlspecialchars($b['user_name']) ?>
                        </td>
                        <td class="px-4 py-4 text-sm">
                            <div class="text-gray-800"><?= date('d/m/Y H:i', strtotime($b['start_time'])) ?></div>
                            <div class="text-gray-400">ถึง <?= date('d/m/Y H:i', strtotime($b['end_time'])) ?></div>
                        </td>
                        <td class="px-4 py-4">
                            <?php if ($b['status'] === 'pending'): ?>
                                <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-medium">รออนุมัติ</span>
                            <?php elseif ($b['status'] === 'approved'): ?>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">อนุมัติแล้ว</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium">ไม่อนุมัติ</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <?php if ($b['status'] === 'pending'): ?>
                                <div class="flex justify-center gap-2">
                                    <button onclick="updateStatus(<?= $b['id'] ?>, 'approved')" class="bg-green-500 hover:bg-green-600 text-white p-2 rounded shadow transition" title="อนุมัติ">
                                        <i class="fa fa-check"></i>
                                    </button>
                                    <button onclick="promptReject(<?= $b['id'] ?>)" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded shadow transition" title="ไม่อนุมัติ">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            <?php else: ?>
                                <button onclick="viewDetails(<?= htmlspecialchars(json_encode($b)) ?>)" class="text-blue-600 hover:underline text-sm font-medium">
                                    ดูรายละเอียด
                                </button>
                            <?php endif; ?>
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
    initPremiumDataTable('#approvalsTable', {
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [5] }
        ]
    });
});

function updateStatus(id, status, reason = '') {
    $.post('<?= url('/adminBooking/updateStatus') ?>', {
        id: id,
        status: status,
        rejection_reason: reason,
        csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
    }, function(data) {
        if (data.success) {
            Swal.fire({
                title: 'สำเร็จ',
                text: 'ดำเนินการเรียบร้อยแล้ว',
                icon: 'success',
        if (data.success) {
            Swal.fire({
                title: 'สำเร็จ',
                text: 'ดำเนินการเรียบร้อยแล้ว',
                icon: 'success',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#2563eb'
            }).then(() => location.reload());
        } else {
            Swal.fire({
                title: 'ผิดพลาด',
                text: data.message || 'ไม่สามารถดำเนินการได้',
                icon: 'error',
                confirmButtonText: 'รับทราบ',
                confirmButtonColor: '#2563eb'
            });
        }
    });
}

function promptReject(id) {
    Swal.fire({
        title: 'ระบุเหตุผลการไม่อนุมัติ',
        input: 'textarea',
        inputPlaceholder: 'ตัวอย่าง: ทรัพยากรไม่ว่าง หรือข้อมูลไม่ครบถ้วน...',
        showCancelButton: true,
        confirmButtonText: 'ยืนยันการไม่อนุมัติ',
        confirmButtonColor: '#dc2626',
        cancelButtonText: 'ยกเลิก',
        cancelButtonColor: '#6b7280',
        inputValidator: (value) => {
            if (!value) return 'กรุณาระบุเหตุผลเพื่อความชัดเจน';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            updateStatus(id, 'rejected', result.value);
        }
    });
}

function viewDetails(data) {
    Swal.fire({
        title: 'รายละเอียดการจอง',
        html: `
            <div class="text-left text-sm space-y-3 mt-4">
                <p><strong>หัวข้อ:</strong> ${data.title}</p>
                <p><strong>รายละเอียด:</strong> ${data.details || '-'}</p>
                <p><strong>จำนวนผู้เข้าร่วม:</strong> ${data.participants_count} คน</p>
                ${data.rejection_reason ? `<p class="text-red-600"><strong>เหตุผลที่ไม่อนุมัติ:</strong> ${data.rejection_reason}</p>` : ''}
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#3b82f6'
    });
}
</script>
