<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">บันทึกการเข้ารับบริการ/การรักษา</h1>
            <p class="mt-2 text-gray-600">ค้นหานักเรียนและบันทึกรายละเอียดการรักษารายบุคคล</p>
        </div>
        <a href="<?= url('/admin/health') ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors">
            <i class="fa fa-arrow-left mr-2"></i> ยกเลิก
        </a>
    </div>

    <form action="<?= url('/admin/health/store-treatment') ?>" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <?= \Core\Security::csrf_field() ?>
        
        <!-- Left: Student Selection -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fa fa-search text-blue-500 mr-2"></i> 1. ค้นหานักเรียน
                </h3>
                <div class="relative mb-6">
                    <input type="text" id="studentSearch" placeholder="ชื่อ-สกุล หรือ รหัสนักเรียน..." class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-4 pl-12 shadow-sm">
                    <i class="fa fa-search absolute left-4 top-4.5 text-gray-400"></i>
                    <div id="searchResults" class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-2xl shadow-xl hidden overflow-hidden">
                        <!-- Search items will be injected here -->
                    </div>
                </div>

                <div id="selectedStudentInfo" class="hidden animate-fade-in">
                    <input type="hidden" name="student_id" id="studentIdInput">
                    <div class="p-6 bg-blue-50 rounded-2xl border border-blue-100">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-blue-200 flex items-center justify-center text-blue-600 mr-4">
                                <i class="fa fa-user"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900" id="studentNameDisplay"></p>
                                <p class="text-xs text-gray-500" id="studentCodeDisplay"></p>
                            </div>
                        </div>
                        <div class="space-y-3 pt-3 border-t border-blue-200">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-blue-400 block mb-1">โรคประจำตัว</span>
                                <p class="text-sm text-gray-700" id="studentChronicDisplay">-</p>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-red-400 block mb-1">ประวัติการแพ้ยา</span>
                                <p class="text-sm text-red-600 font-bold" id="studentAllergyDisplay">-</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="button" onclick="openHealthEdit()" class="w-full py-2 bg-white/50 hover:bg-white text-blue-700 text-xs font-bold rounded-xl border border-blue-200 transition-colors">
                                <i class="fa fa-edit mr-1"></i> แก้ไขข้อมูลสุขภาพ
                            </button>
                        </div>
                    </div>
                </div>

                <div id="healthEditForm" class="hidden animate-fade-in">
                    <form action="<?= url('/admin/health/student-update') ?>" method="POST" class="p-6 bg-gray-50 rounded-2xl border border-gray-200 space-y-4">
                        <?= \Core\Security::csrf_field() ?>
                        <input type="hidden" name="student_id" id="editStudentId">
                        <h4 class="text-sm font-bold text-gray-900">แก้ไขข้อมูลสุขภาพ</h4>
                        <div>
                            <label class="block mb-1 text-[10px] font-bold text-gray-500 uppercase">โรคประจำตัว</label>
                            <textarea name="chronic_disease" id="editChronic" rows="2" class="w-full bg-white border border-gray-300 text-sm rounded-xl p-3"></textarea>
                        </div>
                        <div>
                            <label class="block mb-1 text-[10px] font-bold text-gray-500 uppercase">ประวัติการแพ้ยา</label>
                            <textarea name="medication_allergy" id="editAllergy" rows="2" class="w-full bg-white border border-gray-300 text-sm rounded-xl p-3 text-red-600 font-bold"></textarea>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="flex-grow py-2 bg-blue-600 text-white text-xs font-bold rounded-xl">บันทึก</button>
                            <button type="button" onclick="closeHealthEdit()" class="px-4 py-2 bg-gray-200 text-gray-600 text-xs font-bold rounded-xl">ยกเลิก</button>
                        </div>
                    </form>
                </div>
                
                <div id="noStudentSelected" class="text-center py-12 border-2 border-dashed border-gray-100 rounded-3xl">
                    <i class="fa fa-user-circle text-4xl text-gray-100 mb-2"></i>
                    <p class="text-gray-400 text-xs">กรุณาค้นหาและเลือกนักเรียนก่อน</p>
                </div>
            </div>
        </div>

        <!-- Right: Treatment Details -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fa fa-clipboard-list text-blue-500 mr-2"></i> 2. รายละเอียดการเข้ารับบริการ
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900">อาการเบื้องต้น <span class="text-red-500">*</span></label>
                        <textarea name="symptoms" rows="3" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4" placeholder="ระบุอาการที่นักเรียนแจ้ง..."></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-900">การประเมิน/การรักษา</label>
                        <textarea name="treatment" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4" placeholder="บันทึกการประเมินเบื้องต้นหรือการดูแลรักษา..."></textarea>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-100">
                    <h4 class="text-md font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fa fa-pills text-emerald-500 mr-2"></i> การจ่ายยา (Dispensing)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($medicines as $med): ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 group hover:border-emerald-200 transition-colors">
                                <div class="flex-grow">
                                    <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($med['name']) ?></p>
                                    <p class="text-[10px] text-gray-400">สต๊อกคงเหลือ: <?= $med['stock_quantity'] ?></p>
                                </div>
                                <div class="w-24">
                                    <input type="number" name="medicines[<?= $med['id'] ?>]" min="0" max="<?= $med['stock_quantity'] ?>" value="0" class="w-full bg-white border border-gray-200 text-center rounded-lg p-2 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-100">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="isReferral" name="is_referral" class="w-5 h-5 text-amber-600 bg-gray-100 border-gray-300 rounded focus:ring-amber-500" onchange="toggleReferralFields()">
                        <label for="isReferral" class="ml-3 text-sm font-bold text-gray-900">ส่งต่อโรงพยาบาล (Referral)</label>
                    </div>
                    <div id="referralFields" class="hidden space-y-4 animate-slide-down">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">ชื่อโรงพยาบาล</label>
                                <input type="text" name="referral_hospital" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block w-full p-4" placeholder="เช่น รพ.ลำปลายมาศ">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">สาเหตุการส่งต่อ</label>
                                <input type="text" name="referral_reason" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-amber-500 focus:border-amber-500 block w-full p-4" placeholder="ระบุเหตุผลที่ต้องส่งต่อ">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-2xl shadow-xl shadow-emerald-100 transition-all transform hover:scale-[1.02] active:scale-95">
                        บันทึกข้อมูลการรักษา
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const studentSearch = document.getElementById('studentSearch');
const searchResults = document.getElementById('searchResults');
const selectedStudentInfo = document.getElementById('selectedStudentInfo');
const noStudentSelected = document.getElementById('noStudentSelected');
const studentIdInput = document.getElementById('studentIdInput');

