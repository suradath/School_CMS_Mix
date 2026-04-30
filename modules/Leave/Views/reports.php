<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-2xl font-bold text-slate-800 heading-font">รายงานสรุปการลา</h3>
        <p class="text-sm text-slate-500 mt-1">ภาพรวมสถิติการลาของบุคลากรในปีงบประมาณ <?= date('Y') + 543 ?></p>
    </div>
    <div class="flex space-x-2 no-print">
        <button onclick="window.print()" class="bg-white border border-gray-200 text-slate-600 px-4 py-2 rounded-xl font-bold text-xs hover:bg-slate-50 transition-all flex items-center shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            พิมพ์รายงานทั้งหมด
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Chart 1: Leave by Type -->
    <div class="lg:col-span-1 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <h4 class="text-sm font-bold text-slate-800 mb-6 uppercase tracking-widest">สัดส่วนประเภทการลา</h4>
        <canvas id="typeChart" height="300"></canvas>
    </div>

    <!-- Chart 2: Monthly Trend -->
    <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <h4 class="text-sm font-bold text-slate-800 mb-6 uppercase tracking-widest">แนวโน้มการลาในแต่ละเดือน</h4>
        <canvas id="monthChart" height="150"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
function exportTable(tableId, sheetName) {
    if (typeof XLSX === 'undefined') {
        alert('กำลังโหลดระบบส่งออกข้อมูล... กรุณารอสักครู่แล้วลองใหม่ครับ');
        return;
    }
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.table_to_sheet(document.getElementById(tableId));
    XLSX.utils.book_append_sheet(wb, ws, sheetName);
    const fileName = sheetName + "_" + new Date().toISOString().slice(0,10) + ".xlsx";
    XLSX.writeFile(wb, fileName);
}

function exportPDF(tableId, title) {
    const table = document.getElementById(tableId).cloneNode(true);
    
    // Create print window
    const printWindow = window.open('', '_blank');
    printWindow.document.write('<html><head><title>' + title + '</title>');
    printWindow.document.write('<link href="https://cdn.tailwindcss.com" rel="stylesheet">');
    printWindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">');
    printWindow.document.write('<style>body{font-family:"Sarabun",sans-serif;padding:40px;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #e2e8f0;padding:12px;text-align:left;} th{background-color:#f8fafc;} .no-print{display:none;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<h2 class="text-2xl font-bold mb-6 text-center">' + title + '</h2>');
    printWindow.document.write('<div class="w-full">' + table.outerHTML + '</div>');
    printWindow.document.write('<div class="mt-8 text-right text-sm text-gray-400">ออกรายงานเมื่อ: ' + new Date().toLocaleString('th-TH') + '</div>');
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 1000);
}

// Data from PHP
const typeData = <?= json_encode($statsByType) ?>;
const monthData = <?= json_encode($statsByMonth) ?>;

// Type Chart
new Chart(document.getElementById('typeChart'), {
    type: 'doughnut',
    data: {
        labels: typeData.map(d => d.name),
        datasets: [{
            data: typeData.map(d => d.total),
            backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6'],
            borderWidth: 0,
            hoverOffset: 10
        }]
    },
    options: {
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, font: { weight: 'bold', size: 10 } } }
        },
        cutout: '70%'
    }
});

// Month Chart
const months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
const monthlyTotals = new Array(12).fill(0);
monthData.forEach(d => monthlyTotals[d.month - 1] = d.total);

new Chart(document.getElementById('monthChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'จำนวนวันลาสะสม',
            data: monthlyTotals,
            backgroundColor: '#3b82f6',
            borderRadius: 8,
            barThickness: 20
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { display: false } },
            x: { grid: { display: false } }
        }
    }
});
</script>

<!-- Summary per Person Table -->
<div class="mb-8">
    <div class="flex justify-between items-center mb-4 px-2">
        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-widest">สรุปวันลาสะสมรายบุคคล (<?= date('Y') + 543 ?>)</h4>
        <div class="flex space-x-2 no-print">
            <button onclick="exportTable('summaryTable', 'Leave_Summary')" class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1.5 rounded-lg font-bold text-[10px] hover:bg-emerald-100 transition-all flex items-center">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                EXCEL
            </button>
            <button onclick="exportPDF('summaryTable', 'สรุปวันลาสะสมรายบุคคล (<?= date('Y') + 543 ?>)')" class="bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1.5 rounded-lg font-bold text-[10px] hover:bg-rose-100 transition-all flex items-center">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                PDF
            </button>
        </div>
    </div>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="summaryTable" class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">บุคลากร</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ฝ่ายงาน/กลุ่มสาระฯ</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">ลาป่วย</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">ลากิจ</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">ลาพักผ่อน</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">รวมทั้งหมด</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($personnelSummary as $person): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-800"><?= $person['name'] ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-slate-500 font-medium"><?= $person['department_name'] ?></div>
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-bold text-rose-500"><?= number_format((float)$person['sick_days'], 1) ?></td>
                        <td class="px-6 py-4 text-center text-sm font-bold text-amber-500"><?= number_format((float)$person['personal_days'], 1) ?></td>
                        <td class="px-6 py-4 text-center text-sm font-bold text-emerald-500"><?= number_format((float)$person['vacation_days'], 1) ?></td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-slate-900 text-white rounded-lg text-xs font-bold"><?= number_format((float)$person['total_days'], 1) ?> วัน</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- All Requests Table -->
<div class="mb-8">
    <div class="flex justify-between items-center mb-4 px-2">
        <h4 class="text-sm font-bold text-slate-800 uppercase tracking-widest">รายการลาทั้งหมดในระบบ</h4>
        <div class="flex space-x-2 no-print">
            <button onclick="exportTable('requestsTable', 'All_Leave_Requests')" class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1.5 rounded-lg font-bold text-[10px] hover:bg-emerald-100 transition-all flex items-center">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                EXCEL
            </button>
            <button onclick="exportPDF('requestsTable', 'รายการลาทั้งหมดในระบบ')" class="bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1.5 rounded-lg font-bold text-[10px] hover:bg-rose-100 transition-all flex items-center">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                PDF
            </button>
        </div>
    </div>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="requestsTable" class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ผู้ขอลา</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ประเภท</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ช่วงเวลา</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">จำนวน</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach ($allRequests as $req): 
                        $statusColors = [
                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'rejected' => 'bg-rose-50 text-rose-600 border-rose-100',
                            'cancelled' => 'bg-slate-50 text-slate-400 border-slate-100'
                        ];
                    ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-bold text-slate-700"><?= $req['personnel_name'] ?></td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold" style="color: <?= $req['leave_type_color'] ?>"><?= $req['leave_type_name'] ?></span>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 font-medium">
                            <?= date('d/m/Y', strtotime($req['start_date'])) ?> - <?= date('d/m/Y', strtotime($req['end_date'])) ?>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-sm"><?= $req['total_days'] ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-widest border <?= $statusColors[$req['status']] ?>">
                                <?= $req['status'] ?>
                            </span>
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
        initPremiumDataTable('#summaryTable', {
            order: [[5, 'desc']]
        });
        initPremiumDataTable('#requestsTable', {
            order: [[2, 'desc']]
        });
    });
</script>

<style>
@media print {
    .no-print { display: none !important; }
    .bg-white { border: none !important; box-shadow: none !important; }
    body { background: white !important; }
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_paginate,
    .dataTables_wrapper .dataTables_info {
        display: none !important;
    }
}
</style>
