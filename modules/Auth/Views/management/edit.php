<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-gray-50">
            <h3 class="text-xl font-bold text-slate-800 heading-font"><?= isset($user) ? 'แก้ไขผู้ใช้งาน' : 'เพิ่มผู้ใช้งานใหม่' ?></h3>
            <p class="text-sm text-slate-500 mt-1">กรอกข้อมูลบัญชีผู้ใช้งานและกำหนดสิทธิ์การเข้าถึง</p>
        </div>

        <form action="<?= isset($user) ? url('/admin/users/update/' . $user['id']) : url('/admin/users/store') ?>" method="POST" class="p-6 md:p-8 space-y-6">
            <?= \Core\Security::csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Username -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ชื่อผู้ใช้งาน (Username)</label>
                    <input type="text" name="username" value="<?= $user['username'] ?? '' ?>" <?= isset($user) ? 'readonly' : 'required' ?> class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm <?= isset($user) ? 'opacity-60 cursor-not-allowed' : '' ?>">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">อีเมล (Email)</label>
                    <input type="email" name="email" value="<?= $user['email'] ?? '' ?>" required class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm">
                </div>

                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">ชื่อ-นามสกุล</label>
                    <input type="text" name="full_name" value="<?= $user['full_name'] ?? '' ?>" required class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">บทบาทการใช้งาน (Role)</label>
                    <select name="role" required class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm appearance-none">
                        <option value="teacher" <?= (isset($user) && $user['role'] === 'teacher') ? 'selected' : '' ?>>ครู/บุคลากร (Teacher)</option>
                        <option value="officer" <?= (isset($user) && $user['role'] === 'officer') ? 'selected' : '' ?>>เจ้าหน้าที่ธุรการ (Officer)</option>
                        <option value="director" <?= (isset($user) && $user['role'] === 'director') ? 'selected' : '' ?>>ผู้อำนวยการ (Director)</option>
                        <option value="hr" <?= (isset($user) && $user['role'] === 'hr') ? 'selected' : '' ?>>เจ้าหน้างานบุคคล (HR)</option>
                        <option value="editor" <?= (isset($user) && $user['role'] === 'editor') ? 'selected' : '' ?>>เจ้าหน้าที่ระบบ (Editor)</option>
                        <option value="admin" <?= (isset($user) && $user['role'] === 'admin') ? 'selected' : '' ?>>ผู้ดูแลระบบ (Admin)</option>
                    </select>
                </div>

                <!-- Personnel Mapping -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">เชื่อมโยงกับบุคลากร</label>
                    <select name="personnel_id" class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm appearance-none">
                        <option value="">-- ไม่ระบุ --</option>
                        <?php foreach ($personnel as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= (isset($user) && $user['personnel_id'] == $p['id']) ? 'selected' : '' ?>><?= $p['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tight">* ใช้สำหรับการตรวจสอบสิทธิ์ในกลุ่มสาระฯ</p>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">สถานะบัญชี</label>
                    <select name="status" required class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm appearance-none">
                        <option value="active" <?= (isset($user) && $user['status'] === 'active') ? 'selected' : '' ?>>ใช้งานตามปกติ (Active)</option>
                        <option value="inactive" <?= (isset($user) && $user['status'] === 'inactive') ? 'selected' : '' ?>>ระงับการใช้งาน (Inactive)</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">
                        <?= isset($user) ? 'เปลี่ยนรหัสผ่าน (ปล่อยว่างไว้หากไม่ต้องการเปลี่ยน)' : 'รหัสผ่าน (Password)' ?>
                    </label>
                    <input type="password" name="password" <?= isset($user) ? '' : 'required' ?> class="w-full px-4 py-3 rounded-2xl border border-gray-100 bg-slate-50 focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-50 flex items-center justify-end space-x-4">
                <a href="<?= url('/admin/users') ?>" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">ยกเลิก</a>
                <button type="submit" class="px-10 py-3 bg-primary text-white text-sm font-bold rounded-2xl hover:shadow-lg hover:shadow-primary/30 transition-all">
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>
