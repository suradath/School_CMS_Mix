<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 md:p-8 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800 heading-font">รายชื่อผู้ใช้งานทั้งหมด</h3>
            <p class="text-sm text-slate-500 mt-1">จัดการบัญชีผู้ใช้และกำหนดสิทธิ์การเข้าถึงระบบ</p>
        </div>
        <a href="<?= url('/admin/users/create') ?>" class="inline-flex items-center px-6 py-3 bg-primary text-white text-sm font-bold rounded-2xl hover:shadow-lg hover:shadow-primary/30 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            เพิ่มผู้ใช้งานใหม่
        </a>
    </div>

    <div class="overflow-x-auto">
        <table id="userTable" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ผู้ใช้งาน</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">บทบาท</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">สังกัด / บุคลากร</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">สถานะ</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">เข้าใช้งานล่าสุด</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-slate-50/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold mr-3 group-hover:scale-110 transition-transform shrink-0">
                                <?= substr($user['username'], 0, 1) ?>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800"><?= htmlspecialchars($user['full_name']) ?></p>
                                <p class="text-[11px] text-slate-400"><?= htmlspecialchars($user['username']) ?> | <?= htmlspecialchars($user['email']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            <?php 
                            if (!empty($user['roles_slugs'])) {
                                $slugs = explode(',', $user['roles_slugs']);
                                $names = explode(', ', $user['roles_display']);
                                
                                foreach ($slugs as $index => $slug) {
                                    $roleName = $names[$index] ?? $slug;
                                    $roleClass = [
                                        'admin' => 'bg-rose-100 text-rose-600',
                                        'editor' => 'bg-blue-100 text-blue-600',
                                        'teacher' => 'bg-emerald-100 text-emerald-600',
                                        'dept_head' => 'bg-purple-100 text-purple-600',
                                        'staff' => 'bg-orange-100 text-orange-600',
                                        'director' => 'bg-indigo-100 text-indigo-600',
                                        'hr' => 'bg-pink-100 text-pink-600',
                                        'officer' => 'bg-cyan-100 text-cyan-600'
                                    ][$slug] ?? 'bg-gray-100 text-gray-600';
                                    echo '<span class="px-2 py-0.5 text-[9px] font-bold rounded-md uppercase ' . $roleClass . '">' . htmlspecialchars($roleName) . '</span>';
                                }
                            } else {
                                echo '<span class="px-2 py-0.5 text-[9px] font-bold rounded-md uppercase bg-gray-50 text-gray-400">ไม่มีบทบาท</span>';
                            }
                            ?>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-slate-600"><?= $user['personnel_name'] ? htmlspecialchars($user['personnel_name']) : '<span class="text-slate-300">ไม่ได้เชื่อมโยง</span>' ?></p>
                        <?php if ($user['department_name']): ?>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight"><?= htmlspecialchars($user['department_name']) ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($user['status'] === 'active'): ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-600 uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Active
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-slate-100 text-slate-400 uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-1.5"></span> Inactive
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-xs text-slate-500 italic"><?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'ยังไม่เคยเข้าใช้งาน' ?></p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-1">
                            <a href="<?= url('/admin/users/edit/' . $user['id']) ?>" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all" title="แก้ไข">
                                <i class="fa fa-pencil text-lg"></i>
                            </a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <button onclick="confirmDelete(<?= $user['id'] ?>)" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all" title="ลบ">
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
    $(document).ready(function() {
        initPremiumDataTable('#userTable', {
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [5] }
            ]
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'ยืนยันการลบผู้ใช้งาน?',
            text: "การลบข้อมูลผู้ใช้นี้ไม่สามารถกู้คืนได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ใช่, ลบเลย',
            cancelButtonText: 'ยกเลิก',
            borderRadius: '1.5rem',
            customClass: {
                confirmButton: 'rounded-xl font-bold px-6 py-3',
                cancelButton: 'rounded-xl font-bold px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= url('/admin/users/delete/') ?>" + id;
            }
        });
    }
</script>
