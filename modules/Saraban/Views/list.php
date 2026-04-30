<div class="mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-xl font-bold text-slate-800 heading-font"><?= $title ?></h3>
        <p class="text-sm text-slate-500 mt-1">รายการหนังสือทั้งหมดในหมวดหมู่นี้</p>
    </div>
    <?php if (\Core\Security::checkRole(['admin', 'officer'])): ?>
    <a href="<?= url('/saraban/create/' . $type) ?>" class="bg-primary text-white px-6 py-3 rounded-2xl font-bold text-xs hover:shadow-lg hover:shadow-primary/20 transition-all flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
        ลงทะเบียนใหม่
    </a>
    <?php endif; ?>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-sm font-bold">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<!-- Search Form -->
<form action="" method="GET" class="mb-8 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex flex-wrap items-end gap-4">
    <div class="flex-grow min-w-[200px]">
        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">ค้นหาจากชื่อเรื่อง / เลขที่</label>
        <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="ค้นหา..." class="w-full px-4 py-2.5 rounded-xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary transition-all text-sm">
    </div>
    <div class="w-40">
        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">ตั้งแต่วันที่</label>
        <input type="date" name="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary transition-all text-sm">
    </div>
    <div class="w-40">
        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">ถึงวันที่</label>
        <input type="date" name="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary transition-all text-sm">
    </div>
    <div class="w-40">
        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">สถานะ</label>
        <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary transition-all text-sm">
            <option value="">ทั้งหมด</option>
            <option value="active" <?= ($_GET['status'] ?? '') === 'active' ? 'selected' : '' ?>>ปกติ</option>
            <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>ยกเลิก</option>
        </select>
    </div>
    <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-800 transition-all flex items-center h-[42px]">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        ค้นหา
    </button>
    <a href="<?= url('/saraban/' . $type) ?>" class="px-4 py-2.5 text-slate-400 hover:text-slate-600 text-xs font-bold h-[42px] flex items-center">ล้างค่า</a>
</form>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50">
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">สถานะ</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">เลขที่รับ/หนังสือ</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">เรื่อง</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">จากหน่วยงาน</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ความเร่งด่วน</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">วันที่</th>
                    <th class="px-8 py-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">เครื่องมือ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center text-slate-400 italic">ไม่พบรายการข้อมูลในทะเบียนนี้</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-8 py-5">
                            <?php 
                                $sConfig = [
                                    'pending' => ['label' => 'รอเกษียณ', 'class' => 'bg-rose-50 text-rose-600'],
                                    'minuted' => ['label' => 'เกษียณแล้ว', 'class' => 'bg-amber-50 text-amber-600'],
                                    'processed' => ['label' => 'สั่งการแล้ว', 'class' => 'bg-emerald-50 text-emerald-600'],
                                ];
                                $st = $item['saraban_status'] ?? 'pending';
                                $cfg = $sConfig[$st] ?? ['label' => 'ไม่ระบุ', 'class' => 'bg-slate-100 text-slate-600'];
                            ?>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold <?= $cfg['class'] ?>">
                                <?= $cfg['label'] ?>
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-bold text-slate-900"><?= $item['doc_no'] ?></div>
                            <div class="text-[10px] text-slate-400"><?= $item['book_no'] ?></div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm text-slate-800 font-medium line-clamp-1"><?= $item['title'] ?></div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm text-slate-600"><?= $item['origin'] ?></div>
                        </td>
                        <td class="px-8 py-5">
                            <?php 
                                $priorityClass = [
                                    'normal' => 'bg-slate-100 text-slate-600',
                                    'urgent' => 'bg-amber-100 text-amber-600',
                                    'very_urgent' => 'bg-rose-100 text-rose-600'
                                ];
                                $priorityLabel = [
                                    'normal' => 'ปกติ',
                                    'urgent' => 'ด่วน',
                                    'very_urgent' => 'ด่วนที่สุด'
                                ];
                            ?>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold <?= $priorityClass[$item['priority']] ?>">
                                <?= $priorityLabel[$item['priority']] ?>
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm text-slate-600"><?= date('d/m/Y', strtotime($item['doc_date'])) ?></div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex space-x-2">
                                <a href="<?= url('/saraban/view/' . $item['id']) ?>" class="p-2 text-slate-400 hover:text-primary transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <?php if ($item['file_url']): ?>
                                <a href="<?= url('/saraban/file/' . $item['id']) ?>" target="_blank" class="p-2 text-slate-400 hover:text-emerald-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
