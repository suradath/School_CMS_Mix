<?php
// Notification Handling
if (isset($_SESSION['success'])) {
    echo "<div class='p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50'>{$_SESSION['success']}</div>";
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50'>{$_SESSION['error']}</div>";
    unset($_SESSION['error']);
}
?>

<!-- Actions Menu -->
<div class="flex justify-end mb-6 space-x-3">
    <a href="<?= url('/students/classroom') ?>" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold hover:bg-gray-50 transition-colors shadow-sm inline-flex items-center">
        <i class="fa fa-users mr-2"></i> ข้อมูลรายห้อง
    </a>
    <?php if (\Core\Security::checkRole(['admin', 'director'])): ?>
    <a href="<?= url('/students/import') ?>" class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-colors shadow-sm shadow-primary/30 inline-flex items-center">
        <i class="fa fa-upload mr-2"></i> นำเข้าข้อมูล (DMC)
    </a>
    <?php if (\Core\Security::checkRole('admin')): ?>
    <button onclick="confirmClearData()" class="px-5 py-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl font-bold hover:bg-red-100 transition-colors shadow-sm inline-flex items-center">
        <i class="fa fa-trash mr-2"></i> ล้างข้อมูลทั้งหมด
    </button>
    <form id="clearDataForm" action="<?= url('/students/clear') ?>" method="POST" class="hidden">
        <?= \Core\Security::csrf_field() ?>
    </form>
    <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-8 border border-white shadow-xl shadow-slate-200/50 flex items-center justify-between group hover:border-primary/30 transition-all duration-500">
        <div>
            <p class="text-xs font-black text-slate-400 mb-2 uppercase tracking-[0.2em]">นักเรียนทั้งหมด</p>
            <h3 class="text-4xl font-extrabold text-slate-900 heading-font"><?= number_format($stats['total']) ?></h3>
        </div>
        <div class="w-16 h-16 bg-gradient-to-br from-primary to-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-primary/30 group-hover:scale-110 transition-transform">
            <i class="fa fa-users"></i>
        </div>
    </div>
    
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-8 border border-white shadow-xl shadow-slate-200/50 flex items-center justify-between group hover:border-blue-300 transition-all duration-500">
        <div>
            <p class="text-xs font-black text-slate-400 mb-2 uppercase tracking-[0.2em]">นักเรียนชาย</p>
            <h3 class="text-4xl font-extrabold text-blue-600 heading-font"><?= number_format($stats['gender']['Male']) ?></h3>
        </div>
        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-700 text-white rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
            <i class="fa fa-mars"></i>
        </div>
    </div>
    
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-8 border border-white shadow-xl shadow-slate-200/50 flex items-center justify-between group hover:border-pink-300 transition-all duration-500">
        <div>
            <p class="text-xs font-black text-slate-400 mb-2 uppercase tracking-[0.2em]">นักเรียนหญิง</p>
            <h3 class="text-4xl font-extrabold text-pink-600 heading-font"><?= number_format($stats['gender']['Female']) ?></h3>
        </div>
        <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-rose-600 text-white rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-pink-500/30 group-hover:scale-110 transition-transform">
            <i class="fa fa-venus"></i>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl shadow-slate-100/50">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-lg font-black text-slate-800 heading-font uppercase tracking-tight">สัดส่วนเพศ</h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-full border border-slate-100">Gender Ratio</span>
        </div>
        <div class="relative h-72">
            <canvas id="genderChart"></canvas>
        </div>
    </div>
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl shadow-slate-100/50">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-lg font-black text-slate-800 heading-font uppercase tracking-tight">จำนวนนักเรียนรายระดับชั้น</h3>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-full border border-slate-100">Class Levels</span>
        </div>
        <div class="relative h-72">
            <canvas id="classChart"></canvas>
        </div>
    </div>
</div>

