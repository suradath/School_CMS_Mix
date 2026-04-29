<div class="mb-8 flex justify-between items-center">
    <div>
        <p class="text-slate-500 text-sm">จัดการลำดับและรายการเมนูที่แสดงบนหน้าเว็บไซต์หลัก</p>
    </div>
    <a href="<?= url('/settings/menu/create') ?>" class="bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-2xl transition shadow-lg shadow-blue-500/20 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        เพิ่มเมนู
    </a>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-gray-100">
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">ลำดับ</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">ไอคอน</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">เมนูหลัก</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">ชื่อเมนู</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">URL / Path</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">สถานะ</th>
                    <th class="px-8 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php foreach ($menus as $menu): ?>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-6">
                        <span class="text-sm font-bold text-slate-400 outfit"><?= $menu['sort_order'] ?></span>
                    </td>
                    <td class="px-8 py-6">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600">
                            <i class="fa <?= $menu['icon'] ?: 'fa-bars' ?> text-lg"></i>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-xs font-bold text-slate-500"><?= htmlspecialchars($menu['parent_title']) ?></span>
                    </td>
                    <td class="px-8 py-6">
                        <span class="text-slate-900 font-bold"><?= $menu['title'] ?></span>
                    </td>
                    <td class="px-8 py-6">
                        <code class="text-xs font-bold text-primary bg-primary/5 px-2 py-1 rounded"><?= $menu['url'] ?></code>
                    </td>
                    <td class="px-8 py-6">
                        <?php if ($menu['is_active']): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span> เปิดใช้งาน
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-50 text-slate-400 border border-slate-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-2"></span> ปิดใช้งาน
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex justify-end space-x-2">
                            <a href="<?= url('/settings/menu/edit/' . $menu['id']) ?>" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <a href="<?= url('/settings/menu/delete/' . $menu['id']) ?>" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบเมนูนี้?')" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
