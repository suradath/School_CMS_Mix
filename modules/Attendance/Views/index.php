<div class="space-y-6">
    <!-- Filter Card -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
            <!-- Date Picker -->
            <div class="md:col-span-3">
                <label for="check_date" class="block mb-2 text-sm font-bold text-slate-700 outfit uppercase tracking-wider">วันที่เช็คชื่อ</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                        <i class="fa fa-calendar text-slate-400"></i>
                    </div>
                    <input type="date" id="check_date" value="<?= $_GET['date'] ?? date('Y-m-d') ?>" 
                        class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full ps-10 p-3.5 transition-all">
                </div>
            </div>

            <!-- Course Selection -->
            <div class="md:col-span-6">
                <label for="course_select" class="block mb-2 text-sm font-bold text-slate-700 outfit uppercase tracking-wider">เลือกรายวิชา - ห้องเรียน</label>
                <select id="course_select" class="bg-gray-50 border border-gray-200 text-slate-900 text-sm rounded-2xl focus:ring-primary focus:border-primary block w-full p-3.5 transition-all">
                    <option value="">-- เลือกวิชา --</option>
                    <?php foreach ($courses as $c): ?>
                        <?php 
                            $val = "{$c['id']}|{$c['class_level']}|{$c['room_number']}";
                            $selected = (isset($_GET['course_id']) && $_GET['course_id'] == $c['id'] && $_GET['class_level'] == $c['class_level'] && $_GET['room_number'] == $c['room_number']) ? 'selected' : '';
                        ?>
                        <option value="<?= $val ?>" <?= $selected ?>>
                            [<?= $c['course_code'] ?>] - <?= $c['course_name'] ?> - <?= $c['class_level'] ?>/<?= $c['room_number'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Setup Button -->
            <div class="md:col-span-3 flex space-x-2">
                <button type="button" onclick="fetchStudents()" class="flex-1 px-6 py-3.5 bg-primary text-white rounded-2xl font-bold text-sm shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                    <i class="fa fa-search mr-2"></i>ดึงรายชื่อ
                </button>
                <a href="/attendance/setup" class="p-3.5 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-all" title="ตั้งค่ารายวิชา">
                    <i class="fa fa-cog"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Student List Container -->
    <div id="student-list-container" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-20 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa fa-users text-4xl text-slate-200"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 heading-font mb-2">พร้อมสำหรับการเช็คชื่อ</h3>
            <p class="text-slate-400 text-sm">กรุณาเลือกวิชาและวันที่ต้องการทำรายการด้านบน</p>
        </div>
    </div>
</div>

<script>
    function fetchStudents() {
        const date = document.getElementById('check_date').value;
        const courseVal = document.getElementById('course_select').value;
        
        if (!courseVal) {
            Swal.fire('แจ้งเตือน', 'กรุณาเลือกวิชาและห้องเรียน', 'warning');
            return;
        }

        const [courseId, level, room] = courseVal.split('|');
        const container = document.getElementById('student-list-container');
        
        // Show Loading
        container.innerHTML = `
            <div class="p-20 text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent mb-4"></div>
                <p class="text-slate-500 font-bold">กำลังดึงข้อมูลนักเรียน...</p>
            </div>
        `;

        fetch(`/attendance/get-students?date=${date}&course_id=${courseId}&class_level=${encodeURIComponent(level)}&room_number=${room}`)
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
                
                // สั่งให้ Script ที่มากับ AJAX ทำงาน
                const scripts = container.querySelectorAll('script');
                scripts.forEach(oldScript => {
                    const newScript = document.createElement('script');
                    Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                    oldScript.parentNode.replaceChild(newScript, oldScript);
                });
            })
            .catch(err => {
                container.innerHTML = `<div class="p-10 text-red-500 text-center">เกิดข้อผิดพลาดในการโหลดข้อมูล: ${err}</div>`;
            });
    }

    // Auto-fetch if redirected back with params
    window.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('course_select').value) {
            fetchStudents();
        }
    });
</script>