<!-- Additional Demographics Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
    <!-- Blood Type -->
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition-shadow">
        <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-xl mb-6">
            <i class="fa fa-tint"></i>
        </div>
        <h3 class="text-lg font-black text-slate-800 mb-6 heading-font">กลุ่มเลือด</h3>
        <div class="space-y-4">
            <?php foreach ($stats['blood_type'] as $type => $count): 
                $pct = ($stats['total'] > 0) ? ($count / $stats['total']) * 100 : 0;
            ?>
            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs font-black text-slate-600 uppercase tracking-wider"><?= htmlspecialchars($type) ?></span>
                    <span class="text-xs font-black text-primary"><?= number_format($count) ?></span>
                </div>
                <div class="w-full bg-slate-50 rounded-full h-1.5 border border-slate-100">
                    <div class="bg-rose-500 h-1.5 rounded-full" style="width: <?= $pct ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Religion -->
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition-shadow">
        <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-xl mb-6">
            <i class="fa fa-star"></i>
        </div>
        <h3 class="text-lg font-black text-slate-800 mb-6 heading-font">ศาสนา</h3>
        <div class="space-y-4">
            <?php foreach ($stats['religion'] as $rel => $count): 
                $pct = ($stats['total'] > 0) ? ($count / $stats['total']) * 100 : 0;
            ?>
            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs font-black text-slate-600 uppercase tracking-wider"><?= htmlspecialchars($rel) ?></span>
                    <span class="text-xs font-black text-primary"><?= number_format($count) ?></span>
                </div>
                <div class="w-full bg-slate-50 rounded-full h-1.5 border border-slate-100">
                    <div class="bg-amber-400 h-1.5 rounded-full" style="width: <?= $pct ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Nationality -->
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-lg transition-shadow">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center text-xl mb-6">
            <i class="fa fa-flag"></i>
        </div>
        <h3 class="text-lg font-black text-slate-800 mb-6 heading-font">สัญชาติ</h3>
        <div class="space-y-4">
            <?php foreach ($stats['nationality'] as $nat => $count): 
                $pct = ($stats['total'] > 0) ? ($count / $stats['total']) * 100 : 0;
            ?>
            <div>
                <div class="flex justify-between items-center mb-1">
                    <span class="text-xs font-black text-slate-600 uppercase tracking-wider"><?= htmlspecialchars($nat) ?></span>
                    <span class="text-xs font-black text-primary"><?= number_format($count) ?></span>
                </div>
                <div class="w-full bg-slate-50 rounded-full h-1.5 border border-slate-100">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: <?= $pct ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- New Stats Row: Occupations & Disadvantage -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl shadow-slate-100/50">
        <h3 class="text-lg font-black text-slate-800 mb-8 heading-font tracking-tight">อาชีพผู้ปกครอง (Top 10)</h3>
        <div class="relative h-72">
            <canvas id="occupationChart"></canvas>
        </div>
    </div>
    <div class="bg-white p-8 rounded-[2rem] border border-gray-100 shadow-xl shadow-slate-100/50">
        <h3 class="text-lg font-black text-slate-800 mb-8 heading-font tracking-tight">สถานะความด้อยโอกาส</h3>
        <div class="relative h-72">
            <canvas id="disadvantageChart"></canvas>
        </div>
    </div>
</div>

