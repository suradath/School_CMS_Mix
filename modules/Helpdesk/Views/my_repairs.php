<div class="space-y-8">
    <!-- Header Section -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                <i class="fa fa-history text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">ประวัติการแจ้งซ่อม</h1>
                <p class="text-slate-500 font-medium mt-1">ติดตามสถานะงานซ่อมที่คุณส่งคำขอไว้</p>
            </div>
        </div>
        <a href="<?= url('/helpdesk') ?>" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all flex items-center gap-2">
            <i class="fa fa-plus"></i> แจ้งซ่อมใหม่
        </a>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden">
        <div class="p-8">
            <table id="myRepairsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4">วันที่แจ้ง</th>
                        <th class="px-6 py-4">ประเภทงาน</th>
                        <th class="px-6 py-4">สถานที่</th>
                        <th class="px-6 py-4">รายละเอียด</th>
                        <th class="px-6 py-4 text-center">สถานะ</th>
                        <th class="px-6 py-4 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($repairs as $r): ?>
                        <tr>
                            <td class="px-6 py-4 font-bold text-slate-900"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-black uppercase rounded-full tracking-wider border border-slate-200">
                                    <?= $r['category_name'] ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600 font-medium"><?= $r['location'] ?></td>
                            <td class="px-6 py-4 text-slate-500 text-xs italic">
                                <?= mb_strimwidth($r['description'], 0, 50, '...') ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php
                                    $statusClass = 'bg-slate-100 text-slate-500';
                                    $statusText = $r['status'];
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
                            <td class="px-6 py-4 text-center">
                                <button onclick="viewRepairDetails(<?= htmlspecialchars(json_encode($r)) ?>)" class="p-2 bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                    <i class="fa fa-search"></i>
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
    initPremiumDataTable('#myRepairsTable', {
        order: [[0, 'desc']]
    });
});

function viewRepairDetails(data) {
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
        title: 'รายละเอียดการแจ้งซ่อม',
        html: `
            <div class="text-left text-sm space-y-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">ประเภท</p>
                        <p class="font-bold text-slate-800">${data.category_name}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">สถานะ</p>
                        <p class="font-bold ${data.status === 'fixed' ? 'text-green-600' : 'text-amber-600'}">${data.status === 'fixed' ? 'ซ่อมเสร็จสิ้น' : 'อยู่ระหว่างดำเนินการ'}</p>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">สถานที่</p>
                    <p class="text-slate-700 font-medium">${data.location}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">รายละเอียด</p>
                    <p class="text-slate-600">${data.description}</p>
                </div>
                ${data.remarks ? `
                <div class="p-3 bg-blue-50 text-blue-800 rounded-xl border border-blue-100">
                    <p class="text-[10px] font-bold uppercase mb-1 opacity-70">หมายเหตุจากเจ้าหน้าที่</p>
                    <p>${data.remarks}</p>
                </div>
                ` : ''}
                ${photosHtml}
            </div>
        `,
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#2563eb'
    });
}
</script>
