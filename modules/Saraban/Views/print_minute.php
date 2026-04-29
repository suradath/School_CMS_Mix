<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบปะหน้าเกษียณหนังสือ - <?= $doc['doc_no'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
            .print-area { border: none !important; box-shadow: none !important; }
        }
        body { font-family: 'Sarabun', sans-serif; background-color: #f8fafc; }
        .print-area { background: white; width: 210mm; min-height: 297mm; margin: 20px auto; padding: 25mm; box-shadow: 0 0 10px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
        .thai-stamp { border: 4px solid; padding: 4px 8px; font-weight: 900; text-transform: uppercase; transform: rotate(-5deg); display: inline-block; }
    </style>
</head>
<body class="py-10">
    <div class="max-w-[210mm] mx-auto mb-6 no-print flex justify-between items-center bg-white p-4 rounded-2xl shadow-sm border border-slate-100">
        <p class="text-sm font-bold text-slate-500">ตัวอย่างก่อนพิมพ์: ใบปะหน้าเกษียณหนังสือ</p>
        <button onclick="window.print()" class="bg-slate-900 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-slate-800 transition-all">
            พิมพ์เอกสาร
        </button>
    </div>

    <div class="print-area">
        <div class="flex justify-between items-start border-b-2 border-slate-900 pb-8 mb-10">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 mb-2">ใบปะหน้าเกษียณหนังสือ</h1>
                <p class="text-slate-600">งานสารบรรณอิเล็กทรอนิกส์ - ระบบ School CMS Mix</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold">เลขทะเบียนรับ: <span class="text-xl"><?= $doc['doc_no'] ?></span></p>
                <p class="text-sm">วันที่รับ: <?= date('d/m/Y', strtotime($doc['received_date'] ?: $doc['created_at'])) ?></p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-10">
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">เรื่อง</label>
                    <p class="text-lg font-bold leading-tight"><?= $doc['title'] ?></p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">เลขที่หนังสือ</label>
                    <p class="text-sm font-bold"><?= $doc['book_no'] ?: '-' ?></p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">จากหน่วยงาน</label>
                    <p class="text-sm font-bold"><?= $doc['origin'] ?: '-' ?></p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">ลงวันที่</label>
                    <p class="text-sm font-bold"><?= date('d/m/Y', strtotime($doc['doc_date'])) ?></p>
                </div>
            </div>
        </div>

        <div class="border-2 border-slate-900 rounded-3xl p-8 mb-10 min-h-[500px]">
            <h2 class="text-xl font-bold border-b border-slate-200 pb-4 mb-6">บันทึกข้อความสั่งการ / การวินิจฉัย</h2>
            
            <div class="space-y-10">
                <?php foreach ($minutes as $m): ?>
                <div class="relative pl-6 border-l-2 border-slate-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-grow">
                            <p class="text-sm text-slate-700 leading-relaxed italic">"<?= nl2br(htmlspecialchars($m['note'])) ?>"</p>
                        </div>
                        <?php if ($m['decision'] !== 'none'): ?>
                        <div class="ml-6">
                            <?php 
                                $decConfig = [
                                    'approved' => ['label' => 'อนุมัติ', 'color' => 'text-emerald-600'],
                                    'acknowledged' => ['label' => 'ทราบ', 'color' => 'text-blue-600'],
                                    'forwarded' => ['label' => 'มอบหมาย', 'color' => 'text-amber-600'],
                                    'rejected' => ['label' => 'ทักท้วง', 'color' => 'text-rose-600'],
                                ];
                                $d = $decConfig[$m['decision']];
                            ?>
                            <div class="thai-stamp <?= $d['color'] ?> text-lg">
                                <?= $d['label'] ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-sm font-bold text-slate-900">(ลงชื่อ) ............................................................</p>
                        <p class="text-sm mt-1"><?= $m['full_name'] ?></p>
                        <p class="text-xs text-slate-500 uppercase"><?= $m['position'] ?></p>
                        <p class="text-[10px] text-slate-400 mt-1"><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?> น.</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="text-center text-[10px] text-slate-400 mt-20 border-t pt-4 italic">
            * เอกสารนี้สร้างโดยระบบงานสารบรรณอิเล็กทรอนิกส์ School CMS Mix V2.5 และมีการยืนยันตัวตนผ่านระบบดิจิทัล *
        </div>
    </div>
</body>
</html>
