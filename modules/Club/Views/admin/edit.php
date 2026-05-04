<div class="mb-8">
    <a href="<?= url('/club') ?>"
        class="inline-flex items-center text-slate-400 hover:text-primary transition-colors font-bold mb-4">
        <i class="fa fa-arrow-left mr-2"></i> กลับหน้าจัดการ
    </a>
    <h2 class="text-3xl font-black text-slate-800 heading-font">แก้ไขชุมนุม: <?= htmlspecialchars($club['name']) ?></h2>
    <p class="text-slate-500 font-medium">แก้ไขข้อมูลชุมนุมและเงื่อนไขการรับสมัคร</p>
</div>

<div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden p-8 max-w-4xl">
    <form action="<?= url('/club/update') ?>" method="POST" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= \Core\Security::csrf_token() ?>">
        <input type="hidden" name="id" value="<?= $club['id'] ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 heading-font">ชื่อชุมนุม <span
                        class="text-rose-500">*</span></label>
                <input type="text" name="name" required value="<?= htmlspecialchars($club['name']) ?>"
                    class="w-full px-5 py-3 rounded-2xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all outline-none text-slate-600 font-medium">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 heading-font">ครูประจำชุมนุม</label>
                <?php if (hasRole('admin')): ?>
                    <select name="advisor_id" required
                        class="w-full px-5 py-3 rounded-2xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all outline-none text-slate-600 font-medium">
                        <?php foreach ($personnel as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ($p['id'] == $club['advisor_id']) ? 'selected' : '' ?>>
                                <?= $p['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <input type="text" readonly
                        value="<?= htmlspecialchars($club['advisor_name'] ?? $_SESSION['user_name']) ?>"
                        class="w-full px-5 py-3 rounded-2xl border border-slate-100 bg-slate-50 text-slate-400 font-medium outline-none">
                    <input type="hidden" name="advisor_id" value="<?= $club['advisor_id'] ?>">
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 heading-font">สถานที่จัดกิจกรรม</label>
                <input type="text" name="location" value="<?= htmlspecialchars($club['location'] ?? '') ?>"
                    class="w-full px-5 py-3 rounded-2xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all outline-none text-slate-600 font-medium">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 heading-font">จำนวนที่รับ (Capacity) <span
                        class="text-rose-500">*</span></label>
                <input type="number" name="capacity" required min="1" value="<?= $club['capacity'] ?>"
                    class="w-full px-5 py-3 rounded-2xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all outline-none text-slate-600 font-medium">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 heading-font">สถานะการรับสมัคร</label>
                <select name="status" required
                    class="w-full px-5 py-3 rounded-2xl border border-slate-200 focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all outline-none text-slate-600 font-medium">
                    <option value="open" <?= ($club['status'] === 'open') ? 'selected' : '' ?>>เปิดรับสมัคร</option>
                    <option value="closed" <?= ($club['status'] === 'closed') ? 'selected' : '' ?>>ปิดรับสมัคร</option>
                    <option value="full" <?= ($club['status'] === 'full') ? 'selected' : '' ?>>เต็มแล้ว</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-4 heading-font">ระดับชั้นที่เปิดรับสมัคร <span
                    class="text-rose-500">*</span></label>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php
                $levels = ['ม.1', 'ม.2', 'ม.3', 'ม.4', 'ม.5', 'ม.6', 'ป.1', 'ป.2', 'ป.3', 'ป.4', 'ป.5', 'ป.6'];
                foreach ($levels as $l): ?>
                    <label
                        class="flex items-center p-3 border <?= in_array($l, $target_grades) ? 'border-primary bg-primary/5' : 'border-slate-200' ?> rounded-2xl cursor-pointer hover:bg-slate-50 transition-all group">
                        <input type="checkbox" name="target_grades[]" value="<?= $l ?>" <?= in_array($l, $target_grades) ? 'checked' : '' ?>
                            class="w-5 h-5 rounded-lg border-slate-300 text-primary focus:ring-primary mr-3">
                        <span
                            class="text-sm font-bold <?= in_array($l, $target_grades) ? 'text-primary' : 'text-slate-600' ?> group-hover:text-primary transition-colors"><?= $l ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex justify-end">
            <button type="submit"
                class="px-10 py-4 bg-primary text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-primary/30 transition-all transform hover:-translate-y-1">
                <i class="fa fa-save mr-2"></i> บันทึกการแก้ไข
            </button>
        </div>
    </form>
</div>