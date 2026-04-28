<div class="p-8 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
    <div>
        <h3 class="text-lg font-bold text-slate-800 heading-font">
            สรุปสถิติห้อง <?= $level ?>/<?= $room ?>
        </h3>
        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">อ้างอิงจากฐานข้อมูลล่าสุด</p>
    </div>
    <div id="dt-buttons" class="flex space-x-2">
        <!-- DataTables buttons will be injected here if needed, or we use default -->
    </div>
</div>

<div class="p-6">
    <div class="overflow-x-auto">
        <table id="report-table" class="w-full text-sm text-left">
            <thead class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-gray-100">
                <tr>
                    <th class="px-4 py-4 text-center">#</th>
                    <th class="px-4 py-4">รหัส</th>
                    <th class="px-4 py-4">ชื่อ-นามสกุล</th>
                    <th class="px-4 py-4 text-center">คาบรวม</th>
                    <th class="px-4 py-4 text-center text-green-600">มาเรียน</th>
                    <th class="px-4 py-4 text-center text-orange-600">มาสาย</th>
                    <th class="px-4 py-4 text-center text-red-700">ขาด</th>
                    <th class="px-4 py-4 text-center text-blue-600">ลา</th>
                    <th class="px-4 py-4 text-center">ร้อยละ</th>
                    <th class="px-4 py-4 text-center">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($reportData as $index => $row): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-4 text-center font-medium text-slate-400"><?= $index + 1 ?></td>
                    <td class="px-4 py-4 font-bold text-slate-700 outfit"><?= $row['student_code'] ?></td>
                    <td class="px-4 py-4 font-bold text-slate-800">
                        <?= ($row['title'] ?? '') . $row['first_name'] . ' ' . $row['last_name'] ?>
                    </td>
                    <td class="px-4 py-4 text-center font-bold text-slate-600"><?= $row['total_periods'] ?></td>
                    <td class="px-4 py-4 text-center text-green-600 font-bold"><?= $row['count_present'] ?></td>
                    <td class="px-4 py-4 text-center text-orange-600 font-bold"><?= $row['count_late'] ?></td>
                    <td class="px-4 py-4 text-center text-red-700 font-bold"><?= $row['count_absent'] ?></td>
                    <td class="px-4 py-4 text-center text-blue-600 font-bold"><?= $row['count_leave'] ?></td>
                    <td class="px-4 py-4 text-center">
                        <?php 
                            $pct = (float)$row['attendance_percentage'];
                            $colorClass = $pct < 80 ? 'bg-red-50 text-red-600 border-red-200' : 'bg-green-50 text-green-600 border-green-200';
                        ?>
                        <div class="inline-flex items-center px-3 py-1 rounded-full border <?= $colorClass ?> font-bold outfit text-xs">
                            <?= number_format($pct, 2) ?>%
                            <?php if ($pct < 80): ?>
                                <i class="fa fa-warning ml-1 text-[10px]"></i>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <button type="button" onclick="showAttendanceCalendar(<?= $row['id'] ?>)" class="px-3 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-bold hover:bg-blue-600 hover:text-white transition-all">
                            ดูรายละเอียด
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#report-table')) {
            $('#report-table').DataTable().destroy();
        }

        $('#report-table').DataTable({
            language: {
                "decimal": "",
                "emptyTable": "ไม่มีข้อมูลในตาราง",
                "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                "infoEmpty": "แสดง 0 ถึง 0 จากทั้งหมด 0 รายการ",
                "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "แสดง _MENU_ รายการ",
                "loadingRecords": "กำลังโหลด...",
                "processing": "กำลังประมวลผล...",
                "search": "ค้นหานักเรียน:",
                "searchPlaceholder": "พิมพ์ชื่อหรือรหัส...",
                "zeroRecords": "ไม่พบรายการที่ค้นหา",
                "paginate": {
                    "first": "หน้าแรก",
                    "last": "หน้าสุดท้าย",
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                },
                "aria": {
                    "sortAscending": ": เปิดใช้งานเพื่อเรียงลำดับคอลัมน์จากน้อยไปมาก",
                    "sortDescending": ": เปิดใช้งานเพื่อเรียงลำดับคอลัมน์จากมากไปน้อย"
                }
            },
            pageLength: 25,
            dom: '<"flex flex-col md:flex-row justify-between items-center mb-6"fB>rtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o mr-1"></i> Export Excel',
                    className: 'px-4 py-2 bg-green-600 text-white rounded-xl text-xs font-bold mr-2 shadow-lg shadow-green-200'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf-o mr-1"></i> Export PDF',
                    className: 'px-4 py-2 bg-rose-600 text-white rounded-xl text-xs font-bold mr-2 shadow-lg shadow-rose-200'
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print mr-1"></i> Print',
                    className: 'px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold shadow-lg shadow-slate-200'
                }
            ],
            columnDefs: [
                { targets: [0, 3, 4, 5, 6, 7, 8, 9], className: 'dt-center' }
            ]
        });

        // Move buttons to our custom container if needed
        // $('.dt-buttons').appendTo('#dt-buttons');
    }
