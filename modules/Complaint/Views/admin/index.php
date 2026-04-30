<div class="space-y-8">
    <!-- Stats / Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <?php 
        $stats = [
            'total' => count($complaints),
            'unread' => count(array_filter($complaints, fn($c) => $c['status'] === 'unread')),
            'in_progress' => count(array_filter($complaints, fn($c) => $c['status'] === 'in_progress')),
            'resolved' => count(array_filter($complaints, fn($c) => $c['status'] === 'resolved')),
        ];
        ?>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center">
            <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mr-4">
                <i class="fa fa-list-ul text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">ทั้งหมด</p>
                <p class="text-2xl font-black text-slate-900"><?= $stats['total'] ?></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center">
            <div class="w-14 h-14 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mr-4">
                <i class="fa fa-envelope text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">ยังไม่อ่าน</p>
                <p class="text-2xl font-black text-slate-900"><?= $stats['unread'] ?></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center">
            <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mr-4">
                <i class="fa fa-spinner fa-spin text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">กำลังดำเนินการ</p>
                <p class="text-2xl font-black text-slate-900"><?= $stats['in_progress'] ?></p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mr-4">
                <i class="fa fa-check-circle text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">ยุติเรื่องแล้ว</p>
                <p class="text-2xl font-black text-slate-900"><?= $stats['resolved'] ?></p>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
            <div>
                <h3 class="text-xl font-bold text-slate-900 heading-font">รายการเรื่องร้องเรียน</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">จัดการและติดตามสถานะข้อร้องเรียนจากบุคคลทั่วไป</p>
            </div>
            <div class="flex items-center space-x-2">
                <select id="statusFilter" class="bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-600 px-4 py-2 focus:ring-2 focus:ring-primary/10">
                    <option value="">ทั้งหมด</option>
                    <option value="unread">ยังไม่อ่าน</option>
                    <option value="read">อ่านแล้ว</option>
                    <option value="in_progress">กำลังดำเนินการ</option>
                    <option value="resolved">ยุติเรื่อง</option>
                </select>
            </div>
        </div>

        <div class="p-8">
            <table id="complaintTable" class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] border-b border-slate-50">
                        <th class="px-4 py-6">วันที่-เวลา</th>
                        <th class="px-4 py-6">หัวข้อ</th>
                        <th class="px-4 py-6">ผู้แจ้ง</th>
                        <th class="px-4 py-6 text-center">สถานะ</th>
                        <th class="px-4 py-6 text-right">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($complaints as $item): ?>
                        <tr class="group hover:bg-slate-50/50 transition-colors <?= $item['status'] === 'unread' ? 'font-bold bg-blue-50/30' : '' ?>">
                            <td class="px-4 py-6 text-sm text-slate-500 outfit">
                                <?= date('d/m/Y H:i', strtotime($item['created_at'])) ?>
                            </td>
                            <td class="px-4 py-6">
                                <div class="text-sm text-slate-900"><?= htmlspecialchars($item['topic']) ?></div>
                                <?php if ($item['attachment']): ?>
                                    <span class="inline-flex items-center mt-1 text-[10px] text-primary bg-primary/5 px-2 py-0.5 rounded-lg">
                                        <i class="fa fa-paperclip mr-1"></i> มีรูปประกอบ
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-6">
                                <div class="text-sm text-slate-700"><?= htmlspecialchars($item['contact_name'] ?: 'ไม่ระบุตัวตน') ?></div>
                                <div class="text-[11px] text-slate-400 mt-0.5"><?= htmlspecialchars($item['contact_info'] ?: '-') ?></div>
                            </td>
                            <td class="px-4 py-6 text-center">
                                <?php 
                                $statusMap = [
                                    'unread' => ['label' => 'ยังไม่อ่าน', 'class' => 'bg-rose-100 text-rose-600'],
                                    'read' => ['label' => 'รับทราบ', 'class' => 'bg-blue-100 text-blue-600'],
                                    'in_progress' => ['label' => 'กำลังดำเนินการ', 'class' => 'bg-amber-100 text-amber-600'],
                                    'resolved' => ['label' => 'ยุติเรื่อง', 'class' => 'bg-emerald-100 text-emerald-600'],
                                ];
                                $s = $statusMap[$item['status']];
                                ?>
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full <?= $s['class'] ?>">
                                    <?= $s['label'] ?>
                                </span>
                            </td>
                            <td class="px-4 py-6 text-right">
                                <a href="<?= url('/admin/complaints/view/' . $item['id']) ?>" 
                                    class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all shadow-sm">
                                    อ่านรายละเอียด
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const table = initPremiumDataTable('#complaintTable', {
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: [4] }
            ]
        });

        $('#statusFilter').on('change', function() {
            table.column(3).search(this.value ? '^' + $(this).find('option:selected').text() + '$' : '', true, false).draw();
        });
    });
</script>
