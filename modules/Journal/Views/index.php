<div class="mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-xl font-bold text-slate-800 heading-font">จัดการวารสาร</h3>
        <p class="text-sm text-slate-500 mt-1">อัปโหลดและจัดการรูปภาพวารสารประชาสัมพันธ์</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary text-white px-4 py-2 rounded-xl font-bold text-xs hover:shadow-lg hover:shadow-primary/20 transition-all flex items-center">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
        เพิ่มวารสารใหม่
    </button>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl text-sm font-bold">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl text-sm font-bold">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <?php foreach ($items as $item): ?>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-xl transition-all">
        <div class="relative aspect-[3/4] overflow-hidden bg-slate-100">
            <img src="<?= url($item['image_url']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="<?= $item['title'] ?>">
            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-2">
                <a href="<?= $item['image_url'] ?>" target="_blank" class="p-2 bg-white/20 backdrop-blur-md rounded-xl text-white hover:bg-white/40 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </a>
                <button onclick="confirmDelete(<?= $item['id'] ?>)" class="p-2 bg-rose-500/20 backdrop-blur-md rounded-xl text-rose-100 hover:bg-rose-500/40 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        </div>
        <div class="p-4">
            <h4 class="text-sm font-bold text-slate-800 truncate"><?= $item['title'] ?></h4>
            <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-bold">ลำดับ: <?= $item['sort_order'] ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center">
            <h4 class="text-lg font-bold text-slate-800">เพิ่มวารสารใหม่</h4>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="<?= url('/journal/store') ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            <?= \Core\Security::csrf_field() ?>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">หัวข้อ/ชื่อวารสาร</label>
                <input type="text" name="title" required class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ลำดับการแสดงผล</label>
                <input type="number" name="sort_order" value="0" class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">รูปภาพวารสาร (JPG, PNG)</label>
                <input type="file" name="image" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary/90 cursor-pointer">
            </div>
            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-primary text-white font-bold rounded-2xl hover:shadow-lg hover:shadow-primary/20 transition-all">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('คุณต้องการลบวารสารนี้ใช่หรือไม่?')) {
        window.location.href = '<?= url('/journal/delete/') ?>' + id;
    }
}
</script>
