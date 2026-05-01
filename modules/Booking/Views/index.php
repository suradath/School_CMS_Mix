<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa fa-calendar-check-o text-blue-600"></i>
                ระบบจองทรัพยากร (ห้อง/ยานพาหนะ)
            </h1>
            <p class="text-gray-600">จองห้องประชุมและยานพาหนะสำหรับบุคลากร</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openBookingModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition duration-300 flex items-center gap-2">
                <i class="fa fa-plus"></i> สร้างการจองใหม่
            </button>
            <a href="<?= url('/booking/myBookings') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg border border-gray-300 transition duration-300 flex items-center gap-2">
                <i class="fa fa-list"></i> การจองของฉัน
            </a>
        </div>
    </div>

    <!-- Calendar Legend -->
    <div class="bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
        <div class="flex flex-wrap gap-4 text-sm">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                <span>ห้องประชุม (อนุมัติแล้ว)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-violet-500"></span>
                <span>ยานพาหนะ (อนุมัติแล้ว)</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <span>รอการอนุมัติ</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                <span>ไม่อนุมัติ / ยกเลิก</span>
            </div>
        </div>
    </div>

    <!-- FullCalendar Wrapper -->
    <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-200">
        <div id="calendar" class="min-h-[700px]"></div>
    </div>
</div>

<!-- Modal for Booking (Template) -->
<div id="bookingModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" aria-hidden="true" onclick="closeBookingModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fa fa-edit text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-xl leading-6 font-bold text-gray-900 mb-4" id="modal-title">ข้อมูลการจอง</h3>
                        
                        <form id="bookingForm" class="space-y-4">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ประเภททรัพยากร</label>
                                <select name="resource_type" id="resource_type" onchange="loadResources(this.value)" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border" required>
                                    <option value="">-- เลือกประเภท --</option>
                                    <option value="room">ห้องประชุม</option>
                                    <option value="vehicle">ยานพาหนะ</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">รายการ</label>
                                <select name="resource_id" id="resource_id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border" required disabled>
                                    <option value="">-- เลือกรายการ --</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">เริ่มเวลา</label>
                                    <input type="datetime-local" name="start_time" id="start_time" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">ถึงเวลา</label>
                                    <input type="datetime-local" name="end_time" id="end_time" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">หัวข้อ/วัตถุประสงค์</label>
                                <input type="text" name="title" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border" placeholder="เช่น ประชุมกลุ่มสาระฯ" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">จำนวนผู้เข้าร่วม (คน)</label>
                                <input type="number" name="participants_count" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border" min="1" value="1" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">รายละเอียดเพิ่มเติม</label>
                                <textarea name="details" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-2.5 border"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button type="button" onclick="submitBooking()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    ยืนยันการจอง
                </button>
                <button type="button" onclick="closeBookingModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    ยกเลิก
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for FullCalendar -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/th.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'th',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '<?= url('/booking/events') ?>',
        dateClick: function(info) {
            openBookingModal(info.dateStr);
        },
        eventClick: function(info) {
            const props = info.event.extendedProps;
            Swal.fire({
                title: info.event.title,
                html: `
                    <div class="text-left text-sm space-y-3 mt-4 p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <p><strong>ทรัพยากร:</strong> ${props.resource}</p>
                        <p><strong>ผู้จอง:</strong> ${props.user}</p>
                        <p><strong>จำนวน:</strong> ${props.participants} คน</p>
                        <p><strong>เวลา:</strong> ${info.event.start.toLocaleString('th-TH')} - ${info.event.end ? info.event.end.toLocaleString('th-TH') : ''}</p>
                        <p><strong>รายละเอียด:</strong> ${props.details || '-'}</p>
                        ${props.status === 'rejected' && props.rejection_reason ? `
                        <div class="mt-2 p-3 bg-red-100 text-red-800 rounded-lg border border-red-200">
                            <strong>เหตุผลที่ไม่อนุมัติ:</strong><br>${props.rejection_reason}
                        </div>
                        ` : ''}
                        <div class="pt-2">
                            <strong>สถานะ:</strong> 
                            <span class="ml-2 px-3 py-1 rounded-full text-xs font-bold ${props.status === 'approved' ? 'bg-green-100 text-green-700' : (props.status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700')}">
                                ${props.status === 'approved' ? 'อนุมัติแล้ว' : (props.status === 'pending' ? 'รอการอนุมัติ' : 'ไม่อนุมัติ')}
                            </span>
                        </div>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'รับทราบ',
                confirmButtonColor: '#3b82f6'
            });
        }
    });
    calendar.render();
});

function openBookingModal(dateStr = '') {
    document.getElementById('bookingModal').classList.remove('hidden');
    if (dateStr) {
        // Set default start/end time based on clicked date
        const today = new Date();
        const timeStr = today.getHours().toString().padStart(2, '0') + ':00';
        document.getElementById('start_time').value = dateStr + 'T' + timeStr;
        document.getElementById('end_time').value = dateStr + 'T' + (today.getHours() + 1).toString().padStart(2, '0') + ':00';
    }
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.add('hidden');
    document.getElementById('bookingForm').reset();
}

function loadResources(type) {
    const resourceSelect = document.getElementById('resource_id');
    resourceSelect.innerHTML = '<option value="">-- กำลังโหลด... --</option>';
    resourceSelect.disabled = true;

    if (!type) return;

    fetch(`<?= url('/booking/resourcesByType') ?>?type=${type}`)
        .then(response => response.json())
        .then(data => {
            resourceSelect.innerHTML = '<option value="">-- เลือกรายการ --</option>';
            data.forEach(item => {
                resourceSelect.innerHTML += `<option value="${item.id}">${item.name} (จุได้ ${item.capacity} คน)</option>`;
            });
            resourceSelect.disabled = false;
        });
}

function submitBooking() {
    const formData = new FormData(document.getElementById('bookingForm'));
    
    fetch('<?= url('/booking/store') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'สำเร็จ',
                text: 'ส่งคำขอจองเรียบร้อยแล้ว รอการอนุมัติ',
                icon: 'success',
                confirmButtonColor: '#3b82f6'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'ผิดพลาด',
                text: data.message || 'ไม่สามารถบันทึกการจองได้',
                icon: 'error',
                confirmButtonColor: '#3b82f6'
            });
        }
    });
}
</script>

<style>
/* FullCalendar Tailwind Overrides */
.fc .fc-button-primary {
    @apply bg-blue-600 border-none hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 shadow-sm;
}
.fc .fc-toolbar-title {
    @apply text-xl font-bold text-gray-700;
}
.fc-theme-standard td, .fc-theme-standard th {
    @apply border-gray-100;
}
.fc .fc-daygrid-day.fc-day-today {
    @apply bg-blue-50;
}
</style>
