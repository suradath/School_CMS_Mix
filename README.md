# School CMS Mix V2.9

[![PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-777bb4.svg)](https://www.php.net/)
[![Tailwind CSS](https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

[**🇹🇭 ภาษาไทย**](#-ภาษาไทย) | [**🇺🇸 English**](#-english)

---

## 🇹🇭 ภาษาไทย

### รายละเอียดโปรเจกต์
**School CMS Mix V2.9** คือระบบบริหารจัดการเนื้อหาเว็บไซต์โรงเรียนที่สมบูรณ์แบบที่สุด พัฒนาด้วย PHP 8.1+ ร่วมกับ Tailwind CSS มอบประสบการณ์การใช้งานระดับพรีเมียม (Premium UI/UX)
- **ระบบสารบรรณอิเล็กทรอนิกส์ (E-Saraban)**: รับ-ส่งหนังสือราชการ, เกษียณหนังสือ, และจัดเก็บเอกสาร
- **ระบบชุมนุมออนไลน์ (Online Club Registration)**: ระบบรับสมัครชุมนุมสำหรับนักเรียน, จัดการโควตา, เช็คชื่อ และประเมินผลการเรียน
- **ระบบลาออนไลน์ (Leave Management)**: จัดการการลาของบุคลากรพร้อมระบบอนุมัติ
- **ระบบงานปกครองและ PLC**: จัดการพฤติกรรมนักเรียนและชุมชนแห่งการเรียนรู้ทางวิชาชีพที่ครบถ้วน

### ✨ คุณสมบัติเด่น
- **E-Saraban (ระบบสารบรรณ):** ลงทะเบียนรับ-ส่ง, ออกเลขที่อัตโนมัติ, เกษียณหนังสือแบบ Timeline, และระบบเวียนเอกสารดิจิทัล
- **Leave Management (ระบบลาออนไลน์):** เขียนใบลาออนไลน์, คำนวณวันลาอัตโนมัติ (หักวันหยุด), และสรุปรายงานสำหรับ HR
- **Online Club Registration (ระบบชุมนุมออนไลน์):** ระบบให้นักเรียนเลือกชุมนุมผ่านระบบออนไลน์, กำหนดโควตาจำนวนนักเรียนต่อชุมนุม, ระบบตรวจสอบซ้ำ, ระบบยกเลิกการสมัครเพื่อเปลี่ยนชุมนุม (ตราบเท่าที่ระบบยังเปิด), ระบบเช็คชื่อรายครั้ง, รายงานสรุปการเข้าเรียน (รายบุคคล/ร้อยละ), และระบบประเมินผลผ่าน/ไม่ผ่านสำหรับครูประจำชุมนุม
- **Modern UI/UX:** ดีไซน์พรีเมียมด้วย Glassmorphism รองรับการแสดงผลทุกหน้าจอ (Responsive)
- **Dashboard & Analytics:** สรุปสถิติภาพรวมโรงเรียนในรูปแบบกราฟและตัวเลข
- **SIS (Student Information System):** ระบบจัดการข้อมูลนักเรียนครบวงจร (DMC Compatible)
- **Attendance System:** ระบบเช็คชื่อเข้าเรียนรายวิชา/รายวัน พร้อมระบบ Upsert กันข้อมูลซ้ำ
- **Attendance Reports:** รายงานสถิติการเข้าเรียนรายบุคคล/รายห้อง พร้อมร้อยละการมาเรียน
- **Student Health & Clinic System:** ระบบงานพยาบาลครบวงจร จัดการน้ำหนัก-ส่วนสูง (BMI), ประวัติการรักษา, การเบิกจ่ายยาพร้อมระบบตัดสต๊อกอัตโนมัติ (Inventory Control), ระบบส่งต่อโรงพยาบาล, และระบบแจ้งเตือนยาใกล้หมด
- **BMI Data Management:** ระบบนำเข้า/ส่งออกข้อมูลน้ำหนัก-ส่วนสูงผ่านไฟล์ CSV (Excel Compatible) เพื่อการติดตามภาวะโภชนาการแบบรายห้องเรียน
- **Health Records & Pharmacy:** บันทึกประวัติการแพ้ยาและโรคประจำตัวรายบุคคล, ระบบจ่ายยาที่เชื่อมโยงกับคลังยาแบบ Real-time ด้วย Database Transaction ป้องกันความผิดพลาดของข้อมูลสต๊อก
- **User Management & RBAC:** ระบบจัดการผู้ใช้และสิทธิ์แบบ **Multi-Role** (1 ผู้ใช้มีได้หลายบทบาท) พร้อม UI ที่ทันสมัย
- **Academic Calendar:** ปฏิทินวิชาการแสดงผลแบบ Interactive พร้อมรายละเอียดกิจกรรม
- **Entry Popup:** ระบบจัดการป๊อปอัพแจ้งเตือนหน้าแรก (Welcome Popup)
- **Document Submission System:** ระบบส่งเอกสารและผลงานวิชาการ รองรับ Drag & Drop, ตรวจสอบไฟล์ secure MIME, และระบบอนุมัติ/ตีกลับ (Revision) พร้อมส่งออก Excel
- **Complaint System:** ระบบรับเรื่องร้องเรียนและข้อเสนอแนะ ติดตามสถานะ และแจ้งเตือนผ่านระบบ
- **Helpdesk / Maintenance System:** ระบบแจ้งซ่อมและบำรุงรักษาอาคารสถานที่/ไอที พร้อมระบบอัปโหลดรูปภาพประกอบและติดตามสถานะงานซ่อมแบบ Real-time
- **Booking System (ระบบจองทรัพยากร):** จองห้องประชุมและยานพาหนะผ่านปฏิทิน Interactive (FullCalendar), ตรวจสอบการจองซ้ำอัตโนมัติ, และระบบอนุมัติโดยฝ่ายบริหารทั่วไป
- **Student Discipline System (ระบบงานปกครอง):** จัดการคะแนนความประพฤติ, ระบบหักคะแนนอัตโนมัติจากการเช็คชื่อ (มาสาย/ขาดเรียน), และประวัติพฤติกรรมรายบุคคลสำหรับนักเรียน/ผู้ปกครอง
- **PLC System (ระบบชุมชนแห่งการเรียนรู้):** บันทึกชั่วโมง PLC, ระบบอนุมัติโดยหัวหน้ากลุ่ม, คลังสื่อแบ่งปันทรัพยากร, และออกรายงานสรุปสำหรับยื่นวิทยฐานะ (Print-friendly)
- **Easy Installer:** ระบบติดตั้งอัตโนมัติผ่านหน้าเว็บ สะดวกและรวดเร็ว

### 🛠️ เทคโนโลยีที่ใช้
- **Back-end:** PHP 8.1+ (Strict Types)
- **Front-end:** Tailwind CSS, Flowbite, Vanilla JS, Alpine.js, DataTables (Tailwind Integration)
- **Database:** MySQL / MariaDB (InnoDB)
- **Charts:** Chart.js (สำหรับการลาและระบบสุขภาพ)

---

## 🇺🇸 English

### Project Overview
**School CMS Mix V2.9** is a comprehensive School Management System built on PHP 8.1+ and Tailwind CSS. It features a premium UI/UX design with integrated E-Saraban, Online Club Registration, Student Discipline System, and PLC Management modules tailored for modern educational institutions.

### ✨ Key Features
- **User Management & RBAC:** Advanced role-based access control with **Multi-Role support** (1 user can have multiple roles).
- **E-Saraban System:** Digital document registration, auto-numbering, minute-note timeline, and document distribution.
- **Online Leave System:** Digital leave requests, automatic work-day calculation, and HR summary reports.
- **Modern UI/UX:** Premium design using Glassmorphism, fully responsive for all devices.
- **Student Information System (SIS):** Student directory and dashboard with easy CSV import from DMC.
- **Attendance System:** Individual and subject-based attendance tracking with daily reports and percentage calculation.
- **Student Health & Clinic System:** Comprehensive health management with BMI tracking, medical records, and automatic pharmacy inventory control with real-time stock deduction.
- **BMI Data Management:** CSV import/export system for weight and height tracking (Excel compatible) with nutritional status analysis.
- **Pharmacy & Treatment Logs:** Individual allergy and chronic disease profiles, treatment logging with hospital referral support, and stock management with Low-Stock alerts.
- **Online Club Registration:** Student-side club selection system with quota management, smart filtering, registration withdrawal (while system is open), attendance tracking with summary reports (individual & percentage), and teacher evaluation tools.
- **Academic Calendar:** Interactive school calendar with event management.
- **Document Submission System:** Secure academic document upload system with Drag & Drop, MIME validation, and approval workflow.
- **Complaint System:** Public feedback and complaint management system with badge notifications and status tracking.
- **Helpdesk System:** Maintenance and repair management system for facilities and IT with photo uploads and real-time status tracking.
- **Booking System:** Interactive resource booking (Rooms & Vehicles) with FullCalendar integration, double-booking prevention, and admin approval workflow.
- **Student Discipline System:** Behavior point management, automatic deduction from attendance logs (late/absent), and student/parent history view.
- **PLC System:** Professional Learning Community hours tracking, head-teacher approval workflow, material sharing, and formal printable reports for promotion.
- **Easy Installer:** User-friendly web installer for quick deployment.

---

## 📁 โครงสร้างโปรเจกต์ / Project Structure

```text
/cms
├── Core/               # Core System (Database, Router, Security)
├── docs/               # Manuals & Documentation
├── Modules/            # Functional Modules (Saraban, Leave, Students, Health)
├── Themes/             # UI Templates (Default & Admin)
├── uploads/            # Media & Document Storage
├── index.php           # Main Entry Point
└── install.php         # Web Installer Script
```

## 🚀 การติดตั้งระบบ (Installation)
1. อัปโหลดไฟล์ทั้งหมดขึ้นบนเซิร์ฟเวอร์
2. สร้างฐานข้อมูล MySQL (Collation: `utf8mb4_unicode_ci`)
3. เข้าใช้งานไฟล์ `install.php` ผ่าน Browser และทำตามขั้นตอน
4. **สำคัญ:** เมื่อติดตั้งเสร็จแล้ว ให้ลบไฟล์ `install.php` เพื่อความปลอดภัย
5. 📺 **วิดีโอสอนการติดตั้ง:** [ดูบน YouTube](https://www.youtube.com/watch?v=w7unbm3_Yr4)

## 📖 เอกสารประกอบการใช้งาน (Documentation)
- [🇹🇭 คู่มือการติดตั้ง (Installation Guide)](docs/installation_guide.md)
- [🇹🇭 คู่มือการใช้งาน (User Manual)](docs/user_guide.md)
- [🇹🇭 คู่มือการนำเข้าข้อมูล DMC (DMC Import Guide)](docs/dmc_import_guide.md)
---

### 📸 ภาพตัวอย่างระบบ (System Screenshots)

<div align="center">
    <img src="screenshot/01.png" width="100%" style="margin-bottom: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <img src="screenshot/02.png" width="100%" style="margin-bottom: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <img src="screenshot/03.png" width="100%" style="margin-bottom: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <img src="screenshot/04.png" width="100%" style="margin-bottom: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <img src="screenshot/05.png" width="100%" style="margin-bottom: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <img src="screenshot/06.png" width="100%" style="margin-bottom: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <img src="screenshot/07.png" width="100%" style="margin-bottom: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
</div>

---
---
&copy; 2569 **School CMS Mix V2.9**. พัฒนาโดย **ครูสุรเดช ปุยะติ** (โรงเรียนลำปลายมาศ)

---