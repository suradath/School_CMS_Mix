<?php $personnelId = (int)($_SESSION['personnel_id'] ?? 0); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <a href="javascript:history.back()" class="text-slate-400 hover:text-primary transition-colors flex items-center text-sm font-bold mb-4">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
                ย้อนกลับ
            </a>
            <div class="flex items-center gap-4">
                <h3 class="text-3xl font-black text-slate-800 heading-font truncate max-w-xl"><?= $doc['title'] ?></h3>
                <?php 
                    $statusConfig = [
                        'pending' => ['label' => 'รอเกษียณ', 'class' => 'bg-rose-50 text-rose-600 border-rose-100'],
                        'minuted' => ['label' => 'เกษียณแล้ว', 'class' => 'bg-amber-50 text-amber-600 border-amber-100'],
                        'processed' => ['label' => 'สั่งการแล้ว', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                    ];
                    $currentStatus = $doc['saraban_status'] ?? 'pending';
                    $config = $statusConfig[$currentStatus];
                ?>
                <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase border <?= $config['class'] ?>">
                    <?= $config['label'] ?>
                </span>
                <?php if ($currentStatus !== 'pending'): ?>
                <span class="flex items-center gap-1.5 text-[10px] font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-100">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2-2v6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    ล็อกการแก้ไขข้อมูล
                </span>
                <?php endif; ?>
            </div>
            <p class="text-sm text-slate-500 mt-1">เลขที่ทะเบียน: <?= $doc['doc_no'] ?> | ประเภท: <?= $doc['type_name'] ?></p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="<?= url('/saraban/minute/print/' . $doc['id']) ?>" target="_blank" class="px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl font-bold text-xs hover:bg-slate-50 transition-all flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                พิมพ์ใบปะหน้าเกษียณ
            </a>
            
            <?php if ($myAcknowledge && !$myAcknowledge['acknowledged_at']): ?>
            <a href="<?= url('/saraban/acknowledge/' . $doc['id']) ?>" class="px-6 py-3 bg-emerald-600 text-white rounded-2xl font-bold text-xs hover:shadow-lg hover:shadow-emerald-200 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                กดรับทราบเอกสาร
            </a>
            <?php endif; ?>
            
            <?php if ($doc['file_url']): ?>
            <a href="<?= url('/saraban/file/' . $doc['id']) ?>" target="_blank" class="px-6 py-3 bg-slate-900 text-white rounded-2xl font-bold text-xs hover:shadow-lg transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                ดูไฟล์ตัวจริง
            </a>
            <?php endif; ?>

            <?php if (\Core\Security::checkRole(['admin', 'officer'])): ?>
            <button onclick="confirmDelete(<?= $doc['id'] ?>)" class="px-6 py-3 bg-rose-50 text-rose-600 border border-rose-100 rounded-2xl font-bold text-xs hover:bg-rose-100 transition-all flex items-center shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                ลบเอกสาร
            </button>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-sm font-bold animate-fade-in">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        <!-- PDF Column -->
        <div class="xl:col-span-7 sticky top-8">
            <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden aspect-[1/1.414] relative group">
                <?php if ($doc['file_url']): ?>
                    <?php 
                        $filePath = ROOT_PATH . $doc['file_url'];
                        $isImage = false;
                        if (file_exists($filePath)) {
                            $mime = mime_content_type($filePath);
                            $isImage = strpos($mime, 'image') === 0;
                        }
                    ?>
                    <?php if ($isImage): ?>
                        <img src="<?= url('/saraban/file/' . $doc['id']) ?>" class="w-full h-full object-contain p-8" alt="Document image">
                    <?php else: ?>
                        <iframe src="<?= url('/saraban/file/' . $doc['id']) ?>#toolbar=0" class="w-full h-full border-0"></iframe>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-300">
                        <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="font-bold uppercase tracking-widest text-xs">ไม่พบไฟล์เอกสารแนบ</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Timeline & Form Column -->
        <div class="xl:col-span-5 space-y-8">
            <!-- Minute Form (If allowed) -->
            <?php 
            $alreadyMinuted = false;
            foreach ($minutes as $m) {
                if ($m['user_id'] == $_SESSION['user_id']) {
                    $alreadyMinuted = true;
                    break;
                }
            }
            $isReceiver = false;
            foreach ($receivers as $r) {
                if ($r['personnel_id'] == $personnelId || ($r['department_id'] > 0 && $r['department_id'] == ($userDeptId ?? 0))) {
                    $isReceiver = true;
                    break;
                }
            }
            if ((hasRole(['admin', 'officer']) || $isReceiver) && !$alreadyMinuted): 
            ?>
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-widest border-b border-slate-50 pb-4 mb-6">บันทึกข้อความเกษียณหนังสือ</h4>
                <form action="<?= url('/saraban/minute/add') ?>" method="POST" class="space-y-6">
                    <?= \Core\Security::csrf_field() ?>
                    <input type="hidden" name="document_id" value="<?= $doc['id'] ?>">
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">ข้อความสั่งการ / ความเห็น</label>
                        <div class="mb-3 flex flex-wrap gap-2">
                            <?php 
                                $templates = [
                                    'officer' => ['เรียนเสนอเพื่อโปรดพิจารณา', 'แจ้งผู้เกี่ยวข้องถือปฏิบัติ', 'เห็นควรดำเนินการตามเสนอ'],
                                    'director' => ['ทราบ/ถือปฏิบัติ', 'อนุมัติ', 'มอบงานให้กลุ่มสาระฯ', 'แจ้งผู้เกี่ยวข้องดำเนินการ']
                                ];
                                
                                // Priority role for templates
                                if (hasRole('director')) {
                                    $relevantTemplates = $templates['director'];
                                } elseif (hasRole(['admin', 'editor', 'officer'])) {
                                    $relevantTemplates = $templates['officer'];
                                } else {
                                    $relevantTemplates = [];
                                }
                            ?>
                            <?php foreach ($relevantTemplates as $tpl): ?>
                            <button type="button" onclick="document.getElementById('minute-note').value = '<?= $tpl ?>'" class="px-3 py-1 bg-slate-50 text-slate-500 rounded-lg text-[10px] font-bold hover:bg-primary hover:text-white transition-all">
                                + <?= $tpl ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                        <textarea id="minute-note" name="note" required rows="4" class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm" placeholder="พิมพ์ข้อความที่นี่..."></textarea>
                    </div>

                    <?php if ((hasRole('director') || hasRole('admin')) && $userDeptId === 1): ?>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">การวินิจฉัย (Director Only)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5 group">
                                <input type="radio" name="decision" value="acknowledged" checked class="hidden">
                                <span class="text-xs font-bold text-slate-600 group-has-[:checked]:text-primary">ทราบ</span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 group">
                                <input type="radio" name="decision" value="approved" class="hidden">
                                <span class="text-xs font-bold text-slate-600 group-has-[:checked]:text-emerald-600">อนุมัติ / ดำเนินการ</span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 group">
                                <input type="radio" name="decision" value="forwarded" class="hidden">
                                <span class="text-xs font-bold text-slate-600 group-has-[:checked]:text-amber-600">มอบหมายงาน</span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 group">
                                <input type="radio" name="decision" value="rejected" class="hidden">
                                <span class="text-xs font-bold text-slate-600 group-has-[:checked]:text-rose-600">ทักท้วง / ไม่อนุมัติ</span>
                            </label>
                        </div>
                    </div>
                    <?php endif; ?>

                    <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:shadow-xl transition-all">
                        บันทึกข้อความเกษียณ
                    </button>
                    <p class="text-[10px] text-slate-400 text-center uppercase font-bold tracking-tight">* เมื่อบันทึกแล้วจะไม่สามารถแก้ไขได้ตามระเบียบงานสารบรรณ</p>
                </form>
            </div>
            <?php endif; ?>

            <!-- Minute Timeline -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-widest border-b border-slate-50 pb-4 mb-8">ประวัติการเกษียณหนังสือ (Timeline)</h4>
                
                <?php if (empty($minutes)): ?>
                    <div class="py-12 flex flex-col items-center justify-center text-slate-300">
                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                        <p class="text-xs font-bold uppercase tracking-widest">ยังไม่มีบันทึกข้อความ</p>
                    </div>
                <?php else: ?>
                    <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px before:h-full before:w-0.5 before:bg-slate-100">
                        <?php foreach ($minutes as $m): ?>
                        <div class="relative flex items-start group">
                            <!-- Dot -->
                            <div class="absolute left-5 -translate-x-1/2 w-3 h-3 rounded-full border-2 border-white group-last:bg-emerald-500 <?= $m['decision'] !== 'none' ? 'bg-primary' : 'bg-slate-300' ?>"></div>
                            
                            <div class="ml-10 flex-grow">
                                <div class="bg-slate-50/50 rounded-2xl p-6 border border-slate-50 relative">
                                    <!-- Rubber Stamp for Decision -->
                                    <?php if ($m['decision'] !== 'none'): ?>
                                    <div class="absolute -top-3 -right-3 rotate-12">
                                        <?php 
                                            $decConfig = [
                                                'approved' => ['label' => 'อนุมัติ', 'class' => 'border-emerald-500 text-emerald-600'],
                                                'acknowledged' => ['label' => 'ทราบ', 'class' => 'border-primary text-primary'],
                                                'forwarded' => ['label' => 'มอบหมาย', 'class' => 'border-amber-500 text-amber-600'],
                                                'rejected' => ['label' => 'ทักท้วง', 'class' => 'border-rose-500 text-rose-600'],
                                            ];
                                            $d = $decConfig[$m['decision']];
                                        ?>
                                        <div class="px-3 py-1 border-4 <?= $d['class'] ?> font-black text-sm uppercase rounded-lg bg-white shadow-sm">
                                            <?= $d['label'] ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <p class="text-sm text-slate-700 leading-relaxed mb-4"><?= nl2br(htmlspecialchars($m['note'])) ?></p>
                                    
                                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-[10px] font-bold mr-3 uppercase">
                                                <?= substr($m['full_name'], 0, 2) ?>
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-bold text-slate-900"><?= $m['full_name'] ?></p>
                                                <p class="text-[9px] text-slate-400 uppercase tracking-tighter">
                                                    <?= $m['position'] ?> • <?= date('d/m/Y H:i', strtotime($m['created_at'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                        <?php if ($m['user_id'] == $_SESSION['user_id'] || hasRole(['admin', 'officer'])): ?>
                                        <form action="<?= url('/saraban/minute/delete') ?>" method="POST" onsubmit="return confirm('ยืนยันการยกเลิกข้อความเกษียณนี้?')">
                                            <?= \Core\Security::csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                            <button type="submit" class="text-[9px] font-bold text-rose-500 hover:text-rose-700 uppercase tracking-widest bg-rose-50 px-2 py-1 rounded-md transition-all">
                                                ยกเลิก
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Basic Info -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 space-y-6">
                <h4 class="text-sm font-bold text-slate-800 uppercase tracking-widest border-b border-slate-50 pb-4">ข้อมูลเอกสาร</h4>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">เลขที่หนังสือ</p>
                        <p class="text-sm font-bold text-slate-900"><?= $doc['book_no'] ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">จากหน่วยงาน</p>
                        <p class="text-sm text-slate-700"><?= $doc['origin'] ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ลงวันที่</p>
                        <p class="text-sm text-slate-700"><?= date('d/m/Y', strtotime($doc['doc_date'])) ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">ความเร่งด่วน</p>
                        <?php 
                            $priorityClass = [
                                'normal' => 'text-slate-600 bg-slate-50',
                                'urgent' => 'text-amber-600 bg-amber-50',
                                'very_urgent' => 'text-rose-600 bg-rose-50'
                            ];
                            $priorityLabel = [
                                'normal' => 'ปกติ',
                                'urgent' => 'ด่วน',
                                'very_urgent' => 'ด่วนที่สุด'
                            ];
                        ?>
                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold <?= $priorityClass[$doc['priority']] ?>">
                            <?= $priorityLabel[$doc['priority']] ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'ยืนยันการลบเอกสาร?',
        text: "เมื่อลบแล้วจะไม่สามารถกู้คืนข้อมูลและไฟล์แนบได้!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ยืนยันการลบ',
        cancelButtonText: 'ยกเลิก',
        customClass: {
            confirmButton: 'px-6 py-3 bg-rose-600 text-white rounded-xl font-bold text-sm mx-2 shadow-lg shadow-rose-200',
            cancelButton: 'px-6 py-3 bg-slate-400 text-white rounded-xl font-bold text-sm mx-2'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= url('/saraban/delete/') ?>' + id;
        }
    });
}
</script>
