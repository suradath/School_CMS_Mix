<div class="max-w-7xl mx-auto space-y-8" x-data="{ activeTab: 'meetings', showMeetingModal: false, showMemberModal: false }">
    <!-- Header Card -->
    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <a href="<?= url('/plc/groups') ?>" class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-primary transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black rounded-full border border-primary/20 uppercase tracking-wider">
                            ปีการศึกษา พ.ศ. <?= $group['academic_year'] ?>
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-800 heading-font"><?= $group['name'] ?></h1>
                    <p class="text-slate-500 mt-2"><?= $group['description'] ?></p>
                </div>
                <div class="flex items-center gap-3">
                    <?php 
                    $isApprovedMember = ($myMembership && $myMembership['status'] === 'approved');
                    $isAdmin = \Core\Security::hasRole('admin');
                    $canSeeContent = $isApprovedMember || $isAdmin;
                    ?>
                    <?php if ($isApprovedMember): ?>
                    <button @click="showMeetingModal = true" class="px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:shadow-lg transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        บันทึกกิจกรรม PLC
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex space-x-1 bg-slate-100 p-1.5 rounded-2xl w-fit">
        <button @click="activeTab = 'meetings'" :class="activeTab === 'meetings' ? 'bg-white shadow-sm text-primary' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200">กิจกรรมและการประชุม</button>
        <button @click="activeTab = 'members'" :class="activeTab === 'members' ? 'bg-white shadow-sm text-primary' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200">สมาชิกในกลุ่ม (<?= count($members) ?>)</button>
        <button @click="activeTab = 'materials'" :class="activeTab === 'materials' ? 'bg-white shadow-sm text-primary' : 'text-slate-500 hover:text-slate-700'" class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-200">คลังสื่อการสอน (<?= count($materials) ?>)</button>
    </div>

    <!-- Tab: Meetings -->
    <div x-show="activeTab === 'meetings'" class="space-y-6">
        <?php if (!$canSeeContent): ?>
            <div class="bg-white rounded-3xl p-16 text-center border border-slate-100 shadow-sm">
                <div class="w-20 h-20 bg-amber-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-amber-500">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-700">เนื้อหานี้เฉพาะสมาชิกกลุ่มเท่านั้น</h3>
                <p class="text-slate-400 mt-2">กรุณาส่งคำขอเข้าร่วมกลุ่มเพื่อดูบันทึกกิจกรรมและแลกเปลี่ยนสื่อการสอน</p>
                <?php if (!$myMembership): ?>
                <form action="<?= url('/plc/group/request-join') ?>" method="POST" class="mt-8">
                    <?= \Core\Security::csrf_field() ?>
                    <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                    <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-2xl hover:shadow-lg transition">ส่งคำขอเข้าร่วมกลุ่ม</button>
                </form>
                <?php endif; ?>
            </div>
        <?php elseif (empty($meetings)): ?>
            <div class="bg-white rounded-3xl p-16 text-center border border-slate-100 shadow-sm">
                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6 text-slate-200">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-700">ยังไม่มีการบันทึกกิจกรรม</h3>
                <p class="text-slate-400 mt-2">สมาชิกในกลุ่มสามารถเริ่มต้นบันทึกการประชุมหรือกิจกรรม PLC ได้ที่นี่</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-6">
                <?php foreach ($meetings as $meeting): ?>
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden group hover:border-primary/30 transition-all duration-300">
                    <div class="p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 border border-slate-100">
                                    <span class="text-xl font-black"><?= (int)$meeting['hours'] ?></span>
                                    <span class="text-[10px] font-bold uppercase ml-0.5 mt-1">ชม.</span>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-slate-800"><?= $meeting['topic'] ?></h4>
                                    <p class="text-sm text-slate-400 flex items-center mt-1">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <?= date('d M Y', strtotime($meeting['date'])) ?>
                                        <span class="mx-2">•</span>
                                        บันทึกโดย <?= $meeting['creator_name'] ?>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <?php if ($meeting['status'] === 'approved'): ?>
                                    <span class="px-4 py-2 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-xl border border-emerald-100 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        อนุมัติแล้ว
                                    </span>
                                <?php elseif ($meeting['status'] === 'rejected'): ?>
                                    <span class="px-4 py-2 bg-rose-50 text-rose-600 text-xs font-bold rounded-xl border border-rose-100">ไม่อนุมัติ</span>
                                <?php else: ?>
                                    <span class="px-4 py-2 bg-amber-50 text-amber-600 text-xs font-bold rounded-xl border border-amber-100">รอการตรวจสอบ</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="space-y-2">
                                <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ประเด็นปัญหา</h5>
                                <p class="text-slate-600 text-sm leading-relaxed"><?= nl2br($meeting['problem_topic']) ?></p>
                            </div>
                            <div class="space-y-2">
                                <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">วิธีแก้ปัญหา</h5>
                                <p class="text-slate-600 text-sm leading-relaxed"><?= nl2br($meeting['solution']) ?></p>
                            </div>
                            <div class="space-y-2">
                                <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ผลลัพธ์ที่ได้</h5>
                                <p class="text-slate-600 text-sm leading-relaxed"><?= nl2br($meeting['result']) ?></p>
                            </div>
                        </div>

                        <?php if ($meeting['status'] === 'pending' && ($myMembership['role'] === 'head' || \Core\Security::hasRole('admin'))): ?>
                        <div class="mt-8 pt-8 border-t border-slate-50 flex items-center justify-end gap-3">
                            <form action="<?= url('/plc/meeting/approve') ?>" method="POST" class="inline">
                                <?= \Core\Security::csrf_field() ?>
                                <input type="hidden" name="meeting_id" value="<?= $meeting['id'] ?>">
                                <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                                <button name="status" value="approved" class="px-6 py-2.5 bg-emerald-500 text-white text-xs font-bold rounded-xl hover:shadow-lg hover:shadow-emerald-500/30 transition">อนุมัติชั่วโมง</button>
                                <button name="status" value="rejected" class="px-6 py-2.5 bg-rose-100 text-rose-600 text-xs font-bold rounded-xl hover:bg-rose-200 transition ml-2">ไม่ผ่าน</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tab: Members -->
    <div x-show="activeTab === 'members'" class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
            <h3 class="text-xl font-bold text-slate-800">สมาชิกในกลุ่ม</h3>
            <?php if ($myMembership && $myMembership['role'] === 'head'): ?>
            <button @click="showMemberModal = true" class="px-4 py-2 bg-primary/10 text-primary text-xs font-bold rounded-xl hover:bg-primary hover:text-white transition-all">
                + เพิ่มสมาชิก
            </button>
            <?php endif; ?>
        </div>
        <div class="p-8">
            <?php 
            $pendingMembers = array_filter($members, fn($m) => $m['status'] === 'pending');
            if (!empty($pendingMembers) && ($myMembership['role'] === 'head' || \Core\Security::hasRole('admin'))): 
            ?>
                <div class="mb-10 p-6 bg-amber-50 rounded-3xl border border-amber-100">
                    <h4 class="text-sm font-bold text-amber-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        คำขอเข้าร่วมกลุ่มใหม่ (<?= count($pendingMembers) ?>)
                    </h4>
                    <div class="space-y-3">
                        <?php foreach ($pendingMembers as $pm): ?>
                        <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-amber-100 shadow-sm">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mr-3 text-xs font-bold">
                                    <?= mb_substr($pm['full_name'], 0, 1) ?>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700"><?= $pm['full_name'] ?></p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase"><?= $pm['department_name'] ?: 'ไม่ระบุสังกัด' ?></p>
                                </div>
                            </div>
                            <form action="<?= url('/plc/group/approve-member') ?>" method="POST" class="flex gap-2">
                                <?= \Core\Security::csrf_field() ?>
                                <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                                <input type="hidden" name="user_id" value="<?= $pm['user_id'] ?>">
                                <button name="status" value="approved" class="px-4 py-2 bg-emerald-500 text-white text-xs font-bold rounded-xl hover:shadow-lg transition">อนุมัติ</button>
                                <button name="status" value="rejected" class="px-4 py-2 bg-slate-100 text-slate-500 text-xs font-bold rounded-xl hover:bg-rose-100 hover:text-rose-600 transition">ปฏิเสธ</button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-xs font-bold uppercase tracking-widest border-b border-slate-50">
                        <th class="pb-4 pl-4">ชื่อ-นามสกุล</th>
                        <th class="pb-4">ตำแหน่ง/สังกัด</th>
                        <th class="pb-4 text-center">บทบาท</th>
                        <th class="pb-4 text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php 
                    $activeMembers = array_filter($members, fn($m) => $m['status'] === 'approved');
                    foreach ($activeMembers as $member): 
                    ?>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4 pl-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mr-3 text-xs font-bold">
                                    <?= mb_substr($member['full_name'], 0, 1) ?>
                                </div>
                                <p class="text-sm font-bold text-slate-700"><?= $member['full_name'] ?></p>
                            </div>
                        </td>
                        <td class="py-4">
                            <p class="text-xs text-slate-600 font-bold"><?= $member['position_name'] ?: 'คุณครู' ?></p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase"><?= $member['department_name'] ?: 'ไม่ระบุสังกัด' ?></p>
                        </td>
                        <td class="py-4 text-center">
                            <span class="px-3 py-1 <?= $member['role'] === 'head' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-slate-50 text-slate-500 border-slate-100' ?> text-[10px] font-black rounded-full border uppercase tracking-wider">
                                <?= $member['role'] === 'head' ? 'หัวหน้ากลุ่ม' : 'สมาชิก' ?>
                            </span>
                        </td>
                        <td class="py-4 text-center">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-full border border-emerald-100 uppercase tracking-widest">
                                เข้าร่วมแล้ว
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab: Materials -->
    <div x-show="activeTab === 'materials'" class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50">
            <h3 class="text-xl font-bold text-slate-800">คลังสื่อการสอน (Material Library)</h3>
            <p class="text-sm text-slate-400 mt-1">แหล่งแบ่งปันทรัพยากรและสื่อที่ได้จากการทำ PLC ร่วมกันในกลุ่ม</p>
        </div>
        <div class="p-8">
            <?php if (!$canSeeContent): ?>
                <div class="text-center py-16 opacity-40">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <p class="font-bold">กรุณาเข้าร่วมกลุ่มเพื่อใช้งานคลังสื่อการสอน</p>
                </div>
            <?php elseif (empty($materials)): ?>
                <div class="text-center py-16 opacity-40">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <p class="font-bold">ยังไม่มีสื่อการสอนแบ่งปันในกลุ่มนี้</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($materials as $file): ?>
                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 flex items-start gap-4 hover:border-primary/30 hover:shadow-lg hover:shadow-primary/5 transition-all group">
                        <div class="w-12 h-12 bg-white rounded-xl border border-slate-100 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-700 truncate"><?= $file['file_name'] ?></p>
                            <p class="text-[10px] text-slate-400 mt-0.5 truncate uppercase">จากหัวข้อ: <?= $file['meeting_topic'] ?></p>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-[10px] text-primary font-bold bg-white px-2 py-0.5 rounded border border-primary/10 uppercase"><?= $file['file_type'] ?></span>
                                <a href="<?= url($file['file_path']) ?>" download class="text-primary hover:underline text-xs font-bold">ดาวน์โหลด</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Meeting Modal -->
    <div x-show="showMeetingModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="showMeetingModal = false" class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl overflow-hidden" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-800">บันทึกกิจกรรม PLC</h3>
                <button @click="showMeetingModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="<?= url('/plc/meeting/store') ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-6 max-h-[70vh] overflow-y-auto">
                <?= \Core\Security::csrf_field() ?>
                <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">หัวข้อการประชุม/กิจกรรม</label>
                        <input type="text" name="topic" required class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all" placeholder="สรุปหัวข้อกิจกรรมสั้นๆ">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">วันที่ทำกิจกรรม</label>
                        <input type="date" name="date" value="<?= date('Y-m-d') ?>" required class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">จำนวนชั่วโมง</label>
                        <input type="number" name="hours" step="0.5" min="0.5" required class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all" placeholder="เช่น 2">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">1. ประเด็นปัญหา / สิ่งที่ต้องการพัฒนา</label>
                        <textarea name="problem_topic" rows="3" required class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">2. วิธีการดำเนินงาน / วิธีการแก้ปัญหา</label>
                        <textarea name="solution" rows="3" required class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">3. ผลลัพธ์ที่ได้จากการทำกิจกรรม</label>
                        <textarea name="result" rows="3" required class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all resize-none"></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">แนบหลักฐาน / แลกเปลี่ยนสื่อการสอน</label>
                    <input type="file" name="materials[]" multiple class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all text-sm">
                    <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tight">* รูปภาพกิจกรรม, ไฟล์ PDF, Word, PPT สำหรับแลกเปลี่ยนในกลุ่ม</p>
                </div>

                <div class="pt-4 flex gap-3 sticky bottom-0 bg-white">
                    <button type="button" @click="showMeetingModal = false" class="flex-1 px-6 py-4 border border-slate-200 text-slate-500 font-bold rounded-2xl hover:bg-slate-50 transition">ยกเลิก</button>
                    <button type="submit" class="flex-1 px-6 py-4 bg-primary text-white font-bold rounded-2xl hover:shadow-lg transition">บันทึกและส่งตรวจสอบ</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Member Modal -->
    <div x-show="showMemberModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" x-cloak>
        <div @click.away="showMemberModal = false" class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-800">เพิ่มสมาชิกเข้ากลุ่ม</h3>
                <button @click="showMemberModal = false" class="text-slate-400 hover:text-slate-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <form action="<?= url('/plc/group/add-member') ?>" method="POST" class="p-8 space-y-6">
                <?= \Core\Security::csrf_field() ?>
                <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">เลือกคุณครู/บุคลากร</label>
                    <select name="user_id" required class="w-full px-5 py-3 rounded-2xl bg-slate-50 border border-slate-100 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all select2">
                        <option value="">-- ค้นหารายชื่อ --</option>
                        <?php foreach ($allUsers as $u): ?>
                            <option value="<?= $u['id'] ?>"><?= $u['full_name'] ?> (<?= $u['username'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">บทบาทในกลุ่ม</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center p-4 rounded-2xl border border-slate-100 bg-slate-50 cursor-pointer group hover:bg-white hover:border-primary/30 transition-all">
                            <input type="radio" name="role" value="member" checked class="w-4 h-4 text-primary focus:ring-primary border-gray-300">
                            <span class="ml-3 text-sm font-bold text-slate-700">สมาชิกกลุ่ม</span>
                        </label>
                        <label class="relative flex items-center p-4 rounded-2xl border border-slate-100 bg-slate-50 cursor-pointer group hover:bg-white hover:border-primary/30 transition-all">
                            <input type="radio" name="role" value="head" class="w-4 h-4 text-primary focus:ring-primary border-gray-300">
                            <span class="ml-3 text-sm font-bold text-slate-700">หัวหน้ากลุ่ม</span>
                        </label>
                    </div>
                </div>
                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showMemberModal = false" class="flex-1 px-6 py-3 border border-slate-200 text-slate-500 font-bold rounded-2xl hover:bg-slate-50 transition">ยกเลิก</button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-primary text-white font-bold rounded-2xl hover:shadow-lg transition">เพิ่มเข้ากลุ่ม</button>
                </div>
            </form>
        </div>
    </div>
</div>