</script>

<style>
    /* Aggressive DataTables Pagination Override */
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 2.5rem !important;
        padding-top: 1.5rem !important;
        display: flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
        gap: 8px !important; /* Force gap between all child elements */
    }
    
    /* Target EVERYTHING that could be a pagination button */
    .dataTables_wrapper .dataTables_paginate .paginate_button,
    .dataTables_wrapper .dataTables_paginate span .paginate_button,
    .dataTables_wrapper .dataTables_paginate ul li.paginate_button a,
    .dataTables_wrapper .dataTables_paginate a.paginate_button {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0.5rem 1rem !important;
        margin: 0 4px !important; /* Force horizontal spacing */
        min-width: 42px !important;
        height: 42px !important;
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        color: #475569 !important;
        border-radius: 12px !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
        text-decoration: none !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        cursor: pointer !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
    }

    /* Hover effect */
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover,
    .dataTables_wrapper .dataTables_paginate span .paginate_button:hover,
    .dataTables_wrapper .dataTables_paginate ul li.paginate_button a:hover {
        background-color: #f8fafc !important;
        color: var(--primary-color, #1d4ed8) !important;
        border-color: #cbd5e1 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    }

    /* Active / Current Page */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active a,
    .dataTables_wrapper .dataTables_paginate span .paginate_button.current {
        background-color: var(--primary-color, #1d4ed8) !important;
        color: #ffffff !important;
        border-color: var(--primary-color, #1d4ed8) !important;
        box-shadow: 0 10px 15px -3px rgba(29, 78, 216, 0.3) !important;
    }

    /* Previous & Next Buttons - Make them wider */
    .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
    .dataTables_wrapper .dataTables_paginate .paginate_button.next,
    .dataTables_wrapper .dataTables_paginate li.previous a,
    .dataTables_wrapper .dataTables_paginate li.next a {
        padding-left: 1.75rem !important;
        padding-right: 1.75rem !important;
        background-color: #f1f5f9 !important;
        border-color: transparent !important;
        color: #64748b !important;
    }

    /* Disabled State */
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate li.disabled a {
        opacity: 0.3 !important;
        cursor: default !important;
        transform: none !important;
        box-shadow: none !important;
    }

    /* Fix for when numbers are clumped in a span */
    .dataTables_wrapper .dataTables_paginate span {
        display: flex !important;
        gap: 8px !important;
        margin: 0 4px !important;
    }

    /* Search Box */
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1.5rem !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        padding: 0.75rem 1.25rem !important;
        font-size: 0.875rem !important;
        outline: none !important;
        min-width: 280px !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: var(--primary-color, #1d4ed8) !important;
        ring: 2px rgba(29, 78, 216, 0.1) !important;
    }

    /* Info and Buttons */
    .dataTables_wrapper .dataTables_info {
        color: #94a3b8 !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        margin-top: 2rem !important;
    }
    .dt-buttons {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 0.5rem !important;
    }
</style>
