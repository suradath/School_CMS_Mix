<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 heading-font">แดชบอร์ด PLC</h1>
            <p class="text-slate-500 mt-1">สรุปชั่วโมงสะสมเพื่อการพัฒนาวิชาชีพและวิทยฐานะ</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= url('/plc/groups') ?>" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-2xl hover:bg-slate-50 transition shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                จัดการกลุ่ม PLC
            </a>
            <a href="<?= url('/plc/report/export?year=' . $academicYear) ?>" class="px-6 py-3 bg-emerald-500 text-white font-bold rounded-2xl hover:shadow-lg hover:shadow-emerald-500/30 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Excel
            </a>
            <a href="<?= url('/plc/report?year=' . $academicYear) ?>" target="_blank" class="px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:shadow-lg hover:shadow-primary/30 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                พิมพ์รายงาน
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Progress Card -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-xl font-bold text-slate-800 mb-6">ความก้าวหน้าการสะสมชั่วโมง (ปีการศึกษา พ.ศ. <?= $academicYear ?>)</h3>
                
                <?php 
                    $percent = min(100, ($totalHours / $targetGoal) * 100);
                    $statusColor = $percent >= 100 ? 'bg-emerald-500' : ($percent > 50 ? 'bg-primary' : 'bg-amber-500');
                ?>
                
                <div class="flex items-end justify-between mb-4">
                    <div>
                        <span class="text-4xl font-black text-slate-800"><?= $totalHours ?></span>
                        <span class="text-slate-400 font-bold ml-1 uppercase tracking-wider text-sm">/ <?= $targetGoal ?> ชั่วโมง</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">สถานะปัจจุบัน</span>
                        <p class="text-lg font-bold <?= $percent >= 100 ? 'text-emerald-500' : 'text-primary' ?>">
                            <?= $percent >= 100 ? 'ครบตามเป้าหมาย' : 'กำลังดำเนินการ' ?>
                        </p>
                    </div>
                </div>

                <div class="w-full bg-slate-100 h-6 rounded-full overflow-hidden p-1 shadow-inner">
                    <div class="h-full <?= $statusColor ?> rounded-full shadow-lg transition-all duration-1000 ease-out relative" style="width: <?= $percent ?>%">
                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                    </div>
                </div>
                
                <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter mb-1">กลุ่มที่คุณเข้าร่วม</p>
                        <p class="text-2xl font-bold text-slate-700"><?= count($myGroups) ?></p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter mb-1">สถานะ</p>
                        <p class="text-2xl font-bold text-slate-700"><?= count($summary) ?> กลุ่ม</p>
                    </div>
                </div>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        </div>

        <!-- Groups Summary -->
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
            <h3 class="text-xl font-bold text-slate-800 mb-6">สรุปรายกลุ่ม</h3>
            <div class="space-y-4">
                <?php if (empty($summary)): ?>
                    <div class="text-center py-10 opacity-40">
                        <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="font-bold">ยังไม่มีข้อมูลชั่วโมงสะสม</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($summary as $item): ?>
                    <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100 group hover:border-primary/30 transition-all">
                        <div class="flex-1 min-w-0 mr-4">
                            <h4 class="font-bold text-slate-700 truncate"><?= $item['group_name'] ?></h4>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">
                                <?= $item['role'] === 'head' ? 'หัวหน้ากลุ่ม' : 'สมาชิก' ?>
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-black text-primary"><?= $item['approved_hours'] ?></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase ml-0.5">ชม.</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- My Groups Section -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <h3 class="text-xl font-bold text-slate-800">กลุ่ม PLC ของคุณ</h3>
        </div>
        <div class="p-8">
            <?php if (empty($myGroups)): ?>
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-600">คุณยังไม่ได้เข้าร่วมกลุ่ม PLC ใดๆ</h4>
                    <p class="text-slate-400 mt-2 max-w-sm mx-auto">เริ่มต้นโดยการค้นหากลุ่มที่มีอยู่หรือสร้างกลุ่มใหม่เพื่อเริ่มสะสมชั่วโมง</p>
                    <a href="<?= url('/plc/groups') ?>" class="inline-block mt-8 px-8 py-3 bg-primary text-white font-bold rounded-2xl hover:shadow-lg transition">ไปหน้าจัดการกลุ่ม</a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($myGroups as $group): ?>
                    <a href="<?= url('/plc/group/view/' . $group['id']) ?>" class="group bg-slate-50 border border-slate-100 rounded-3xl p-6 hover:bg-white hover:border-primary hover:shadow-xl hover:shadow-primary/10 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 bg-white text-primary text-[10px] font-black rounded-full border border-primary/20 uppercase tracking-wider">
                                ปีการศึกษา <?= $group['academic_year'] ?>
                            </span>
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors border border-slate-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </div>
                        </div>
                        <h4 class="text-lg font-bold text-slate-800 group-hover:text-primary transition-colors"><?= $group['name'] ?></h4>
                        <p class="text-sm text-slate-500 mt-2 line-clamp-2"><?= $group['description'] ?: 'ไม่มีคำอธิบายกลุ่ม' ?></p>
                        
                        <div class="mt-6 pt-6 border-t border-slate-200/50 flex items-center justify-between">
                            <div class="flex items-center text-slate-400">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="text-xs font-bold"><?= $group['role'] === 'head' ? 'หัวหน้ากลุ่ม' : 'สมาชิก' ?></span>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
