<div class="p-6">
    <div class="flex items-center space-x-4 mb-6">
        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary font-bold text-xl">
            <?= substr($student['first_name'] ?? 'S', 0, 1) ?>
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-800 heading-font">
                <?= ($student['title'] ?? '') . $student['first_name'] . ' ' . $student['last_name'] ?>
            </h3>
            <p class="text-sm text-slate-500 font-medium">รหัสประจำตัว: <?= $student['student_code'] ?></p>
        </div>
    </div>

    <!-- Calendar Legend -->
    <div class="flex flex-wrap gap-3 mb-6 p-4 bg-slate-50 rounded-2xl">
        <div class="flex items-center text-[10px] font-bold text-slate-500">
            <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span> มาเรียน
        </div>
        <div class="flex items-center text-[10px] font-bold text-slate-500">
            <span class="w-3 h-3 bg-orange-500 rounded-full mr-2"></span> มาสาย
        </div>
        <div class="flex items-center text-[10px] font-bold text-slate-500">
            <span class="w-3 h-3 bg-red-700 rounded-full mr-2"></span> หนีเรียน
        </div>
        <div class="flex items-center text-[10px] font-bold text-slate-500">
            <span class="w-3 h-3 bg-gray-600 rounded-full mr-2"></span> ขาดเรียน
        </div>
        <div class="flex items-center text-[10px] font-bold text-slate-500">
            <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span> ลากิจ
        </div>
        <div class="flex items-center text-[10px] font-bold text-slate-500">
            <span class="w-3 h-3 bg-purple-500 rounded-full mr-2"></span> ลาป่วย
        </div>
    </div>

    <!-- Calendar Container -->
    <div id="attendance-calendar" class="min-h-[400px]"></div>
</div>

<!-- Load FullCalendar from CDN (Only if not already loaded) -->
<script>
    if (typeof FullCalendar === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js';
        script.onload = initCalendar;
        document.head.appendChild(script);
    } else {
        initCalendar();
    }

    function initCalendar() {
        var calendarEl = document.getElementById('attendance-calendar');
        if (!calendarEl) return;

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'th',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            events: [
                <?php 
                $statusColors = [
                    'present' => '#22c55e',       // green-500
                    'late' => '#f97316',          // orange-500
                    'truant' => '#b91c1c',        // red-700
                    'absent' => '#4b5563',        // gray-600
                    'personal_leave' => '#3b82f6', // blue-500
                    'sick_leave' => '#a855f7',     // purple-500
                ];
                $statusLabels = [
                    'present' => 'มาเรียน',
                    'late' => 'มาสาย',
                    'truant' => 'หนีเรียน',
                    'absent' => 'ขาดเรียน',
                    'personal_leave' => 'ลากิจ',
                    'sick_leave' => 'ลาป่วย',
                ];
                foreach ($history as $h): 
                ?>
                {
                    title: '<?= $statusLabels[$h['status']] ?? $h['status'] ?>',
                    start: '<?= $h['check_date'] ?>',
                    backgroundColor: '<?= $statusColors[$h['status']] ?? '#94a3b8' ?>',
                    borderColor: '<?= $statusColors[$h['status']] ?? '#94a3b8' ?>',
                    allDay: true
                },
                <?php endforeach; ?>
            ]
        });
        calendar.render();
    }
</script>

<style>
    .fc .fc-toolbar-title {
        font-family: 'K2D', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }
    .fc .fc-button-primary {
        background-color: #f8fafc;
        border-color: #f1f5f9;
        color: #64748b;
        font-weight: 700;
        font-size: 0.75rem;
        border-radius: 0.75rem;
        text-transform: uppercase;
        padding: 0.5rem 1rem;
    }
    .fc .fc-button-primary:hover {
        background-color: #f1f5f9;
        border-color: #e2e8f0;
        color: #1e293b;
    }
    .fc .fc-button-primary:not(:disabled).fc-button-active, 
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
        color: white;
    }
    .fc-event-title {
        font-weight: 700;
        font-size: 0.7rem;
        padding: 2px 4px;
    }
    .fc-daygrid-event {
        border-radius: 4px;
    }
</style>
