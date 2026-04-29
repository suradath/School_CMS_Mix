<div class="space-y-6">
    <!-- Filter Card -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="flex-1">
                <label for="course_select" class="block mb-2 text-sm font-bold text-slate-700 outfit uppercase tracking-wider">เลือกรายวิชาเพื่อดูสรุปสถิติ</label>
                <select id="course_select" onchange="fetchReport()" class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-3.5 transition-all">
                    <option value="">-- เลือกวิชา - ห้องเรียน --</option>
                    <?php foreach ($courses as $c): ?>
                        <?php $val = "{$c['id']}|{$c['class_level']}|{$c['room_number']}"; ?>
                        <option value="<?= $val ?>">
                            [<?= $c['course_code'] ?>] - <?= $c['course_name'] ?> - <?= $c['class_level'] ?>/<?= $c['room_number'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="button" onclick="fetchReport()" class="px-8 py-3.5 bg-primary text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                    <i class="fa fa-refresh mr-2"></i>ประมวลผลข้อมูล
                </button>
            </div>
        </div>
    </div>

    <!-- Report Table Container -->
    <div id="report-container" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-20 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                <i class="fa fa-bar-chart text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 heading-font mb-2">รายงานสรุปผลการเข้าเรียน</h3>
            <p class="text-slate-400 text-sm">กรุณาเลือกวิชาที่ต้องการตรวจสอบสถิติการเข้าเรียนของนักเรียน</p>
        </div>
    </div>
</div>

<!-- Attendance Calendar Modal -->
<div id="calendar-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-4xl max-h-full">
        <div class="relative bg-white rounded-3xl shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h3 class="text-xl font-bold text-slate-800 heading-font">
                    ประวัติการเข้าเรียนรายบุคคล
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-2xl text-sm w-10 h-10 ms-auto inline-flex justify-center items-center transition-all" onclick="calendarModal.hide()">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div id="calendar-modal-body" class="p-0 min-h-[500px]">
                <!-- AJAX Content -->
            </div>
        </div>
    </div>
</div>

<script>
    let calendarModal;
    window.addEventListener('DOMContentLoaded', () => {
        const $targetEl = document.getElementById('calendar-modal');
        if ($targetEl) {
            // ย้ายไปที่ body เพื่อเลี่ยงปัญหา z-index
            document.body.appendChild($targetEl);
            
            // ตั้งค่า Modal Options เพื่อความแน่นอน
            const options = {
                placement: 'center',
                backdrop: 'dynamic',
                backdropClasses: 'bg-gray-900/50 fixed inset-0 z-40',
                closable: true
            };
            
            calendarModal = new Modal($targetEl, options);
        }
    });

    function showAttendanceCalendar(studentId) {
        const courseVal = document.getElementById('course_select').value;
        const [courseId] = courseVal.split('|');
        const modalBody = document.getElementById('calendar-modal-body');
        
        modalBody.innerHTML = `
            <div class="flex flex-col items-center justify-center h-[500px]">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent mb-4"></div>
                <p class="text-slate-500 font-bold">กำลังโหลดปฏิทิน...</p>
            </div>
        `;
        
        calendarModal.show();

        fetch('<?= url('/attendance/get-student-calendar') ?>' + `?student_id=${studentId}&course_id=${courseId}`)
            .then(response => response.text())
            .then(html => {
                modalBody.innerHTML = html;
                
                // สั่งให้ Script ที่มากับ AJAX ทำงาน
                const scripts = modalBody.querySelectorAll('script');
                scripts.forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
            })
            .catch(err => {
                modalBody.innerHTML = `<div class="p-10 text-red-500 text-center">เกิดข้อผิดพลาด: ${err}</div>`;
            });
    }

    function fetchReport() {
        const courseVal = document.getElementById('course_select').value;
        if (!courseVal) return;

        const [courseId, level, room] = courseVal.split('|');
        const container = document.getElementById('report-container');
        
        container.innerHTML = `
            <div class="p-20 text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent mb-4"></div>
                <p class="text-slate-500 font-bold">กำลังประมวลผลข้อมูลสถิติ...</p>
            </div>
        `;

        fetch('<?= url('/attendance/get-report') ?>' + `?course_id=${courseId}&class_level=${encodeURIComponent(level)}&room_number=${room}`)
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                
                // สั่งให้ Script ที่มากับ AJAX ทำงาน (เพื่อให้ initDataTable ถูกประกาศ)
                const scripts = container.querySelectorAll('script');
                scripts.forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });

                // เรียกใช้ฟังก์ชันหลังจากรัน script แล้ว
                if (typeof initDataTable === 'function') {
                    initDataTable();
                }
            })
            .catch(err => {
                container.innerHTML = `<div class="p-10 text-red-500 text-center">เกิดข้อผิดพลาด: ${err}</div>`;
            });
    }
</script>
