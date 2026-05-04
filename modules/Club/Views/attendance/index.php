<div class="mb-8">
    <a href="<?= url('/club') ?>" class="inline-flex items-center text-slate-400 hover:text-primary transition-colors font-bold mb-4">
        <i class="fa fa-arrow-left mr-2"></i> กลับหน้าจัดการ
    </a>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800 heading-font">บันทึกการเข้าเรียน</h2>
            <p class="text-slate-500 font-medium">ชุมนุม: <?= htmlspecialchars($club['name']) ?></p>
        </div>
        <div>
            <form id="dateForm" method="GET" action="<?= url('/club/attendance') ?>">
                <input type="hidden" name="club_id" value="<?= $club['id'] ?>">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">เลือกวันที่</label>
                <input type="date" name="date" value="<?= $date ?>" onchange="this.form.submit()" class="px-5 py-3 rounded-2xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all outline-none text-slate-600 font-bold">
            </form>
        </div>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
    <form action="<?= url('/club/attendance/save') ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
        <input type="hidden" name="club_id" value="<?= $club['id'] ?>">
        <input type="hidden" name="date" value="<?= $date ?>">

        <div class="overflow-x-auto p-8">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] heading-font">
                        <th class="px-8 py-5">รหัสนักเรียน</th>
                        <th class="px-8 py-5">ชื่อ-นามสกุล</th>
                        <th class="px-8 py-5">ชั้น/ห้อง</th>
                        <th class="px-8 py-5 text-center">มาเรียน</th>
                        <th class="px-8 py-5 text-center">ขาด</th>
                        <th class="px-8 py-5 text-center">ลา</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($members)): ?>
                        <tr><td colspan="6" class="px-8 py-10 text-center text-slate-400 font-medium">ยังไม่มีนักเรียนสมัครเข้าร่วมชุมนุม</td></tr>
                    <?php endif; ?>
                    <?php foreach ($members as $m): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5 text-sm font-bold text-slate-600"><?= $m['student_code'] ?></td>
                            <td class="px-8 py-5 font-bold text-slate-900"><?= $m['first_name'] . ' ' . $m['last_name'] ?></td>
                            <td class="px-8 py-5 text-sm text-slate-500 font-medium"><?= $m['class_level'] ?> / <?= $m['room_number'] ?></td>
                            <td class="px-8 py-5 text-center">
                                <label class="cursor-pointer inline-flex items-center">
                                    <input type="radio" name="attendance[<?= $m['student_id'] ?>]" value="present" <?= (!isset($attMap[$m['student_id']]) || $attMap[$m['student_id']] === 'present') ? 'checked' : '' ?> class="w-6 h-6 text-emerald-500 border-slate-300 focus:ring-emerald-500">
                                </label>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <label class="cursor-pointer inline-flex items-center">
                                    <input type="radio" name="attendance[<?= $m['student_id'] ?>]" value="absent" <?= (isset($attMap[$m['student_id']]) && $attMap[$m['student_id']] === 'absent') ? 'checked' : '' ?> class="w-6 h-6 text-rose-500 border-slate-300 focus:ring-rose-500">
                                </label>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <label class="cursor-pointer inline-flex items-center">
                                    <input type="radio" name="attendance[<?= $m['student_id'] ?>]" value="leave" <?= (isset($attMap[$m['student_id']]) && $attMap[$m['student_id']] === 'leave') ? 'checked' : '' ?> class="w-6 h-6 text-amber-500 border-slate-300 focus:ring-amber-500">
                                </label>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($members)): ?>
        <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-10 py-4 bg-primary text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-primary/30 transition-all">
                <i class="fa fa-save mr-2"></i> บันทึกการเช็คชื่อ
            </button>
        </div>
        <?php endif; ?>
    </form>
</div>
