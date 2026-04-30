<div class="max-w-5xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <a href="<?= url('/admin/complaints') ?>" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors">
            <i class="fa fa-arrow-left mr-2"></i> กลับไปหน้ารายการ
        </a>
        <div class="flex items-center space-x-3">
            <?php if (\Core\Security::hasRole('admin')): ?>
                <button onclick="confirmDelete(<?= $item['id'] ?>)" class="p-3 text-rose-500 hover:bg-rose-50 rounded-2xl transition-colors shadow-sm bg-white border border-rose-50">
                    <i class="fa fa-trash-o"></i>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Content Column -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="px-4 py-1.5 bg-primary/5 text-primary text-[10px] font-black uppercase tracking-widest rounded-full">
                            Complaint Details
                        </div>
                        <span class="text-xs font-bold text-slate-400 outfit"><?= date('d M Y, H:i', strtotime($item['created_at'])) ?></span>
                    </div>

                    <h1 class="text-3xl font-black text-slate-900 heading-font mb-6 leading-tight"><?= htmlspecialchars($item['topic']) ?></h1>
                    
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed font-medium">
                        <?= nl2br(htmlspecialchars($item['details'])) ?>
                    </div>

                    <?php if ($item['attachment']): ?>
                        <div class="mt-12 pt-8 border-t border-slate-50">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">รูปภาพประกอบ</p>
                            <a href="<?= url($item['attachment']) ?>" target="_blank" class="block group relative rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all">
                                <img src="<?= url($item['attachment']) ?>" alt="Attachment" class="w-full h-auto group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="px-6 py-3 bg-white text-slate-900 font-bold rounded-2xl shadow-xl">คลิกเพื่อดูภาพขนาดใหญ่</span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="space-y-8">
            <!-- Status Card -->
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">จัดการสถานะ</p>
                
                <div class="space-y-3">
                    <?php 
                    $statuses = [
                        'read' => ['label' => 'รับทราบเรื่อง', 'icon' => 'fa-check', 'color' => 'blue'],
                        'in_progress' => ['label' => 'กำลังดำเนินการ', 'icon' => 'fa-spinner fa-spin', 'color' => 'amber'],
                        'resolved' => ['label' => 'ยุติเรื่องแล้ว', 'icon' => 'fa-check-circle', 'color' => 'emerald'],
                    ];
                    foreach ($statuses as $val => $data):
                        $active = $item['status'] === $val;
                    ?>
                        <button onclick="updateStatus('<?= $val ?>')" 
                            class="w-full flex items-center justify-between p-4 rounded-2xl transition-all group <?= $active ? "bg-{$data['color']}-500 text-white shadow-lg shadow-{$data['color']}-200" : "bg-slate-50 text-slate-600 hover:bg-slate-100" ?>">
                            <div class="flex items-center">
                                <i class="fa <?= $data['icon'] ?> mr-3 <?= $active ? 'text-white' : "text-{$data['color']}-500" ?>"></i>
                                <span class="text-sm font-bold"><?= $data['label'] ?></span>
                            </div>
                            <?php if ($active): ?>
                                <i class="fa fa-dot-circle-o"></i>
                            <?php endif; ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Contact Info Card -->
            <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl shadow-slate-200 text-white relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-primary/10 rounded-full blur-3xl"></div>
                <p class="text-[10px] font-bold text-blue-300 uppercase tracking-widest mb-6">ข้อมูลผู้ติดต่อ</p>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center mr-4 shrink-0">
                            <i class="fa fa-user text-blue-300"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">ชื่อผู้แจ้ง</p>
                            <p class="text-sm font-bold"><?= htmlspecialchars($item['contact_name'] ?: 'ไม่ประสงค์ออกนาม') ?></p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center mr-4 shrink-0">
                            <i class="fa fa-phone text-blue-300"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">ช่องทางติดต่อ</p>
                            <p class="text-sm font-bold"><?= htmlspecialchars($item['contact_info'] ?: '-') ?></p>
                        </div>
                    </div>

                    <?php if ($item['read_by']): 
                        $reader = \Core\Database::fetch("SELECT name FROM users WHERE id = ?", [$item['read_by']]);
                    ?>
                        <div class="flex items-start pt-6 border-t border-white/10">
                            <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center mr-4 shrink-0">
                                <i class="fa fa-eye text-emerald-300"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">เปิดอ่านครั้งแรกโดย</p>
                                <p class="text-sm font-bold text-emerald-300"><?= htmlspecialchars($reader['name'] ?? 'Unknown') ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateStatus(status) {
        Swal.fire({
            title: 'ยืนยันการเปลี่ยนสถานะ?',
            text: 'ระบบจะเปลี่ยนสถานะเป็น: ' + status,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'ยืนยัน',
            cancelButtonText: 'ยกเลิก',
            customClass: {
                confirmButton: 'px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm mx-2',
                cancelButton: 'px-6 py-3 bg-slate-400 text-white rounded-xl font-bold text-sm mx-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= url('/admin/complaints/update-status') ?>', {
                    id: <?= $item['id'] ?>,
                    status: status,
                    csrf_token: '<?= \Core\Security::csrf_token() ?>'
                }, function(res) {
                    const data = JSON.parse(res);
                    if (data.success) {
                        Swal.fire('สำเร็จ!', 'อัปเดตสถานะเรียบร้อยแล้ว', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('ผิดพลาด', data.message, 'error');
                    }
                });
            }
        });
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'ลบข้อมูล?',
            text: 'คุณต้องการลบข้อร้องเรียนนี้ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'ใช่, ลบข้อมูล',
            cancelButtonText: 'ยกเลิก',
            customClass: {
                confirmButton: 'px-6 py-3 bg-rose-500 text-white rounded-xl font-bold text-sm mx-2',
                cancelButton: 'px-6 py-3 bg-slate-400 text-white rounded-xl font-bold text-sm mx-2'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= url('/admin/complaints/delete') ?>', {
                    id: id,
                    csrf_token: '<?= \Core\Security::csrf_token() ?>'
                }, function(res) {
                    const data = JSON.parse(res);
                    if (data.success) {
                        window.location.href = '<?= url('/admin/complaints') ?>';
                    } else {
                        Swal.fire('ผิดพลาด', data.message, 'error');
                    }
                });
            }
        });
    }
</script>
