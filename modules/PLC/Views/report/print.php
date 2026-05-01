<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; font-size: 14px; line-height: 1.6; color: #333; margin: 0; padding: 40px; }
        .header { text-align: center; margin-bottom: 40px; }
        .header h1 { font-size: 20px; margin: 0; }
        .header p { margin: 5px 0; font-size: 16px; }
        .info-section { margin-bottom: 30px; }
        .info-grid { display: grid; grid-template-cols: 1fr 1fr; gap: 10px; margin-top: 10px; }
        .info-label { font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table th, table td { border: 1px solid #000; padding: 10px; text-align: left; }
        table th { background-color: #f5f5f5; text-align: center; font-weight: bold; }
        
        .summary-box { border: 2px solid #000; padding: 20px; margin-bottom: 40px; }
        .summary-box p { margin: 5px 0; font-size: 16px; font-weight: bold; }
        
        .signature-section { display: grid; grid-template-cols: 1fr 1fr; gap: 40px; margin-top: 60px; text-align: center; }
        .signature-box { padding-top: 40px; }
        .signature-line { border-bottom: 1px dotted #000; display: inline-block; width: 200px; margin-bottom: 5px; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            @page { size: A4; margin: 2cm; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2563eb; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">พิมพ์รายงาน (Print)</button>
    </div>

    <div class="header">
        <h1>แบบสรุปรายงานผลการดำเนินงานชุมชนแห่งการเรียนรู้ทางวิชาชีพ (PLC)</h1>
        <p>ประจำปีการศึกษา พ.ศ. <?= $academicYear ?></p>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div><span class="info-label">ชื่อ-นามสกุล:</span> <?= $user['full_name'] ?></div>
            <div><span class="info-label">ตำแหน่ง:</span> <?= $user['position_name'] ?? 'คุณครู' ?></div>
            <div><span class="info-label">สังกัด:</span> <?= $user['department_name'] ?? 'ไม่ระบุ' ?></div>
            <div><span class="info-label">ชั่วโมงเป้าหมาย:</span> 50 ชั่วโมง</div>
        </div>
    </div>

    <div class="summary-box">
        <p>สรุปชั่วโมงสะสมรวม: <?= $totalHours ?> ชั่วโมง</p>
        <p>สถานะ: <?= $totalHours >= 50 ? 'ครบตามเกณฑ์ที่กำหนด' : 'ยังไม่ครบตามเกณฑ์ (ขาดอีก ' . (50 - $totalHours) . ' ชั่วโมง)' ?></p>
    </div>

    <h3>รายละเอียดกิจกรรมและการประชุม</h3>
    <table>
        <thead>
            <tr>
                <th width="50">ลำดับ</th>
                <th width="100">วันที่</th>
                <th width="200">กลุ่ม PLC</th>
                <th>หัวข้อ/ประเด็นปัญหา</th>
                <th width="80">จำนวนชั่วโมง</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logs)): ?>
                <tr><td colspan="5" style="text-align: center;">ไม่พบข้อมูลกิจกรรมที่ได้รับการอนุมัติ</td></tr>
            <?php else: ?>
                <?php $i = 1; foreach ($logs as $log): ?>
                <tr>
                    <td style="text-align: center;"><?= $i++ ?></td>
                    <td style="text-align: center;"><?= date('d/m/Y', strtotime($log['date'])) ?></td>
                    <td><?= $log['group_name'] ?></td>
                    <td><?= $log['topic'] ?></td>
                    <td style="text-align: center;"><?= $log['hours'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">รวมชั่วโมงสะสมทั้งหมด</td>
                <td style="text-align: center; font-weight: bold;"><?= $totalHours ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <span class="signature-line"></span>
            <p>(<?= $user['full_name'] ?>)</p>
            <p>ผู้รายงาน</p>
        </div>
        <div class="signature-box">
            <span class="signature-line"></span>
            <p>(...........................................................)</p>
            <p>หัวหน้ากลุ่ม PLC / ผู้รับรอง</p>
        </div>
        <div class="signature-box" style="grid-column: span 2;">
            <p style="margin-bottom: 40px;">ความคิดเห็นของผู้อำนวยการโรงเรียน..................................................................................................................</p>
            <span class="signature-line"></span>
            <p>(...........................................................)</p>
            <p>ผู้อำนวยการโรงเรียน</p>
            <p>วันที่ .......... เดือน .................... พ.ศ. ...............</p>
        </div>
    </div>

    <script>
        // Auto print if wanted, or just wait for button click
    </script>
</body>
</html>
