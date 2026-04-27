<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 md:p-8 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-800 heading-font">รายชื่อผู้ใช้งานทั้งหมด</h3>
            <p class="text-sm text-slate-500 mt-1">จัดการบัญชีผู้ใช้และกำหนดสิทธิ์การเข้าถึงระบบ</p>
        </div>
        <a href="/admin/users/create" class="inline-flex items-center px-6 py-3 bg-primary text-white text-sm font-bold rounded-2xl hover:shadow-lg hover:shadow-primary/30 transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            เพิ่มผู้ใช้งานใหม่
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ผู้ใช้งาน</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">บทบาท</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">สังกัด / บุคลากร</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">สถานะ</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">เข้าใช้งานล่าสุด</th>
                    <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-slate-50/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold mr-3 group-hover:scale-110 transition-transform">
                                <?= substr($user['username'], 0, 1) ?>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800"><?= $user['full_name'] ?></p>
                                <p class="text-xs text-slate-400"><?= $user['username'] ?> | <?= $user['email'] ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <?php 
                        $roleClass = [
                            'admin' => 'bg-red-100 text-red-600',
                            'editor' => 'bg-blue-100 text-blue-600',
                            'teacher' => 'bg-green-100 text-green-600'
                        ][$user['role']] ?? 'bg-gray-100 text-gray-600';
                        $roleName = [
                            'admin' => 'ผู้ดูแลระบบ',
                            'editor' => 'เจ้าหน้าที่ (Editor)',
                            'teacher' => 'ครู/บุคลากร'
                        ][$user['role']] ?? $user['role'];
                        ?>
                        <span class="px-3 py-1 text-[10px] font-bold rounded-lg uppercase <?= $roleClass ?>"><?= $roleName ?></span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-slate-600"><?= $user['personnel_name'] ?: '<span class="text-slate-300">ไม่ได้เชื่อมโยง</span>' ?></p>
                        <?php if ($user['department_name']): ?>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight"><?= $user['department_name'] ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($user['status'] === 'active'): ?>
                            <span class="flex items-center text-xs font-bold text-green-500">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span> Active
                            </span>
                        <?php else: ?>
                            <span class="flex items-center text-xs font-bold text-red-400">
                                <span class="w-2 h-2 rounded-full bg-red-400 mr-2"></span> Inactive
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-xs text-slate-500 italic"><?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'ยังไม่เคยเข้าใช้งาน' ?></p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="/admin/users/edit/<?= $user['id'] ?>" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <a href="/admin/users/delete/<?= $user['id'] ?>" onclick="return confirm('ยืนยันการลบผู้ใช้งานนี้?')" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
