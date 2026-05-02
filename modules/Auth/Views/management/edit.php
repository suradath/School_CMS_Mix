<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-gray-50">
            <h3 class="text-xl font-bold text-slate-800 heading-font"><?= isset($user) ? 'แก้ไขผู้ใช้งาน' : 'เพิ่มผู้ใช้งานใหม่' ?></h3>
            <p class="text-sm text-slate-500 mt-1">กรอกข้อมูลบัญชีผู้ใช้งานและกำหนดสิทธิ์การเข้าถึง</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
        <div class="mx-6 md:mx-8 mt-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center text-rose-600">
            <i class="fa fa-exclamation-triangle mr-3"></i>
            <p class="text-sm font-bold"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
        <div class="mx-6 md:mx-8 mt-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center text-emerald-600">
            <i class="fa fa-check-circle mr-3"></i>
            <p class="text-sm font-bold"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
        </div>
        <?php endif; ?>

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

                <!-- Roles -->
                <div class="md:col-span-2" x-data="{ selectedRoles: <?= json_encode(isset($user['roles']) ? array_column($user['roles'], 'id') : []) ?> }">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">บทบาทการใช้งาน (Roles) <span class="text-primary">* เลือกได้มากกว่า 1 บทบาท</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php foreach ($allRoles as $role): ?>
                        <label class="relative flex items-center p-4 rounded-2xl border transition-all cursor-pointer group" 
                               :class="selectedRoles.includes(<?= $role['id'] ?>) ? 'border-primary bg-primary/5 ring-4 ring-primary/5' : 'border-gray-100 bg-slate-50 hover:bg-white hover:border-primary/30'">
                            <input type="checkbox" name="roles[]" value="<?= $role['id'] ?>" 
                                   class="w-5 h-5 rounded-lg border-gray-300 text-primary focus:ring-primary transition-all"
                                   x-model="selectedRoles"
                                   :value="<?= $role['id'] ?>">
                            <div class="ml-3">
                                <p class="text-sm font-bold transition-colors" :class="selectedRoles.includes(<?= $role['id'] ?>) ? 'text-primary' : 'text-slate-700'"><?= $role['name'] ?></p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight"><?= $role['slug'] ?></p>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>
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
                    <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tight">* สำหรับเจ้าหน้าที่/ครู</p>
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