<!-- Location Table -->
<div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h3 class="text-lg font-bold text-gray-800 heading-font"><i class="fa fa-map-marker text-green-500 mr-2"></i>จำนวนนักเรียนแยกตามพื้นที่</h3>
    </div>

    <div class="overflow-x-auto">
        <table id="locationDataTable" class="w-full text-sm text-left">
            <thead class="text-xs text-gray-400 uppercase bg-gray-50/50 rounded-xl">
                <tr>
                    <th class="px-4 py-3 font-bold">ลำดับที่</th>
                    <th class="px-4 py-3 font-bold">จังหวัด</th>
                    <th class="px-4 py-3 font-bold">อำเภอ</th>
                    <th class="px-4 py-3 font-bold">ตำบล</th>
                    <th class="px-4 py-3 font-bold text-center">จำนวนนักเรียน</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($stats['locations'] as $index => $loc): ?>
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-4 py-3 font-medium text-gray-900 text-center"><?= $index + 1 ?></td>
                    <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($loc['province'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($loc['district'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-gray-600"><?= htmlspecialchars($loc['sub_district'] ?? '-') ?></td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-3 py-1 bg-primary/5 text-primary rounded-lg font-bold">
                            <?= number_format($loc['student_count']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* DataTables Premium Styling */
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
.dataTables_wrapper .dataTables_paginate .paginate_button.previous,
.dataTables_wrapper .dataTables_paginate .paginate_button.next {
    padding-left: 1rem !important;
    padding-right: 1rem !important;
}
</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fonts configuration for Chart.js
    Chart.defaults.font.family = "'K2D', 'Sarabun', sans-serif";
    
    // Gender Chart (Pie)
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: ['ชาย', 'หญิง'],
            datasets: [{
                data: [<?= $stats['gender']['Male'] ?>, <?= $stats['gender']['Female'] ?>],
                backgroundColor: ['#3b82f6', '#ec4899'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 14 } } }
            },
            cutout: '70%'
        }
    });

    // Class Level Chart (Bar)
    const classCtx = document.getElementById('classChart').getContext('2d');
    <?php 
        $classLabels = array_keys($stats['classes']);
        $classData = array_values($stats['classes']);
    ?>
    new Chart(classCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($classLabels) ?>,
            datasets: [{
                label: 'จำนวนนักเรียน (คน)',
                data: <?= json_encode($classData) ?>,
                backgroundColor: '<?= \Core\Database::getSetting('primary_color', '#1d4ed8') ?>',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [4, 4] }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Occupation Chart (Horizontal Bar)
    const occCtx = document.getElementById('occupationChart').getContext('2d');
    <?php 
        $occLabels = array_keys($stats['parent_occupations']);
        $occData = array_values($stats['parent_occupations']);
    ?>
    new Chart(occCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($occLabels) ?>,
            datasets: [{
                label: 'จำนวน (คน)',
                data: <?= json_encode($occData) ?>,
                backgroundColor: '#3b82f6',
                borderRadius: 6,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: { display: false } },
                y: { grid: { display: false } }
            }
        }
    });

    // Disadvantage Chart (Pie)
    const disCtx = document.getElementById('disadvantageChart').getContext('2d');
    <?php 
        $disLabels = array_keys($stats['disadvantage']);
        $disData = array_values($stats['disadvantage']);
    ?>
    new Chart(disCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($disLabels) ?>,
            datasets: [{
                data: <?= json_encode($disData) ?>,
                backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#ec4899'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
            }
        }
    });

    // Location DataTable
    initPremiumDataTable('#locationDataTable', {
        pageLength: 20,
        order: [[4, 'desc']], // Sort by student count (column index 4)
        columnDefs: [
            { orderable: false, targets: 0 } // Disable sorting for sequence number
        ]
    });
});

function confirmClearData() {
    Swal.fire({
        title: 'ยืนยันการล้างข้อมูล?',
        text: "ข้อมูลนักเรียน ที่อยู่ และผู้ปกครองทั้งหมดจะถูกลบออกอย่างถาวร!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ใช่, ลบทั้งหมด',
        cancelButtonText: 'ยกเลิก',
        customClass: {
            confirmButton: 'px-6 py-3 bg-red-600 text-white rounded-xl font-bold text-sm mx-2 shadow-lg shadow-red-500/30 hover:bg-red-700 transition-colors',
            cancelButton: 'px-6 py-3 bg-slate-200 text-slate-700 rounded-xl font-bold text-sm mx-2 hover:bg-slate-300 transition-colors'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('clearDataForm').submit();
        }
    });
}
</script>
