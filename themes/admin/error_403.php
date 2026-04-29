<div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
    <div class="relative mb-8">
        <div class="w-32 h-32 bg-rose-50 rounded-full flex items-center justify-center animate-pulse">
            <svg class="w-16 h-16 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11 3a9 9 0 11-18 0 9 9 0 0118 0zM12 5V3m0 2L9 7m3-2l3 2" />
            </svg>
        </div>
        <div class="absolute -top-2 -right-2 w-10 h-10 bg-white rounded-2xl shadow-lg border border-rose-100 flex items-center justify-center">
            <i class="fa fa-lock text-rose-600"></i>
        </div>
    </div>

    <h1 class="text-3xl font-black text-slate-900 heading-font mb-4 tracking-tight">
        การเข้าถึงถูกปฏิเสธ (Access Denied)
    </h1>
    
    <p class="text-slate-500 max-w-md mb-10 leading-relaxed font-medium">
        ขออภัย คุณไม่มีสิทธิ์ในการเข้าถึงหน้านี้ โปรดติดต่อผู้ดูแลระบบหากคุณเชื่อว่านี่คือข้อผิดพลาด หรือลองกลับไปยังหน้าหลัก
    </p>

    <div class="flex flex-col sm:flex-row gap-4">
        <a href="<?= url('/dashboard') ?>" class="px-8 py-4 bg-primary text-white rounded-2xl font-bold text-sm shadow-xl shadow-primary/20 hover:scale-105 transition-all">
            <i class="fa fa-home mr-2"></i>กลับหน้าหลัก
        </a>
        <button onclick="history.back()" class="px-8 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all">
            <i class="fa fa-arrow-left mr-2"></i>ย้อนกลับ
        </button>
    </div>

    <div class="mt-16 pt-8 border-t border-slate-100 w-full max-w-xs">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em]">Error Code: 403 Forbidden</p>
    </div>
</div>
