<div class="mb-6 flex justify-between items-center text-right">
    <a href="<?= url('/personnel/create') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition shadow-md">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        เพิ่มบุคลากร
    </a>
</div>

<?php foreach ($departments as $dept): ?>
    <?php if (!empty($dept['members'])): ?>
    <div class="mb-10">
        <h3 class="text-xl font-bold text-gray-800 mb-4 pb-2 border-b-2 border-blue-500 inline-block heading-font">
            <?= $dept['name'] ?>
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($dept['members'] as $person): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="flex p-5">
                    <div class="flex-shrink-0 mr-4">
                        <img class="w-16 h-16 rounded-full object-cover border-2 border-blue-100" 
                             src="<?= $person['image_url'] ? url($person['image_url']) : 'https://ui-avatars.com/api/?name='.urlencode($person['name']).'&background=random' ?>" 
                             alt="<?= $person['name'] ?>">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900"><?= $person['name'] ?></h4>
                        <p class="text-sm text-blue-600 font-medium"><?= $person['position'] ?></p>
                        <div class="mt-3 flex space-x-2">
                            <a href="<?= url('/personnel/edit/' . $person['id']) ?>" class="text-xs bg-gray-50 text-gray-600 px-2 py-1 rounded hover:bg-gray-100 transition">แก้ไข</a>
                            <a href="<?= url('/personnel/delete/' . $person['id']) ?>" class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded hover:bg-red-100 transition" onclick="return confirm('ยืนยันการลบ?')">ลบ</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
<?php endforeach; ?>

<?php if (empty($departments)): ?>
    <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300">
        <p class="text-gray-500">ไม่พบข้อมูลบุคลากร</p>
    </div>
<?php endif; ?>
