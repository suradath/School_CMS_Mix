<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800 heading-font">การจองของฉัน</h2>
            <p class="text-slate-500 font-medium">ประวัติและสถานะการจองทรัพยากรของคุณ</p>
        </div>
        <a href="<?= url('/booking') ?>" class="inline-flex items-center px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-2xl hover:bg-slate-50 transition-all shadow-sm">
            <i class="fa fa-calendar mr-2"></i> กลับไปยังปฏิทิน
        </a>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-8">
            <table id="myBookingsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-4 font-bold text-gray-700">วันที่ทำรายการ</th>
                        <th class="px-4 py-4 font-bold text-gray-700">ทรัพยากร</th>
                        <th class="px-4 py-4 font-bold text-gray-700">วัน-เวลาที่ใช้</th>
                        <th class="px-4 py-4 font-bold text-gray-700">หัวข้อ/วัตถุประสงค์</th>
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
                        <td class="px-4 py-4 text-sm">
                            <div class="text-gray-800"><?= date('d/m/Y H:i', strtotime($b['start_time'])) ?></div>
                            <div class="text-gray-400">ถึง <?= date('d/m/Y H:i', strtotime($b['end_time'])) ?></div>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-700">
                            <?= htmlspecialchars($b['title']) ?>
                        </td>
                        <td class="px-4 py-4">
                            <?php if ($b['status'] === 'pending'): ?>
                                <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-medium">รออนุมัติ</span>
                            <?php elseif ($b['status'] === 'approved'): ?>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">อนุมัติแล้ว</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-medium" title="<?= htmlspecialchars($b['rejection_reason'] ?? '') ?>">ไม่อนุมัติ</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <button onclick="viewDetails(<?= htmlspecialchars(json_encode($b)) ?>)" class="text-blue-600 hover:underline text-sm font-medium">
                                ดูรายละเอียด
                            </button>
                            <?php if ($b['status'] === 'pending'): ?>
                                <button onclick="cancelBooking(<?= $b['id'] ?>)" class="text-red-600 hover:underline text-sm font-medium ml-3">
                                    ยกเลิก
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
    initPremiumDataTable('#myBookingsTable', {
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [5] }
        ]
    });
});

function viewDetails(data) {
    let reasonHtml = '';
    if (data.status === 'rejected' && data.rejection_reason) {
        reasonHtml = `<div class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg border border-red-100">
            <strong>เหตุผลที่ไม่อนุมัติ:</strong><br>${data.rejection_reason}
        </div>`;
    }

    Swal.fire({
        title: 'รายละเอียดการจอง',
        html: `
            <div class="text-left text-sm space-y-2 mt-4">
                <p><strong>หัวข้อ:</strong> ${data.title}</p>
                <p><strong>รายละเอียด:</strong> ${data.details || '-'}</p>
                <p><strong>ทรัพยากร:</strong> ${data.resource_name}</p>
                <p><strong>จำนวนผู้เข้าร่วม:</strong> ${data.participants_count} คน</p>
                <p><strong>เวลา:</strong> ${new Date(data.start_time).toLocaleString('th-TH')} - ${new Date(data.end_time).toLocaleString('th-TH')}</p>
                ${reasonHtml}
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#3b82f6'
    });
}

function cancelBooking(id) {
    Swal.fire({
        title: 'ยกเลิกการจอง?',
        text: "คุณแน่ใจหรือไม่ว่าต้องการยกเลิกคำขอจองนี้",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ยกเลิกเลย',
        cancelButtonText: 'กลับ'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?= url('/booking/cancel') ?>', {
                id: id,
                csrf_token: '<?= $_SESSION['csrf_token'] ?? '' ?>'
            }, function(data) {
                if (data.success) {
                    Swal.fire('สำเร็จ', 'ยกเลิกการจองเรียบร้อยแล้ว', 'success').then(() => location.reload());
                } else {
                    Swal.fire('ผิดพลาด', data.message || 'ไม่สามารถยกเลิกได้', 'error');
                }
            });
        }
    });
}
</script>
