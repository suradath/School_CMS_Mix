<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard' ?> - School CMS Mix V2.8</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '<?= \Core\Database::getSetting('primary_color', '#1d4ed8') ?>',
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&family=Outfit:wght@400;600;700&family=K2D:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwind.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.tailwind.min.css">
    
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwind.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.tailwind.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <style>
        :root {
            --primary-color: <?= \Core\Database::getSetting('primary_color', '#1d4ed8') ?>;
        }
        body { font-family: 'Sarabun', sans-serif; }
        h1, h2, h3, .heading-font { font-family: 'K2D', sans-serif; }
        .outfit { font-family: 'Outfit', sans-serif; }
        
        .sidebar-glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        
        .nav-item-active {
            background: linear-gradient(135deg, var(--tw-color-primary, #1d4ed8) 0%, #1e40af 100%);
            color: white !important;
            box-shadow: 0 10px 15px -3px rgba(30, 64, 175, 0.3);
        }
    </style>
    <style>
        /* Global Premium DataTables Styling */
        .dataTables_wrapper .dataTables_filter input {
            @apply bg-slate-50 border border-slate-100 rounded-xl text-sm px-4 py-2 focus:ring-2 focus:ring-primary/10 ml-2 transition-all;
            width: 250px;
        }
        .dataTables_wrapper .dataTables_length select {
            @apply bg-slate-50 border border-slate-100 rounded-xl text-sm px-8 py-2 focus:ring-2 focus:ring-primary/10 mx-2 transition-all;
        }
        .dataTables_wrapper .dataTables_info {
            @apply text-[11px] font-bold text-slate-400 uppercase tracking-widest;
            padding-top: 1.5rem;
        }
        .dataTables_wrapper .dataTables_paginate {
            @apply flex items-center gap-1 mt-6;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply px-4 py-2 text-sm font-bold rounded-xl transition-all cursor-pointer border border-slate-100 bg-white text-slate-600 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 40px;
            margin: 0 2px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-primary text-white shadow-lg shadow-primary/20 border-primary !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.current):not(.disabled):hover {
            @apply bg-slate-50 text-primary border-primary/30 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            @apply text-slate-200 border-slate-50 bg-slate-50/50 cursor-not-allowed !important;
        }
        
        /* Table Header Styling */
        table.dataTable thead th {
            @apply px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50 border-b border-slate-100 !important;
        }
        table.dataTable tbody td {
            @apply px-6 py-4 text-sm text-slate-600 border-b border-slate-50 transition-colors !important;
        }
        table.dataTable tbody tr:hover td {
            @apply bg-slate-50/50 !important;
        }

        /* Buttons Styling */
        .dt-buttons {
            @apply flex gap-2 mb-2 md:mb-0;
        }
        .dt-button {
            @apply !bg-white !border-slate-100 !rounded-xl !px-4 !py-2 !text-xs !font-bold !text-slate-600 !shadow-sm !transition-all hover:!bg-slate-50 hover:!border-slate-200 !m-0;
        }
        .buttons-excel {
            @apply !bg-emerald-50 !text-emerald-600 !border-emerald-100 hover:!bg-emerald-100 !important;
        }
        .buttons-print {
            @apply !bg-blue-50 !text-blue-600 !border-blue-100 hover:!bg-blue-100 !important;
        }

        /* Global SweetAlert2 Button Styling Fix */
        .swal2-container .swal2-styled.swal2-confirm {
            background-color: #2563eb !important;
            color: #ffffff !important;
            border-radius: 0.75rem !important;
            padding: 0.625rem 1.5rem !important;
            font-weight: 600 !important;
        }
        .swal2-container .swal2-styled.swal2-cancel {
            background-color: #64748b !important;
            color: #ffffff !important;
            border-radius: 0.75rem !important;
            padding: 0.625rem 1.5rem !important;
            font-weight: 600 !important;
        }
    </style>
</head>
<body class="bg-gray-50/50">
    
    <!-- Sidebar Toggle (Mobile) -->
    <button data-drawer-target="separator-sidebar" data-drawer-toggle="separator-sidebar" aria-controls="separator-sidebar" type="button" class="inline-flex items-center p-2 mt-4 ms-4 text-sm text-gray-500 rounded-xl sm:hidden hover:bg-white focus:outline-none shadow-sm border border-gray-200">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path></svg>
    </button>

    <?php 
        $siteName = \Core\Database::getSetting('site_name', 'School CMS Mix V2.8'); 
        $siteLogo = \Core\Database::getSetting('site_logo', '');
        $primaryColor = \Core\Database::getSetting('primary_color', '#1d4ed8');
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Check for unread documents
        $unreadSarabanCount = 0;
        if (isset($_SESSION['user_id'])) {
            $personnelId = (int)($_SESSION['personnel_id'] ?? 0);
            if ($personnelId > 0) {
                $personnelData = \Core\Database::fetch("SELECT department_id FROM personnel WHERE id = ?", [$personnelId]);
                $deptId = $personnelData['department_id'] ?? 0;
                
                $unreadSarabanCount = \Core\Database::fetch("
                    SELECT COUNT(*) as count 
                    FROM saraban_receivers r 
                    JOIN saraban_documents d ON r.document_id = d.id
                    WHERE (r.personnel_id = ? OR r.department_id = ?) 
                    AND r.status = 'unread' 
                    AND d.status = 'active'
                ", [$personnelId, $deptId])['count'] ?? 0;
            }

            // Check for submissions needing revision (for Teachers)
            $revisionCount = \Core\Database::fetch("
                SELECT COUNT(*) as count 
                FROM document_submissions 
                WHERE user_id = ? AND status = 'revision'
            ", [$_SESSION['user_id']])['count'] ?? 0;

            // Check for unread complaints (for Admin/Director)
            $unreadComplaintCount = 0;
            if (\Core\Security::hasRole(['admin', 'director'])) {
                $unreadComplaintCount = (int)\Core\Database::fetchColumn("SELECT COUNT(*) FROM complaints WHERE status = 'unread'");
                // Count pending repairs for badge
                $pendingRepairCount = 0;
                try {
                    $pendingRepairCount = (int)\Core\Database::fetchColumn("SELECT COUNT(*) FROM repair_requests WHERE status = 'pending'");
                } catch (\Exception $e) {
                    $pendingRepairCount = 0;
                }
            }

            // Check for pending bookings (for Admin/General Admin)
            $pendingBookingCount = 0;
            if (\Core\Security::checkRole(['admin', 'officer', 'editor'])) {
                try {
                    $pendingBookingCount = (int)\Core\Database::fetchColumn("SELECT COUNT(*) FROM bookings WHERE status = 'pending'");
                } catch (\Exception $e) {
                    $pendingBookingCount = 0;
                }
            }
        }
    ?>

    <!-- Global Notifications (SweetAlert2) -->
    <?php if ($unreadSarabanCount > 0 && strpos($currentPath, '/saraban') === false): ?>
    <script>
        window.onload = function() {
            Swal.fire({
                title: 'มีเอกสารส่งถึงคุณ!',
                text: 'คุณมีหนังสือเวียน <?= $unreadSarabanCount ?> รายการที่ยังไม่ได้อ่าน',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'เปิดกล่องขาเข้า',
                cancelButtonText: 'ไว้ทีหลัง',
                customClass: {
                    confirmButton: 'px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm mx-2 shadow-lg shadow-primary/20',
                    cancelButton: 'px-6 py-3 bg-slate-400 text-white rounded-xl font-bold text-sm mx-2'
                },
                buttonsStyling: false,
                position: 'center',
                backdrop: `rgba(15, 23, 42, 0.4)`
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= url('/saraban') ?>';
                }
            });
        };
    </script>
    <?php endif; ?>
    
    <!-- Sidebar -->
    <aside id="separator-sidebar" class="fixed top-0 left-0 z-40 w-72 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
        <div class="h-full px-4 py-8 overflow-y-auto bg-white border-r border-gray-100 shadow-xl shadow-gray-200/50">
            <div class="flex items-center px-4 mb-10 group">
                <div class="p-2 bg-primary/10 rounded-2xl mr-3 group-hover:rotate-12 transition-transform">
                    <?php if ($siteLogo): ?>
                        <img src="<?= url($siteLogo) ?>" class="h-8 w-auto" alt="<?= $siteName ?>">
                    <?php else: ?>
                        <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                    <?php endif; ?>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800 heading-font truncate border-none"><?= $siteName ?></h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Admin Management</p>
                </div>
            </div>
            
            <nav class="space-y-1.5">
                <?php 
                $leaveSubmenu = [
                    ['url' => '/leave', 'label' => 'การลาออนไลน์', 'icon' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z'],
                ];
                if (\Core\Security::checkRole(['admin', 'editor', 'officer', 'hr'])) {
                    $leaveSubmenu[] = ['url' => '/leave/review', 'label' => 'พิจารณาคำขอลา', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'];
                }
                if (\Core\Security::checkRole(['admin', 'officer', 'hr'])) {
                    $leaveSubmenu[] = ['url' => '/leave/reports', 'label' => 'รายงานสรุปการลาภาพรวม', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'];
                }

                $pageSubmenu = [];
                if (\Core\Security::checkRole(['admin', 'editor'])) {
                    $pageSubmenu = [
                        ['url' => '/pages', 'label' => 'จัดการหน้าเว็บคงที่', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['url' => '/news', 'label' => 'จัดการข่าวประชาสัมพันธ์', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM7 8h4m-4 4h8m-8 4h8'],
                        ['url' => '/gallery', 'label' => 'อัลบั้มภาพกิจกรรม', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['url' => '/journal', 'label' => 'วารสาร', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ];
                }

                $studentSubmenu = [
                    ['url' => '/students', 'label' => 'ภาพรวมข้อมูลนักเรียน', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['url' => '/students/classroom', 'label' => 'ข้อมูลรายห้องเรียน', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                    ['url' => '/attendance', 'label' => 'ระบบเช็คชื่อเข้าเรียน', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2'],
                    ['url' => '/attendance/report', 'label' => 'รายงานสรุปผลการเข้าเรียน', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ];

                if (\Core\Security::checkRole(['admin', 'discipline_staff'])) {
                    $studentSubmenu[] = ['url' => '/discipline', 'label' => 'ระบบงานปกครอง/พฤติกรรม', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'];
                    $studentSubmenu[] = ['url' => '/discipline/categories', 'label' => 'ตั้งค่าประเภทพฤติกรรม', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'];
                }

                if (\Core\Security::checkRole('student')) {
                    $studentSubmenu[] = ['url' => '/my-discipline', 'label' => 'ประวัติพฤติกรรมของฉัน', 'icon' => 'M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z'];
                }

                $studentSubmenu[] = ['url' => '/health', 'label' => 'สุขภาพและโภชนาการ', 'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'];

                $menuItems = [
                    ['url' => '/dashboard', 'label' => 'แผงควบคุม', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['label' => 'สารสนเทศนักเรียน', 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222', 'submenu' => $studentSubmenu],
                ];

                if (!empty($pageSubmenu)) {
                    $menuItems[] = ['label' => 'จัดการหน้าเว็บ', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'submenu' => $pageSubmenu];
                }

                if (\Core\Security::checkRole(['admin', 'editor'])) {
                    $menuItems[] = ['url' => '/personnel', 'label' => 'บุคลากร', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'];
                }

                $menuItems = array_merge($menuItems, [
                    ['label' => 'ระบบการลา', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 9l1.5 1.5L22 9.15', 'submenu' => $leaveSubmenu],
                    ['label' => 'งานสารบรรณ', 'icon' => 'M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20', 'badge' => $unreadSarabanCount > 0 ? $unreadSarabanCount : null, 'submenu' => [
                        ['url' => '/saraban', 'label' => 'แผงควบคุมสารบรรณ', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                        ['url' => '/saraban/inbound', 'label' => 'ทะเบียนหนังสือรับ', 'icon' => 'M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12'],
                        ['url' => '/saraban/outbound', 'label' => 'ทะเบียนหนังสือส่ง', 'icon' => 'M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4'],
                        ['url' => '/saraban/orders', 'label' => 'ทะเบียนคำสั่ง', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['url' => '/saraban/announcements', 'label' => 'ทะเบียนประกาศ', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                    ]],
                    ['url' => '/calendar', 'label' => 'ปฏิทินวิชาการ', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2V12a2 2 0 002 2z'],
                ]);

                // Resource Booking System Menu
                $bookingSubmenu = [
                    ['url' => '/booking', 'label' => 'ปฏิทินการจอง', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2V12a2 2 0 002 2z'],
                    ['url' => '/booking/myBookings', 'label' => 'การจองของฉัน', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ];
                if (\Core\Security::checkRole(['admin', 'officer', 'editor'])) {
                    $bookingSubmenu[] = [
                        'url' => '/adminBooking/approvals', 
                        'label' => 'พิจารณาคำขอจอง', 
                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                        'badge' => $pendingBookingCount > 0 ? $pendingBookingCount : null
                    ];
                    $bookingSubmenu[] = ['url' => '/adminBooking/resources', 'label' => 'จัดการทรัพยากร', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'];
                }

                $menuItems[] = [
                    'label' => 'ระบบจองทรัพยากร', 
                    'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2V12a2 2 0 002 2z', 
                    'badge' => $pendingBookingCount > 0 ? $pendingBookingCount : null,
                    'submenu' => $bookingSubmenu
                ];

                // Document Submission System Menu
                $revisionCount = 0;
                $pendingReviewCount = 0;
                if (\Core\Security::isLoggedIn()) {
                    $userId = (int)$_SESSION['user_id'];
                    // Count revisions for teacher
                    $revisionCount = (int)\Core\Database::fetchColumn("SELECT COUNT(*) FROM document_submissions WHERE user_id = ? AND status = 'revision'", [$userId]);
                    
                    // Count pending reviews for academic/admin
                    if (\Core\Security::checkRole(['admin', 'academic'])) {
                        $pendingReviewCount = (int)\Core\Database::fetchColumn("SELECT COUNT(*) FROM document_submissions WHERE status = 'pending'");
                    }
                }

                $submissionSubmenu = [
                    ['url' => '/submissions', 'label' => 'ส่งเอกสารและผลงาน', 'icon' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12'],
                ];
                if (\Core\Security::checkRole(['admin', 'academic', 'director'])) {
                    $submissionSubmenu[] = [
                        'url' => '/submissions/monitor', 
                        'label' => 'ติดตามการส่งเอกสาร', 
                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2',
                        'badge' => (\Core\Security::checkRole(['admin', 'academic']) && $pendingReviewCount > 0) ? $pendingReviewCount : null
                    ];
                }
                if (\Core\Security::checkRole(['admin', 'academic'])) {
                    $submissionSubmenu[] = ['url' => '/submissions/topics', 'label' => 'ตั้งค่าหัวข้อการส่ง', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'];
                }

                $menuItems[] = [
                    'label' => 'ระบบส่งเอกสาร', 
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 
                    'badge' => isset($revisionCount) && $revisionCount > 0 ? $revisionCount : null,
                    'submenu' => $submissionSubmenu
                ];

                // PLC Menu
                $plcSubmenu = [
                    ['url' => '/plc', 'label' => 'แดชบอร์ด PLC', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['url' => '/plc/groups', 'label' => 'จัดการกลุ่ม PLC', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                ];
                if (\Core\Security::checkRole(['admin', 'hr', 'academic', 'director'])) {
                    $plcSubmenu[] = ['url' => '/plc/admin/reports', 'label' => 'สรุปชั่วโมงภาพรวม', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'];
                }
                
                $menuItems[] = [
                    'label' => 'ระบบ PLC', 
                    'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 
                    'submenu' => $plcSubmenu
                ];

                // Helpdesk Menu
                $helpdeskSubmenu = [
                    ['url' => '/helpdesk', 'label' => 'แจ้งซ่อมใหม่', 'icon' => 'M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['url' => '/helpdesk/my-repairs', 'label' => 'ประวัติการแจ้งซ่อม', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
                if (\Core\Security::checkRole(['admin', 'staff', 'officer'])) {
                    $helpdeskSubmenu[] = [
                        'url' => '/admin/helpdesk', 
                        'label' => 'จัดการรายการแจ้งซ่อม', 
                        'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                        'badge' => $pendingRepairCount > 0 ? $pendingRepairCount : null
                    ];
                }

                $menuItems[] = [
                    'label' => 'ระบบแจ้งซ่อม', 
                    'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 
                    'badge' => (\Core\Security::checkRole(['admin', 'staff', 'officer']) && $pendingRepairCount > 0) ? $pendingRepairCount : null,
                    'submenu' => $helpdeskSubmenu
                ];

                if (\Core\Security::checkRole('admin')) {
                    $menuItems[] = ['url' => '/admin/users', 'label' => 'จัดการผู้ใช้งาน', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'];
                }

                if (\Core\Security::checkRole(['admin', 'director'])) {
                    $menuItems[] = [
                        'url' => '/admin/complaints', 
                        'label' => 'ระบบรับเรื่องร้องเรียน', 
                        'icon' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z',
                        'badge' => $unreadComplaintCount > 0 ? $unreadComplaintCount : null
                    ];
                }
                
                foreach ($menuItems as $item): 
                    if (isset($item['submenu'])) {
                        // Check if any submenu item is active
                        $anySubActive = false;
                        foreach ($item['submenu'] as $sub) {
                            if ($currentPath === $sub['url'] || strpos($currentPath, $sub['url'] . '/') === 0) {
                                $anySubActive = true;
                                break;
                            }
                        }
                    ?>
                        <div x-data="{ open: <?= $anySubActive ? 'true' : 'false' ?> }" class="space-y-1">
                            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3.5 text-sm font-bold transition-all duration-200 rounded-2xl group <?= $anySubActive ? 'bg-primary/5 text-primary' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' ?>">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-3 <?= $anySubActive ? 'text-primary' : 'text-slate-400 group-hover:text-primary transition-colors' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $item['icon'] ?>"></path>
                                    </svg>
                                    <?= $item['label'] ?>
                                </div>
                                <div class="flex items-center">
                                    <?php if (isset($item['badge'])): ?>
                                        <span class="mr-2 px-2 py-0.5 bg-rose-500 text-white text-[10px] font-bold rounded-full"><?= $item['badge'] ?></span>
                                    <?php endif; ?>
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </button>
                            <div x-show="open" x-transition.opacity class="pl-12 space-y-1">
                                <?php foreach ($item['submenu'] as $sub): 
                                    // Check if current path matches sub URL exactly or as a more specific match
                                    $subActive = ($currentPath === $sub['url']);
                                    if (!$subActive && $currentPath !== '/') {
                                        // Prefix match but only if it's a directory-style match and no other subitem is a better match
                                        if (strpos($currentPath, $sub['url'] . '/') === 0) {
                                            $subActive = true;
                                            // Check if there's a more specific subitem
                                            foreach ($item['submenu'] as $otherSub) {
                                                if ($otherSub['url'] !== $sub['url'] && strpos($currentPath, $otherSub['url']) === 0) {
                                                    if (strlen($otherSub['url']) > strlen($sub['url'])) {
                                                        $subActive = false;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                ?>
                                    <a href="<?= url($sub['url']) ?>" class="flex items-center py-2 text-xs font-medium transition-colors <?= $subActive ? 'text-primary font-bold' : 'text-slate-400 hover:text-slate-900' ?>">
                                        <?php if (isset($sub['icon'])): ?>
                                            <svg class="w-3.5 h-3.5 mr-2.5 <?= $subActive ? 'text-primary' : 'text-slate-300' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $sub['icon'] ?>"></path>
                                            </svg>
                                        <?php endif; ?>
                                        <div class="flex-1 flex justify-between items-center min-w-0">
                                            <span class="truncate"><?= $sub['label'] ?></span>
                                            <?php if (isset($sub['badge'])): ?>
                                                <span class="px-1.5 py-0.5 bg-rose-500 text-white text-[9px] font-bold rounded-full"><?= $sub['badge'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php
                        continue;
                    }

                    // Normal link logic
                    $active = ($currentPath === $item['url']) || (strpos($currentPath, $item['url'] . '/') === 0);
                    
                    if ($active) {
                        foreach ($menuItems as $otherItem) {
                            if (isset($otherItem['url']) && $otherItem['url'] !== $item['url'] && strlen($otherItem['url']) > strlen($item['url'])) {
                                if (strpos($currentPath, $otherItem['url']) === 0) {
                                    $active = false;
                                    break;
                                }
                            }
                        }
                    }
                ?>
                <a href="<?= url($item['url']) ?>" class="flex items-center px-4 py-3.5 text-sm font-bold transition-all duration-200 rounded-2xl group <?= $active ? 'nav-item-active' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' ?>">
                    <svg class="w-5 h-5 mr-3 <?= $active ? 'text-white' : 'text-slate-400 group-hover:text-primary transition-colors' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $item['icon'] ?>"></path>
                    </svg>
                    <?= $item['label'] ?>
                </a>
                <?php endforeach; ?>
            </nav>

                <p class="px-4 mb-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">System Settings</p>
                <?php if (\Core\Security::checkRole('admin')): ?>
                <a href="<?= url('/settings') ?>" class="flex items-center px-4 py-3.5 text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-2xl transition-all group <?= $currentPath === '/settings' ? 'bg-slate-50 text-slate-900' : '' ?>">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:rotate-45 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    ตั้งค่าระบบ
                </a>
                <a href="<?= url('/settings/home-editor') ?>" class="flex items-center px-4 py-3.5 text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-2xl transition-all group <?= $currentPath === '/settings/home-editor' ? 'bg-slate-50 text-slate-900' : '' ?>">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    แก้ไขหน้าแรก
                </a>
                <a href="<?= url('/personnel/departments') ?>" class="flex items-center px-4 py-3.5 text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-2xl transition-all group <?= $currentPath === '/personnel/departments' ? 'bg-slate-50 text-slate-900' : '' ?>">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    ตั้งค่ากลุ่มสาระฯ
                </a>
                <a href="<?= url('/settings/popups') ?>" class="flex items-center px-4 py-3.5 text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-2xl transition-all group <?= $currentPath === '/settings/popups' ? 'bg-slate-50 text-slate-900' : '' ?>">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    จัดการ Popup
                </a>
                <a href="<?= url('/settings/menu') ?>" class="flex items-center px-4 py-3.5 text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-2xl transition-all group <?= $currentPath === '/settings/menu' ? 'bg-slate-50 text-slate-900' : '' ?>">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    จัดการเมนู
                </a>
                <a href="<?= url('/settings/footer-editor') ?>" class="flex items-center px-4 py-3.5 text-sm font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-900 rounded-2xl transition-all group <?= $currentPath === '/settings/footer-editor' ? 'bg-slate-50 text-slate-900' : '' ?>">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    แก้ไขส่วนท้าย
                </a>
                <?php endif; ?>
                <a href="<?= url('/auth/logout') ?>" class="flex items-center px-4 py-3.5 text-sm font-bold text-red-500 hover:bg-red-50 rounded-2xl transition-all group">
                    <svg class="w-5 h-5 mr-3 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    ออกจากระบบ
                </a>
            </div>
            
            <div class="mt-auto pt-10">
                <div class="p-4 bg-slate-900 rounded-3xl text-white relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                    <p class="text-[10px] font-bold text-blue-300 uppercase tracking-[0.2em] mb-1">Status</p>
                    <p class="text-xs font-bold leading-relaxed">System v2.8 Stable</p>
                    <a href="<?= url('/') ?>" target="_blank" class="mt-4 inline-flex items-center text-[10px] font-bold hover:text-blue-300 transition-colors">
                        VIEW LIVE SITE <svg class="w-2.5 h-2.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-4 sm:ml-72 min-h-screen">
        <div class="p-6 md:p-10">
            <!-- Header Area -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 space-y-4 md:space-y-0">
                <div>
                    <h2 class="text-4xl font-extrabold text-slate-900 heading-font tracking-tight"><?= $title ?? 'Dashboard' ?></h2>
                    <nav class="flex mt-2 text-xs font-bold text-slate-400 uppercase tracking-widest" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1">
                            <li class="inline-flex items-center"><a href="<?= url('/dashboard') ?>" class="hover:text-primary">Admin</a></li>
                            <li><div class="flex items-center"><svg class="w-3 h-3 mx-1 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg> <span class="text-slate-300"><?= $title ?? 'Dashboard' ?></span></div></li>
                        </ol>
                    </nav>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex flex-col items-end mr-3">
                        <span class="text-sm font-bold text-slate-900 outfit leading-none"><?= $_SESSION['user_name'] ?? 'Admin' ?></span>
                        <span class="text-[10px] font-bold text-primary uppercase tracking-widest mt-1">
                            <?php 
                            if (!empty($_SESSION['user_roles'])) {
                                $roleNames = [];
                                foreach ($_SESSION['user_roles'] as $slug) {
                                    $roleNames[] = [
                                        'admin' => 'ผู้ดูแลระบบ',
                                        'editor' => 'เจ้าหน้าที่ระบบ',
                                        'teacher' => 'ครู/บุคลากร',
                                        'dept_head' => 'หัวหน้ากลุ่มสาระฯ',
                                        'staff' => 'เจ้าหน้าที่ทั่วไป',
                                        'director' => 'ผู้อำนวยการ',
                                        'hr' => 'งานบุคคล'
                                    ][$slug] ?? ucfirst($slug);
                                }
                                echo implode(' / ', $roleNames);
                            } else {
                                echo 'User';
                            }
                            ?>
                        </span>
                    </div>
                    <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-primary font-bold">
                        <?= substr($_SESSION['user_name'] ?? 'A', 0, 1) ?>
                    </div>
                </div>
            </div>

            <!-- Page Content with Bento Grid Container -->
            <div class="animate-fade-in min-h-[60vh]">
                <?php if(isset($content)) echo $content; ?>
            </div>

            <!-- Footer -->
            <footer class="mt-20 py-10 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center text-slate-400">
                <div class="flex items-center space-x-2 text-sm font-bold">
                    <span class="text-slate-900 outfit tracking-tight text-lg">School CMS Mix</span>
                    <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded text-[10px] uppercase tracking-widest">v2.8</span>
                </div>
                <div class="mt-6 md:mt-0 text-center md:text-right">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-300">Development & Copyright &copy; 2569</div>
                    <div class="text-sm font-bold text-slate-700 mt-1">ครูสุรเดช ปุยะติ <span class="mx-1 text-slate-200">|</span> <a href="mailto:suradath@lamplaimat.ac.th" class="hover:text-primary transition-colors">suradath@lamplaimat.ac.th</a></div>
                    <div class="text-[11px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">โรงเรียนลำปลายมาศ</div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    <!-- DataTables Global Initialization -->
    <script>
        function initPremiumDataTable(selector, options = {}) {
            const defaultOptions = {
                dom: '<"flex flex-col md:flex-row items-center justify-between gap-4 mb-6"Bf>rt<"flex flex-col md:flex-row items-center justify-between gap-4 mt-6"ip>',
                buttons: [
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel-o mr-2"></i> ส่งออก Excel',
                        className: 'buttons-excel',
                        titleAttr: 'Export to Excel'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print mr-2"></i> พิมพ์',
                        className: 'buttons-print',
                        titleAttr: 'Print Table'
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/th.json',
                    search: "",
                    searchPlaceholder: "ค้นหาข้อมูล...",
                    lengthMenu: "แสดง _MENU_ รายการ",
                    info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    infoEmpty: "แสดง 0 ถึง 0 จาก 0 รายการ",
                    infoFiltered: "(กรองจากทั้งหมด _MAX_ รายการ)",
                    zeroRecords: "ไม่พบข้อมูลที่ค้นหา",
                    paginate: {
                        first: '<i class="fa fa-angle-double-left"></i>',
                        last: '<i class="fa fa-angle-double-right"></i>',
                        next: '<i class="fa fa-angle-right"></i>',
                        previous: '<i class="fa fa-angle-left"></i>'
                    }
                }
            };
            return $(selector).DataTable($.extend(true, defaultOptions, options));
        }
    </script>
</body>
</html>
