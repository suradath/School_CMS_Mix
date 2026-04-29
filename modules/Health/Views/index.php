<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 heading-font">ระบบสุขภาพและโภชนาการ</h1>
        <p class="text-gray-500 mt-1">ติดตามสถานะสุขภาพและค่าดัชนีมวลกาย (BMI) ของนักเรียน</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="<?= url('/students') ?>" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-colors shadow-sm inline-flex items-center">
            <i class="fa fa-users mr-2"></i> ข้อมูลนักเรียน
        </a>
    </div>
</div>

<!-- Filters & Summary -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    <!-- Filters Card -->
    <div class="lg:col-span-1 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <h3 class="text-sm font-bold text-gray-400 uppercase mb-4 tracking-wider">ตัวกรองข้อมูล</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">ระดับชั้น</label>
                <select id="filterClass" class="w-full bg-gray-50 border border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary focus:border-primary block p-2.5 transition-all">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($classes as $c): ?>
                        <option value="<?= htmlspecialchars($c['class_level']) ?>"><?= htmlspecialchars($c['class_level']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">ห้อง</label>
                <input type="number" id="filterRoom" placeholder="เช่น 1" class="w-full bg-gray-50 border border-gray-100 text-gray-900 text-sm rounded-xl focus:ring-primary focus:border-primary block p-2.5 transition-all">
            </div>
            <button id="refreshData" class="w-full py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-colors shadow-sm shadow-primary/20 flex items-center justify-center mt-2">
                <i class="fa fa-refresh mr-2"></i> อัปเดตข้อมูล
            </button>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-sm font-bold text-gray-400 uppercase mb-4 tracking-wider">สัดส่วนภาวะโภชนาการ</h3>
            <div class="relative h-64">
                <canvas id="bmiDoughnutChart"></canvas>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <h3 class="text-sm font-bold text-gray-400 uppercase mb-4 tracking-wider">เปรียบเทียบตามระดับชั้น</h3>
            <div class="relative h-64">
                <canvas id="classBarChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Student List Table -->
<div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900 heading-font"><i class="fa fa-list text-primary mr-2"></i>รายชื่อนักเรียนและค่า BMI</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table id="healthDataTable" class="w-full text-sm text-left">
            <thead class="text-xs text-gray-400 uppercase bg-gray-50/50 rounded-xl">
                <tr>
                    <th class="px-4 py-4 font-bold">รหัสนักเรียน</th>
                    <th class="px-4 py-4 font-bold">ชื่อ-นามสกุล</th>
                    <th class="px-4 py-4 font-bold">ชั้น/ห้อง</th>
                    <th class="px-4 py-4 font-bold text-center">น้ำหนัก</th>
                    <th class="px-4 py-4 font-bold text-center">ส่วนสูง</th>
                    <th class="px-4 py-4 font-bold text-center">BMI</th>
                    <th class="px-4 py-4 font-bold text-center">สถานะ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <!-- Loaded via AJAX -->
            </tbody>
        </table>
    </div>
</div>

<style>
/* DataTables Premium Styling - Synced with other modules */
.dataTables_wrapper .dataTables_length select {
    padding-right: 2.5rem;
    border-radius: 0.75rem;
    border-color: #f1f5f9;
    background-color: #f8fafc;
    font-size: 0.875rem;
}
.dataTables_wrapper .dataTables_filter input {
    border-radius: 0.75rem;
    border-color: #f1f5f9;
    background-color: #f8fafc;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    margin-left: 0.5rem;
}
.dataTables_wrapper .dataTables_info {
    font-size: 0.75rem;
    color: #64748b;
    padding-top: 1.5rem;
}
.dataTables_wrapper .dataTables_paginate {
    padding-top: 1.5rem;
    display: flex;
    gap: 0.25rem;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 0.75rem !important;
    border: 1px solid #f1f5f9 !important;
    background: #fff !important;
    color: #475569 !important;
    padding: 0.4rem 0.8rem !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    transition: all 0.2s;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #1e293b !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--tw-color-primary, #1d4ed8) !important;
    color: white !important;
    border-color: var(--tw-color-primary, #1d4ed8) !important;
    box-shadow: 0 4px 6px -1px rgba(29, 78, 216, 0.2);
}
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
/* Increase Row Height */
#healthDataTable tbody td {
    padding-top: 1.25rem !important;
    padding-bottom: 1.25rem !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const $ = jQuery;
    let doughnutChart, barChart, dataTable;

    // Initialize Charts
    function initCharts(data) {
        const dCtx = document.getElementById('bmiDoughnutChart').getContext('2d');
        if (doughnutChart) doughnutChart.destroy();
        doughnutChart = new Chart(dCtx, {
            type: 'doughnut',
            data: {
                labels: data.distribution.labels,
                datasets: [{
                    data: data.distribution.values,
                    backgroundColor: data.distribution.colors,
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11, family: "'K2D', sans-serif" } } }
                },
                cutout: '65%'
            }
        });

        const bCtx = document.getElementById('classBarChart').getContext('2d');
        if (barChart) barChart.destroy();
        barChart = new Chart(bCtx, {
            type: 'bar',
            data: {
                labels: data.class_comparison.labels,
                datasets: [
                    { label: 'สมส่วน', data: data.class_comparison.normal, backgroundColor: '#10b981', borderRadius: 4 },
                    { label: 'ไม่ตามเกณฑ์', data: data.class_comparison.at_risk, backgroundColor: '#f87171', borderRadius: 4 }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11, family: "'K2D', sans-serif" } } }
                },
                scales: {
                    x: { stacked: true, grid: { display: false } },
                    y: { stacked: true, beginAtZero: true, grid: { borderDash: [4, 4] } }
                }
            }
        });
    }

    // Load Data
    function loadHealthData() {
        const classLevel = $('#filterClass').val();
        const room = $('#filterRoom').val();

        $.ajax({
            url: '<?= url('/health/data') ?>',
            data: { class_level: classLevel, room_number: room },
            dataType: 'json',
            success: function(data) {
                initCharts(data);
                
                // Refresh DataTable
                if (dataTable) {
                    dataTable.clear().rows.add(data.students).draw();
                } else {
                    dataTable = $('#healthDataTable').DataTable({
                        data: data.students,
                        pageLength: 20,
                        lengthMenu: [10, 20, 50, 100],
                        columns: [
                            { data: 'student_code' },
                            { data: null, render: function(d) { return d.title + d.first_name + ' ' + d.last_name; }, className: 'font-bold text-gray-900' },
                            { data: null, render: function(d) { return d.class_level + '/' + d.room_number; } },
                            { data: 'weight', className: 'text-center' },
                            { data: 'height', className: 'text-center' },
                            { data: 'bmi', className: 'text-center font-bold text-primary' },
                            { data: null, render: function(d) { 
                                return `<span class="px-3 py-1 rounded-lg text-xs font-bold ${d.status_bg}">${d.status}</span>`;
                            }, className: 'text-center' }
                        ],
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/th.json',
                            search: "_INPUT_",
                            searchPlaceholder: "ค้นหาชื่อหรือรหัส..."
                        },
                        dom: 'frtip'
                    });
                }
            },
            error: function() {
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถดึงข้อมูลสุขภาพได้', 'error');
            }
        });
    }

    // Initial Load
    loadHealthData();

    // Refresh on button click
    $('#refreshData').on('click', loadHealthData);
});
</script>
