<?php
$statusOptions = [
    'present' => ['label' => 'มาเรียน', 'color' => 'bg-green-500', 'text' => 'text-white'],
    'late' => ['label' => 'มาสาย', 'color' => 'bg-orange-500', 'text' => 'text-white'],
    'truant' => ['label' => 'หนีเรียน', 'color' => 'bg-red-700', 'text' => 'text-white'],
    'absent' => ['label' => 'ขาดเรียน', 'color' => 'bg-gray-600', 'text' => 'text-white'],
    'personal_leave' => ['label' => 'ลากิจ', 'color' => 'bg-blue-500', 'text' => 'text-white'],
    'sick_leave' => ['label' => 'ลาป่วย', 'color' => 'bg-purple-500', 'text' => 'text-white'],
];
?>

<div class="flex flex-col md:flex-row justify-between items-center p-6 border-b border-gray-100 gap-4">
    <div class="flex items-center space-x-4">
        <h3 class="text-lg font-bold text-slate-800 heading-font">
            <i class="fa fa-list-ul mr-2 text-primary"></i>รายชื่อนักเรียน ชั้น <?= $level ?>/<?= $room ?>
            <?php if ($isEditMode): ?>
                <span class="ml-2 px-3 py-1 bg-amber-100 text-amber-700 text-[10px] uppercase tracking-widest rounded-full">Edit Mode</span>
            <?php endif; ?>
        </h3>
    </div>
    <div class="flex items-center space-x-2">
        <button type="button" onclick="bulkSet('present')" class="px-4 py-2 bg-green-50 text-green-600 border border-green-200 rounded-xl text-xs font-bold hover:bg-green-100 transition-all">มาเรียนทั้งหมด</button>
        <button type="button" onclick="bulkSet('absent')" class="px-4 py-2 bg-gray-50 text-gray-600 border border-gray-200 rounded-xl text-xs font-bold hover:bg-gray-100 transition-all">ขาดเรียนทั้งหมด</button>
        <a href="<?= url('/attendance/export?date=' . $date . '&course_id=' . $courseId . '&class_level=' . urlencode($level) . '&room_number=' . $room) ?>" 
           class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-blue-200 hover:scale-[1.02] transition-all">
           <i class="fa fa-file-excel-o mr-1"></i>ส่งออก Excel
        </a>
    </div>
</div>

<form action="<?= url('/attendance/save') ?>" method="POST" id="attendance-form">
    <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
    <input type="hidden" name="date" value="<?= $date ?>">
    <input type="hidden" name="course_id" value="<?= $courseId ?>">
    <input type="hidden" name="class_level" value="<?= $level ?>">
    <input type="hidden" name="room_number" value="<?= $room ?>">

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-slate-400 text-[10px] font-bold uppercase tracking-widest">
                <tr>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">รหัส</th>
                    <th class="px-6 py-4">ชื่อ-นามสกุล</th>
                    <th class="px-6 py-4 text-center">สถานะการเข้าเรียน</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($students as $index => $s): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-slate-400"><?= $index + 1 ?></td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-700 outfit"><?= $s['student_code'] ?></td>
                    <td class="px-6 py-4 text-sm font-bold text-slate-800"><?= ($s['title'] ?? '') . $s['first_name'] . ' ' . $s['last_name'] ?></td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap justify-center gap-1">
                            <?php foreach ($statusOptions as $val => $opt): ?>
                                <?php 
                                    $id = "att_{$s['id']}_{$val}";
                                    // Default to 'present' if not set and not in edit mode (or just empty as requested)
                                    // User said: "ค่าเริ่มต้นของทุกคนว่างเปล่า หรือตั้งเป็น 'มาเรียน' ตามความเหมาะสม"
                                    // I'll default to 'present' for convenience if it's a new record.
                                    $isChecked = ($s['status'] === $val) ? 'checked' : (!$isEditMode && $val === 'present' ? 'checked' : '');
                                ?>
                                <label for="<?= $id ?>" class="cursor-pointer">
                                    <input type="radio" name="attendance[<?= $s['id'] ?>]" value="<?= $val ?>" id="<?= $id ?>" class="peer hidden" <?= $isChecked ?>>
                                    <span class="px-3 py-1.5 rounded-full text-[10px] font-bold border border-gray-200 text-gray-400 peer-checked:<?= $opt['color'] ?> peer-checked:<?= $opt['text'] ?> peer-checked:border-transparent transition-all">
                                        <?= $opt['label'] ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="p-8 border-t border-gray-100 bg-slate-50/50 flex justify-end">
        <button type="submit" class="px-10 py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm shadow-xl hover:shadow-2xl hover:scale-[1.02] active:scale-95 transition-all">
            <i class="fa fa-save mr-2"></i>บันทึกข้อมูลการเช็คชื่อ
        </button>
    </div>
</form>

<script>
    function bulkSet(status) {
        document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(radio => {
            radio.checked = true;
        });
        
        // Show notification
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: `เปลี่ยนสถานะเป็น ${status === 'present' ? 'มาเรียน' : 'ขาดเรียน'} ทั้งหมดแล้ว`,
            showConfirmButton: false,
            timer: 2000
        });
    }

    // Confirmation on submit
    document.getElementById('attendance-form').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'ยืนยันการบันทึก?',
            text: "คุณกำลังจะบันทึกสถานะการเข้าเรียนของนักเรียนทั้งหมด",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ใช่, บันทึกเลย',
            cancelButtonText: 'ยกเลิก',
            customClass: {
                confirmButton: 'px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm mx-2',
                cancelButton: 'px-6 py-3 bg-slate-400 text-white rounded-xl font-bold text-sm mx-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
