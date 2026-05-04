<div class="mb-8">
    <a href="<?= url('/club') ?>" class="inline-flex items-center text-slate-400 hover:text-primary transition-colors font-bold mb-4">
        <i class="fa fa-arrow-left mr-2"></i> กลับหน้าจัดการ
    </a>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h2 class="text-3xl font-black text-slate-800 heading-font">ประเมินผลการเรียน</h2>
            <p class="text-slate-500 font-medium">ชุมนุม: <?= htmlspecialchars($club['name']) ?></p>
        </div>
        <div class="flex gap-2">
            <form id="filterForm" method="GET" action="<?= url('/club/evaluation') ?>" class="flex gap-2">
                <input type="hidden" name="club_id" value="<?= $club['id'] ?>">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">ภาคเรียน</label>
                    <select name="semester" onchange="this.form.submit()" class="px-5 py-3 rounded-2xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all outline-none text-slate-600 font-bold">
                        <option value="1" <?= ($semester == '1') ? 'selected' : '' ?>>1</option>
                        <option value="2" <?= ($semester == '2') ? 'selected' : '' ?>>2</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 ml-1">ปีการศึกษา</label>
                    <input type="number" name="year" value="<?= $year ?>" onchange="this.form.submit()" class="w-32 px-5 py-3 rounded-2xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all outline-none text-slate-600 font-bold">
                </div>
            </form>
        </div>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
    <form action="<?= url('/club/evaluation/save') ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
        <input type="hidden" name="club_id" value="<?= $club['id'] ?>">
        <input type="hidden" name="semester" value="<?= $semester ?>">
        <input type="hidden" name="year" value="<?= $year ?>">

        <div class="overflow-x-auto p-8">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] heading-font">
                        <th class="px-8 py-5">รหัสนักเรียน</th>
                        <th class="px-8 py-5">ชื่อ-นามสกุล</th>
                        <th class="px-8 py-5">ผลการประเมิน</th>
                        <th class="px-8 py-5">หมายเหตุ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (empty($members)): ?>
                        <tr><td colspan="4" class="px-8 py-10 text-center text-slate-400 font-medium">ยังไม่มีนักเรียนสมัครเข้าร่วมชุมนุม</td></tr>
                    <?php endif; ?>
                    <?php foreach ($members as $m): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5 text-sm font-bold text-slate-600"><?= $m['student_code'] ?></td>
                            <td class="px-8 py-5 font-bold text-slate-900"><?= $m['first_name'] . ' ' . $m['last_name'] ?></td>
                            <td class="px-8 py-5">
                                <div class="flex gap-4">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="result[<?= $m['student_id'] ?>]" value="P" <?= (!isset($evalMap[$m['student_id']]) || $evalMap[$m['student_id']]['result'] === 'P') ? 'checked' : '' ?> class="w-5 h-5 text-emerald-500 border-slate-300 focus:ring-emerald-500">
                                        <span class="ml-2 font-bold text-slate-600 group-hover:text-emerald-600 transition-colors">ผ (ผ่าน)</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="radio" name="result[<?= $m['student_id'] ?>]" value="F" <?= (isset($evalMap[$m['student_id']]) && $evalMap[$m['student_id']]['result'] === 'F') ? 'checked' : '' ?> class="w-5 h-5 text-rose-500 border-slate-300 focus:ring-rose-500">
                                        <span class="ml-2 font-bold text-slate-600 group-hover:text-rose-600 transition-colors">มผ (ไม่ผ่าน)</span>
                                    </label>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <input type="text" name="remarks[<?= $m['student_id'] ?>]" value="<?= htmlspecialchars($evalMap[$m['student_id']]['remarks'] ?? '') ?>" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:border-primary outline-none text-sm" placeholder="หมายเหตุ...">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($members)): ?>
        <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-end">
            <button type="submit" class="px-10 py-4 bg-primary text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-primary/30 transition-all">
                <i class="fa fa-save mr-2"></i> บันทึกการประเมินผล
            </button>
        </div>
        <?php endif; ?>
    </form>
</div>
