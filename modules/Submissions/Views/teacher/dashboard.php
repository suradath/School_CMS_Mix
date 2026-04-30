<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>

            <h3 class="text-xl font-bold text-slate-800 heading-font">จัดการและติดตามสถานะการส่งแผนการสอน สื่อการสอน
                และผลงานวิชาการต่างๆ</h3>
        </div>
    </div>

    <!-- Active Topics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <?php foreach ($topics as $t): ?>
            <?php
            $sub = $submissions[$t['id']] ?? null;
            $statusInfo = \Modules\Submissions\Models\Submission::getStatusInfo($sub['status'] ?? '');
            ?>
            <div
                class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-primary/5 transition-all group relative overflow-hidden">
                <!-- Status Badge Overlay -->
                <?php if ($sub): ?>
                    <div class="absolute top-0 right-0 p-1">
                        <div
                            class="<?= $statusInfo['bg'] ?> px-4 py-2 rounded-bl-2xl font-bold text-xs uppercase tracking-widest">
                            <?= $statusInfo['label'] ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex items-start gap-5">
                    <div
                        class="w-16 h-16 rounded-2xl bg-primary/5 flex items-center justify-center text-primary shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">ภาคเรียนที่
                            <?= $t['semester'] ?>/<?= $t['academic_year'] ?>
                        </div>
                        <h4 class="text-xl font-bold text-slate-800 mb-2 truncate"><?= htmlspecialchars($t['title']) ?></h4>
                        <p class="text-slate-500 text-sm line-clamp-2 mb-4">
                            <?= htmlspecialchars($t['description'] ?? 'ส่งงานตามกำหนดเวลาและประเภทไฟล์ที่ได้รับอนุญาต') ?>
                        </p>

                        <div class="flex flex-wrap gap-4 text-[11px] font-bold uppercase tracking-wider">
                            <div class="flex items-center text-slate-400">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span><?= implode(', ', $t['allowed_files']) ?></span>
                            </div>
                            <div class="flex items-center text-slate-400">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <span>Max <?= $t['max_file_size'] ?>MB</span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-6 border-slate-50">

                <?php if ($sub): ?>
                    <!-- Submission Status Details -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-400 uppercase">สถานะล่าสุด</span>
                            <span class="text-xs font-medium text-slate-500 italic">เมื่อ:
                                <?= date('d/m/Y H:i', strtotime($sub['submitted_at'])) ?></span>
                        </div>

                        <div class="flex items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <svg class="w-5 h-5 text-slate-400 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <span class="text-sm font-medium text-slate-600 truncate"><?= $sub['original_filename'] ?></span>
                        </div>

                        <?php if ($sub['status'] === 'revision'): ?>
                            <!-- Revision Alert Badge -->
                            <div class="bg-red-50 border border-red-100 p-4 rounded-2xl animate-pulse">
                                <div class="flex items-center mb-2">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-xs font-bold text-red-600 uppercase tracking-widest">ต้องปรับปรุง</span>
                                </div>
                                <p class="text-sm text-red-800 font-medium">
                                    <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                    <?= htmlspecialchars($sub['feedback'] ?? 'กรุณาอัปโหลดไฟล์ใหม่ตามคำแนะนำ') ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div class="flex gap-2">
                            <a href="<?= url($sub['file_path']) ?>" target="_blank"
                                class="flex-1 text-center py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all text-sm">
                                เปิดดูไฟล์
                            </a>
                            <button onclick="triggerUpload(<?= $t['id'] ?>)"
                                class="flex-1 py-3 <?= ($sub['status'] === 'approved') ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-primary text-white hover:bg-blue-700 shadow-lg shadow-primary/20 transition-all' ?> font-bold rounded-2xl text-sm"
                                <?= ($sub['status'] === 'approved') ? 'disabled' : '' ?>>
                                <?= ($sub['status'] === 'revision') ? 'ส่งเวอร์ชันใหม่' : 'แก้ไขเอกสาร' ?>
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Empty State / Upload Form -->
                    <div class="mt-4">
                        <button onclick="triggerUpload(<?= $t['id'] ?>)"
                            class="w-full py-4 bg-primary/5 border-2 border-dashed border-primary/20 rounded-2xl flex flex-col items-center justify-center group/btn hover:bg-primary/10 hover:border-primary/40 transition-all">
                            <svg class="w-8 h-8 text-primary/40 group-hover/btn:text-primary group-hover/btn:scale-110 transition-all mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span class="text-sm font-bold text-primary">คลิกเพื่ออัปโหลดเอกสาร</span>
                            <span class="text-[10px] text-slate-400 font-medium mt-1">DRAG & DROP SUPPORTED</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <?php if (empty($topics)): ?>
            <div class="col-span-full py-20 text-center">
                <div
                    class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-500">ไม่มีหัวข้อการส่งเอกสารที่เปิดใช้งานในขณะนี้</h3>
                <p class="text-slate-400">กรุณาตรวจสอบอีกครั้งในภายหลัง</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" onclick="closeUploadModal()">
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div
            class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="<?= url('/submissions/submit') ?>" method="POST" enctype="multipart/form-data"
                id="uploadForm">
                <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
                <input type="hidden" name="topic_id" id="form_topic_id">

                <div class="p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-slate-800 heading-font">อัปโหลดเอกสาร</h3>
                        <button type="button" onclick="closeUploadModal()" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Drag & Drop Area -->
                    <div id="dropZone"
                        class="relative group border-2 border-dashed border-slate-200 rounded-3xl p-10 flex flex-col items-center justify-center transition-all hover:border-primary/50 hover:bg-primary/5">
                        <input type="file" name="file" id="fileInput" required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div
                            class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center text-primary mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                        </div>
                        <p class="text-slate-700 font-bold text-lg mb-1" id="fileNameLabel">ลากไฟล์มาวาง
                            หรือคลิกเพื่อเลือก</p>
                        <p class="text-slate-400 text-sm font-medium" id="fileSizeLabel">รองรับไฟล์ตามเงื่อนไขที่กำหนด
                        </p>
                    </div>

                    <div
                        class="mt-6 flex items-start gap-3 p-4 bg-amber-50 rounded-2xl border border-amber-100 text-amber-700">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs font-medium">การอัปโหลดใหม่จะแทนที่เอกสารเดิมที่คุณเคยส่งไว้
                            (ยกเว้นเอกสารที่ได้รับการอนุมัติแล้วจะไม่สามารถแก้ไขได้)</p>
                    </div>
                </div>

                <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end space-x-3">
                    <button type="button" onclick="closeUploadModal()"
                        class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all">ยกเลิก</button>
                    <button type="submit"
                        class="px-8 py-3 bg-primary text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-primary/20">ส่งเอกสาร</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const fileInput = document.getElementById('fileInput');
    const fileNameLabel = document.getElementById('fileNameLabel');
    const fileSizeLabel = document.getElementById('fileSizeLabel');
    const dropZone = document.getElementById('dropZone');

    fileInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            fileNameLabel.innerText = file.name;
            fileSizeLabel.innerText = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            dropZone.classList.add('border-primary', 'bg-primary/5');
        }
    });

    function triggerUpload(topicId) {
        document.getElementById('form_topic_id').value = topicId;
        document.getElementById('uploadModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('uploadForm').reset();
        fileNameLabel.innerText = 'ลากไฟล์มาวาง หรือคลิกเพื่อเลือก';
        fileSizeLabel.innerText = 'รองรับไฟล์ตามเงื่อนไขที่กำหนด';
        dropZone.classList.remove('border-primary', 'bg-primary/5');
    }

    // Basic Drag & Drop visual feedback
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, e => {
            e.preventDefault();
            dropZone.classList.add('border-primary', 'bg-primary/5');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, e => {
            e.preventDefault();
            if (!fileInput.files.length) {
                dropZone.classList.remove('border-primary', 'bg-primary/5');
            }
        }, false);
    });
</script>