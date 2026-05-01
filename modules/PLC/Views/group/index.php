<div class="max-w-7xl mx-auto space-y-8" x-data="{ showModal: false }">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 heading-font">จัดการกลุ่ม PLC</h1>
            <p class="text-slate-500 mt-1">สร้างกลุ่ม ค้นหากลุ่ม และบริหารจัดการสมาชิก</p>
        </div>
        <button @click="showModal = true" class="px-8 py-3 bg-primary text-white font-bold rounded-2xl hover:shadow-lg hover:shadow-primary/30 transition flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            สร้างกลุ่ม PLC ใหม่
        </button>
    </div>

    <!-- Group List Card -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8">
            <table class="w-full text-left datatable">
                <thead>
                    <tr class="text-slate-400 text-xs font-bold uppercase tracking-widest border-b border-slate-50">
                        <th class="pb-4 pl-4">ชื่อกลุ่ม</th>
                        <th class="pb-4 text-center">ปีการศึกษา (พ.ศ.)</th>
                        <th class="pb-4 text-center">จำนวนสมาชิก</th>
                        <th class="pb-4">ผู้สร้างกลุ่ม</th>
                        <th class="pb-4 text-right pr-4">การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php foreach ($groups as $group): ?>
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="py-5 pl-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary mr-4 group-hover:bg-primary group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-700 group-hover:text-primary transition-colors"><?= $group['name'] ?></p>
                                    <p class="text-xs text-slate-400 mt-0.5"><?= mb_strimwidth($group['description'], 0, 50, '...') ?: 'ไม่มีคำอธิบาย' ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 text-center">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full border border-slate-200">
                                พ.ศ. <?= $group['academic_year'] ?>
                            </span>
                        </td>
                        <td class="py-5 text-center">
                            <div class="flex items-center justify-center">
                                <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 text-xs font-bold flex items-center justify-center">
                                    <?= $group['member_count'] ?>
                                </span>
                            </div>
                        </td>
                        <td class="py-5">
                            <p class="text-sm font-bold text-slate-600"><?= $group['creator_name'] ?></p>
                            <p class="text-[10px] text-slate-400 uppercase tracking-tighter"><?= date('d M Y', strtotime($group['created_at'])) ?></p>
                        </td>
                        <td class="py-5 text-right pr-4">
                            <?php if (isset($myGroupsIds[$group['id']])): ?>
                                <?php if ($myGroupsIds[$group['id']] === 'approved'): ?>
                                    <a href="<?= url('/plc/group/view/' . $group['id']) ?>" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-primary hover:text-white transition shadow-sm">
                                        เข้าสู่กลุ่ม
                                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </a>
                                <?php else: ?>
                                    <span class="px-4 py-2 bg-amber-50 text-amber-600 text-[10px] font-bold rounded-xl border border-amber-100 uppercase tracking-widest">
                                        รอการอนุมัติ
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <form action="<?= url('/plc/group/request-join') ?>" method="POST" class="inline">
                                    <?= \Core\Security::csrf_field() ?>
                                    <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary/10 text-primary text-xs font-bold rounded-xl hover:bg-primary hover:text-white transition shadow-sm">
                                        ขอเข้าร่วมกลุ่ม
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Group Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="showModal = false" class="bg-white rounded-3xl w-full max-w-lg shadow-2xl overflow-hidden" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-800">สร้างกลุ่ม PLC ใหม่</h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="<?= url('/plc/groups/store') ?>" method="POST" class="p-8 space-y-6">
                <?= \Core\Security::csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">ชื่อกลุ่ม PLC</label>
                    <input type="text" name="name" required class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all" placeholder="เช่น กลุ่ม PLC คณิตศาสตร์ ม.ต้น">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">ปีการศึกษา (พ.ศ.)</label>
                        <select name="academic_year" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                            <?php 
                            $currentYearBE = date('Y') + 543;
                            for($y = $currentYearBE; $y >= $currentYearBE-2; $y--): 
                            ?>
                                <option value="<?= $y ?>"><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">เป้าหมาย (ชั่วโมง)</label>
                        <input type="number" name="target_goal" value="50" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">รายละเอียดกลุ่ม / หัวข้อเป้าหมาย</label>
                    <textarea name="description" rows="3" class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all resize-none" placeholder="รายละเอียดหรือวัตถุประสงค์ของกลุ่ม..."></textarea>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showModal = false" class="flex-1 px-6 py-3 border border-slate-200 text-slate-500 font-bold rounded-2xl hover:bg-slate-50 transition">ยกเลิก</button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:shadow-lg transition">สร้างกลุ่ม</button>
                </div>
            </form>
        </div>
    </div>
</div>
