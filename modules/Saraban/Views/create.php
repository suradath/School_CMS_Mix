<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="/saraban/<?= $type['slug'] ?>" class="text-slate-400 hover:text-primary transition-colors flex items-center text-sm font-bold mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
            ย้อนกลับ
        </a>
        <h3 class="text-2xl font-bold text-slate-800 heading-font"><?= $title ?></h3>
    </div>

    <form action="/saraban/store" method="POST" enctype="multipart/form-data" class="space-y-8">
        <?= \Core\Security::csrf_field() ?>
        <input type="hidden" name="type_id" value="<?= $type['id'] ?>">
        <input type="hidden" name="budget_year" value="<?= $budget_year ?>">

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">เลขทะเบียนรับ/ส่ง (อัตโนมัติ)</label>
                    <input type="text" name="doc_no" value="<?= $doc_no ?>" readonly class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 font-bold text-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">เลขที่หนังสือ (ตัวจริง)</label>
                    <input type="text" name="book_no" value="<?= $suggested_book_no ?>" placeholder="<?= $type['prefix'] ?>..." class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">เรื่อง</label>
                <input type="text" name="title" required class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">จากหน่วยงาน</label>
                    <input type="text" name="origin" class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">ความเร่งด่วน</label>
                    <select name="priority" class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        <option value="normal">ปกติ</option>
                        <option value="urgent">ด่วน</option>
                        <option value="very_urgent">ด่วนที่สุด</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">ลงวันที่ในหนังสือ</label>
                    <input type="date" name="doc_date" value="<?= date('Y-m-d') ?>" class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                </div>
                <?php if ($type['slug'] === 'inbound'): ?>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">วันที่รับหนังสือ</label>
                    <input type="date" name="received_date" value="<?= date('Y-m-d') ?>" class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                </div>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">แนบไฟล์เอกสาร (PDF/Image)</label>
                <input type="file" name="file" accept="application/pdf,image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-slate-800 cursor-pointer">
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
            <h4 class="text-lg font-bold text-slate-800 heading-font mb-6">การเวียนหนังสือ (Distribution)</h4>
            
            <div class="space-y-8">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">ส่งให้กลุ่มสาระฯ / ฝ่ายงาน</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php foreach ($departments as $dept): ?>
                        <label class="flex items-center p-4 rounded-2xl border border-slate-50 hover:bg-slate-50 transition-colors cursor-pointer group">
                            <input type="checkbox" name="dept_receivers[]" value="<?= $dept['id'] ?>" class="w-5 h-5 rounded-lg border-slate-300 text-primary focus:ring-primary transition-all">
                            <span class="ml-3 text-sm text-slate-600 group-hover:text-slate-900"><?= $dept['name'] ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">ส่งให้รายบุคคล</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-64 overflow-y-auto p-4 bg-slate-50/50 rounded-3xl">
                        <?php foreach ($personnel as $p): ?>
                        <label class="flex items-center p-3 rounded-xl hover:bg-white hover:shadow-sm transition-all cursor-pointer group">
                            <input type="checkbox" name="person_receivers[]" value="<?= $p['id'] ?>" class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary transition-all">
                            <div class="ml-3">
                                <p class="text-sm font-bold text-slate-700 group-hover:text-primary transition-colors"><?= $p['name'] ?></p>
                                <p class="text-[10px] text-slate-400 uppercase"><?= $p['position'] ?></p>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
            <h4 class="text-lg font-bold text-slate-800 heading-font mb-4">บันทึกข้อความเกษียณเบื้องต้น (Initial Minute)</h4>
            <p class="text-xs text-slate-400 mb-6 uppercase tracking-widest font-bold">* สำหรับเจ้าหน้าที่ธุรการลงความเห็นเพื่อเสนอผู้บริหาร</p>
            
            <div class="mb-4 flex flex-wrap gap-2">
                <?php 
                    $templates = ['เรียนเสนอเพื่อโปรดพิจารณา', 'แจ้งผู้เกี่ยวข้องถือปฏิบัติ', 'เห็นควรดำเนินการตามเสนอ'];
                ?>
                <?php foreach ($templates as $tpl): ?>
                <button type="button" onclick="document.getElementById('initial-note').value = '<?= $tpl ?>'" class="px-3 py-1 bg-slate-50 text-slate-500 rounded-lg text-[10px] font-bold hover:bg-primary hover:text-white transition-all">
                    + <?= $tpl ?>
                </button>
                <?php endforeach; ?>
            </div>
            <textarea id="initial-note" name="initial_note" rows="3" class="w-full px-6 py-4 rounded-2xl border border-slate-100 bg-slate-50 focus:bg-white focus:border-primary transition-all text-sm" placeholder="พิมพ์ข้อความเกษียณเบื้องต้น..."></textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-12 py-5 bg-primary text-white font-bold rounded-3xl shadow-xl shadow-primary/20 hover:scale-105 transition-all text-lg">
                บันทึกและส่งเวียนหนังสือ
            </button>
        </div>
    </form>
</div>
