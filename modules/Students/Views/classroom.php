<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
    <a href="<?= url('/students') ?>" class="text-sm text-gray-500 hover:text-primary transition-colors font-bold">
        <i class="fa fa-arrow-left mr-1"></i> กลับไปหน้าภาพรวม
    </a>
</div>

<!-- Filters -->
<div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
    <form method="GET" action="<?= url('/students/classroom') ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">ค้นหา</label>
            <input type="text" name="search" value="<?= htmlspecialchars($filters['search']) ?>" placeholder="ชื่อ, นามสกุล, รหัสนักเรียน" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary focus:border-primary block p-2.5">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">ระดับชั้น</label>
            <select name="class_level" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary focus:border-primary block p-2.5">
                <option value="">ทั้งหมด</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= htmlspecialchars($c['class_level']) ?>" <?= $filters['class_level'] === $c['class_level'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['class_level']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 mb-2 uppercase">ห้องเรียน</label>
            <input type="number" name="room_number" value="<?= htmlspecialchars($filters['room_number']) ?>" placeholder="เช่น 1" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-primary focus:border-primary block p-2.5">
        </div>
        <div>
            <button type="submit" class="w-full text-white bg-primary hover:bg-primary-dark font-bold rounded-xl text-sm px-5 py-2.5 text-center transition-colors shadow-sm">
                <i class="fa fa-search mr-1"></i> กรองข้อมูล
            </button>
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
    <div class="overflow-x-auto">
        <table id="studentDataTable" class="w-full text-sm text-left">
            <thead class="text-xs text-gray-400 uppercase bg-gray-50/50 rounded-xl">
                <tr>
                    <th class="px-4 py-4 font-bold">รหัสนักเรียน</th>
                    <th class="px-4 py-4 font-bold">ชื่อ-นามสกุล</th>
                    <th class="px-4 py-4 font-bold">ระดับชั้น/ห้อง</th>
                    <th class="px-4 py-4 font-bold">เพศ</th>
                    <th class="px-4 py-4 font-bold text-right no-sort">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($students as $s): ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-4 font-medium text-gray-900">
                            <?= htmlspecialchars($s['student_code']) ?>
                        </td>
                        <td class="px-4 py-4 text-gray-700 font-bold">
                            <?= htmlspecialchars($s['title'] . $s['first_name'] . ' ' . $s['last_name']) ?>
                        </td>
                        <td class="px-4 py-4">
                            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-lg border border-blue-100">
                                <?= htmlspecialchars($s['class_level']) ?>/<?= htmlspecialchars((string)$s['room_number']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <?= $s['gender'] === 'ช' ? '<span class="text-blue-500 font-bold"><i class="fa fa-male mr-1"></i>ชาย</span>' : '<span class="text-pink-500 font-bold"><i class="fa fa-female mr-1"></i>หญิง</span>' ?>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <a href="<?= url('/students/profile/' . $s['id']) ?>" class="inline-flex items-center px-3 py-1.5 bg-primary/10 text-primary rounded-lg font-bold text-xs hover:bg-primary hover:text-white transition-all">
                                <i class="fa fa-eye mr-1.5"></i> ดูโปรไฟล์
                            </a>
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#studentDataTable').DataTable({
        pageLength: 20,
        lengthMenu: [10, 20, 50, 100],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/th.json',
            search: "_INPUT_",
            searchPlaceholder: "ค้นหาในหน้านี้..."
        },
        columnDefs: [
            { orderable: false, targets: 'no-sort' }
        ],
        order: [[0, 'asc']] // Default sort by Student Code
    });
});
</script>