const studentNameDisplay = document.getElementById('studentNameDisplay');
const studentCodeDisplay = document.getElementById('studentCodeDisplay');
const studentChronicDisplay = document.getElementById('studentChronicDisplay');
const studentAllergyDisplay = document.getElementById('studentAllergyDisplay');

studentSearch.addEventListener('input', async (e) => {
    const q = e.target.value;
    if (q.length < 2) {
        searchResults.classList.add('hidden');
        return;
    }

    const response = await fetch(`<?= url('/admin/health/search-students') ?>?q=${encodeURIComponent(q)}`);
    const students = await response.json();

    if (students.length > 0) {
        searchResults.innerHTML = '';
        students.forEach(s => {
            const div = document.createElement('div');
            div.className = 'p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0 transition-colors';
            div.innerHTML = `
                <p class="text-sm font-bold text-gray-900">${s.first_name} ${s.last_name}</p>
                <p class="text-xs text-gray-500">รหัส: ${s.student_code} | ชั้น: ${s.class_level}/${s.room_number}</p>
            `;
            div.onclick = () => selectStudent(s);
            searchResults.appendChild(div);
        });
        searchResults.classList.remove('hidden');
    } else {
        searchResults.classList.add('hidden');
    }
});

function selectStudent(s) {
    studentIdInput.value = s.id;
    studentNameDisplay.innerText = `${s.first_name} ${s.last_name}`;
    studentCodeDisplay.innerText = `รหัสนักเรียน: ${s.student_code} | ชั้น: ${s.class_level}/${s.room_number}`;
    studentChronicDisplay.innerText = s.chronic_disease || '-';
    studentAllergyDisplay.innerText = s.medication_allergy || '-';

    selectedStudentInfo.classList.remove('hidden');
    noStudentSelected.classList.add('hidden');
    searchResults.classList.add('hidden');
    studentSearch.value = '';

    // Pre-fill edit form
    document.getElementById('editStudentId').value = s.id;
    document.getElementById('editChronic').value = s.chronic_disease || '';
    document.getElementById('editAllergy').value = s.medication_allergy || '';
}

function openHealthEdit() {
    selectedStudentInfo.classList.add('hidden');
    document.getElementById('healthEditForm').classList.remove('hidden');
}

function closeHealthEdit() {
    document.getElementById('healthEditForm').classList.add('hidden');
    selectedStudentInfo.classList.remove('hidden');
}

function toggleReferralFields() {
    const referralFields = document.getElementById('referralFields');
    const isReferral = document.getElementById('isReferral');
    if (isReferral.checked) {
        referralFields.classList.remove('hidden');
    } else {
        referralFields.classList.add('hidden');
    }
}

// Close search results when clicking outside
document.addEventListener('click', (e) => {
    if (!studentSearch.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.add('hidden');
    }
});
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slide-down {
    from { opacity: 0; max-height: 0; overflow: hidden; }
    to { opacity: 1; max-height: 200px; }
}
.animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
.animate-slide-down { animation: slide-down 0.4s ease-out forwards; }
</style>
