<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
    <div>
        <h2 class="text-3xl font-black text-slate-800 heading-font">จัดการชุมนุม</h2>
        <p class="text-slate-500 font-medium">จัดการข้อมูลชุมนุมและผลการดำเนินงาน</p>
    </div>
    <div class="flex gap-2">
        <?php if (hasRole('admin')): ?>
            <a href="<?= url('/club/settings') ?>"
                class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-600 text-sm font-bold rounded-2xl hover:bg-slate-200 transition-all">
                <i class="fa fa-cog mr-2"></i> ตั้งค่าระบบ
            </a>
        <?php endif; ?>
        <a href="<?= url('/club/create') ?>"
            class="inline-flex items-center px-6 py-3 bg-primary text-white text-sm font-bold rounded-2xl hover:shadow-lg hover:shadow-primary/30 transition-all">
            <i class="fa fa-plus mr-2"></i> เพิ่มชุมนุม
        </a>
    </div>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden p-8">
    <div class="overflow-x-auto">
        <table id="clubTable" class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] heading-font">
                    <th class="px-8 py-5">ชื่อชุมนุม</th>
                    <th class="px-8 py-5">ครูประจำชุมนุม</th>
                    <th class="px-8 py-5">สถานที่</th>
                    <th class="px-8 py-5">จำนวนที่รับ</th>
                    <th class="px-8 py-5">ระดับชั้นที่รับ</th>
                    <th class="px-8 py-5">สถานะ</th>
                    <th class="px-8 py-5 text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($clubs as $c): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="font-bold text-slate-900 heading-font text-base"><?= htmlspecialchars($c['name']) ?>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-500 font-medium">
                            <?= htmlspecialchars($c['advisor_name'] ?? 'ไม่ระบุ') ?>
                        </td>
                        <td class="px-8 py-5 text-sm text-slate-500">
                            <?= htmlspecialchars($c['location'] ?? '-') ?>
                        </td>
                        <td class="px-8 py-5 text-sm">
                            <div class="flex items-center">
                                <span class="font-bold text-slate-900"><?= $c['current_count'] ?></span>
                                <span class="mx-1 text-slate-300">/</span>
                                <span class="text-slate-500"><?= $c['capacity'] ?></span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <?php $grades = json_decode($c['target_grades'], true) ?: []; ?>
                            <div class="flex flex-wrap gap-1">
                                <?php foreach ($grades as $g): ?>
                                    <span
                                        class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded text-[10px] font-bold"><?= $g ?></span>
                                <?php endforeach; ?>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <?php if ($c['status'] === 'open'): ?>
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-full">เปิดรับสมัคร</span>
                            <?php elseif ($c['status'] === 'full'): ?>
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-widest rounded-full">เต็มแล้ว</span>
                            <?php else: ?>
                                <span
                                    class="inline-flex items-center px-3 py-1 bg-rose-100 text-rose-700 text-[10px] font-black uppercase tracking-widest rounded-full">ปิดรับสมัคร</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end space-x-1">
                                <a href="<?= url('/club/attendance?club_id=' . $c['id']) ?>"
                                    class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all"
                                    title="เช็คชื่อ">
                                    <i class="fa fa-calendar-check-o text-lg"></i>
                                </a>
                                <a href="<?= url('/club/summary?club_id=' . $c['id']) ?>"
                                    class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all"
                                    title="สรุปการเข้าเรียน">
                                    <i class="fa fa-list-alt text-lg"></i>
                                </a>
                                <a href="<?= url('/club/evaluation?club_id=' . $c['id']) ?>"
                                    class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all"
                                    title="ประเมินผล">
                                    <i class="fa fa-star text-lg"></i>
                                </a>
                                <a href="<?= url('/club/export?club_id=' . $c['id']) ?>"
                                    class="p-2 text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 rounded-xl transition-all"
                                    title="ส่งออก Excel">
                                    <i class="fa fa-file-excel-o text-lg"></i>
                                </a>
                                <a href="<?= url('/club/edit?id=' . $c['id']) ?>"
                                    class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all"
                                    title="แก้ไข">
                                    <i class="fa fa-pencil text-lg"></i>
                                </a>
                                <?php if (hasRole('admin')): ?>
                                    <button onclick="confirmDelete(<?= $c['id'] ?>)"
                                        class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all"
                                        title="ลบ">
                                        <i class="fa fa-trash-o text-lg"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        initPremiumDataTable('#clubTable', {
            order: [[0, 'asc']]
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบชุมนุม?',
            text: "ข้อมูลการสมัครและเช็คชื่อทั้งหมดจะถูกลบออก",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ใช่, ลบเลย',
            cancelButtonText: 'ยกเลิก',
            borderRadius: '1.5rem'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= url('/club/delete?id=') ?>' + id;
            }
        });
    }
</script>