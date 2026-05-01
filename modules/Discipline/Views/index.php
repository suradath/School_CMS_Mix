<div class="space-y-8">
    <!-- Search & Filter Card -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 bg-gradient-to-br from-white to-slate-50/50">
        <form method="GET" action="<?= url('/discipline') ?>" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ชั้นเรียน</label>
                <select name="class" class="w-full bg-white border border-slate-100 rounded-2xl px-4 py-3.5 text-slate-700 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none appearance-none">
                    <option value="">ทั้งหมด</option>
                    <?php for($i=1; $i<=6; $i++): ?>
                        <option value="ม.<?= $i ?>" <?= ($selectedClass == "ม.$i") ? 'selected' : '' ?>>มัธยมศึกษาปีที่ <?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ห้องเรียน</label>
                <input type="number" name="room" value="<?= $selectedRoom ?: '' ?>" class="w-full bg-white border border-slate-100 rounded-2xl px-4 py-3.5 text-slate-700 focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none" placeholder="ระบุเลขห้อง (เช่น 1)">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:shadow-xl hover:shadow-slate-900/20 hover:-translate-y-0.5 transition-all flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    ค้นหาข้อมูล
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Summary Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center group hover:border-primary/20 transition-all">
            <div class="p-4 rounded-2xl bg-primary/10 text-primary mr-4 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">นักเรียนทั้งหมด</p>
                <p class="text-2xl font-black text-slate-800 outfit"><?= count($students) ?> <span class="text-sm font-medium text-slate-400 ml-1">คน</span></p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center group hover:border-emerald-500/20 transition-all">
            <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-600 mr-4 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">คะแนนพฤติกรรมเฉลี่ย</p>
                <p class="text-2xl font-black text-slate-800 outfit">
                    <?php 
                        $avg = count($students) > 0 ? array_sum(array_column($students, 'total_score')) / count($students) : 0;
                        echo number_format($avg, 1);
                    ?>
                </p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center group hover:border-rose-500/20 transition-all">
            <div class="p-4 rounded-2xl bg-rose-50 text-rose-600 mr-4 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">นักเรียนที่มีคะแนนติดลบ</p>
                <p class="text-2xl font-black text-slate-800 outfit">
                    <?= count(array_filter($students, fn($s) => $s['total_score'] < 0)) ?> <span class="text-sm font-medium text-slate-400 ml-1">คน</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gradient-to-r from-white to-slate-50/50">
            <div>
                <h3 class="text-xl font-bold text-slate-800 heading-font">คะแนนพฤติกรรมนักเรียน</h3>
                <p class="text-sm text-slate-500 mt-1">สรุปคะแนนความประพฤติรายบุคคล</p>
            </div>
        </div>
        <div class="p-8">
            <div class="overflow-x-auto">
                <table id="studentDisciplineTable" class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">รหัสนักเรียน</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">ชื่อ-นามสกุล</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">ชั้น/ห้อง</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">คะแนนสะสม</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] bg-slate-50/50">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php foreach ($students as $s): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 outfit font-semibold text-slate-500"><?= $s['student_code'] ?></td>
                            <td class="px-6 py-4 font-bold text-slate-700"><?= $s['title'] . $s['first_name'] . ' ' . $s['last_name'] ?></td>
                            <td class="px-6 py-4 text-slate-600 font-medium"><?= $s['class_level'] . '/' . $s['room_number'] ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="text-lg font-black outfit <?= $s['total_score'] >= 0 ? 'text-emerald-600' : 'text-rose-600' ?>">
                                        <?= $s['total_score'] > 0 ? '+' . $s['total_score'] : $s['total_score'] ?>
                                    </span>
                                    <div class="ml-3 w-24 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                        <div class="h-full <?= $s['total_score'] >= 0 ? 'bg-emerald-500' : 'bg-rose-500' ?>" style="width: <?= min(100, abs($s['total_score'])) ?>%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <a href="<?= url('/discipline/record?student_id=' . $s['id']) ?>" class="px-4 py-2 bg-primary/10 text-primary rounded-xl text-xs font-bold hover:bg-primary hover:text-white transition-all flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        บันทึกใหม่
                                    </a>
                                    <a href="<?= url('/discipline/history?student_id=' . $s['id']) ?>" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all">
                                        ประวัติ
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        initPremiumDataTable('#studentDisciplineTable', {
            order: [[3, 'asc']]
        });
    });
</script>
