<?php
declare(strict_types=1);

namespace Modules\Attendance\Models;

use Core\Database;

class Attendance
{
    public static function getCoursesWithClasses(int $teacherId): array
    {
        $sql = "SELECT c.*, cl.class_level, cl.room_number, cl.id as link_id
                FROM attendance_courses c
                JOIN attendance_course_classes cl ON c.id = cl.course_id
                WHERE c.teacher_id = ?
                ORDER BY c.course_code ASC, cl.class_level ASC, cl.room_number ASC";
        return Database::fetchAll($sql, [$teacherId]);
    }

    /**
     * Get all courses for a teacher
     */
    public static function getCourses(int $teacherId): array
    {
        return Database::fetchAll("SELECT * FROM attendance_courses WHERE teacher_id = ? ORDER BY course_code ASC", [$teacherId]);
    }

    public static function createCourse(int $teacherId, string $code, string $name): int
    {
        $existing = self::getCourseByCode($teacherId, $code);
        if ($existing) return (int)$existing['id'];

        Database::query("INSERT INTO attendance_courses (teacher_id, course_code, course_name) VALUES (?, ?, ?)", [$teacherId, $code, $name]);
        return (int)Database::getInstance()->lastInsertId();
    }

    /**
     * Get course by code for a teacher
     */
    public static function getCourseByCode(int $teacherId, string $code): array|false
    {
        return Database::fetch("SELECT * FROM attendance_courses WHERE teacher_id = ? AND course_code = ?", [$teacherId, $code]);
    }

    /**
     * Link course to a classroom
     */
    public static function linkClassroom(int $courseId, string $level, int $room): bool
    {
        // Check if link already exists
        $exists = Database::fetch("SELECT id FROM attendance_course_classes WHERE course_id = ? AND class_level = ? AND room_number = ?", [$courseId, $level, $room]);
        if ($exists) return true;

        Database::query("INSERT INTO attendance_course_classes (course_id, class_level, room_number) VALUES (?, ?, ?)", 
            [$courseId, $level, $room]
        );
        return true;
    }

    /**
     * Unlink a classroom from a course
     */
    public static function unlinkClassroom(int $linkId): bool
    {
        Database::query("DELETE FROM attendance_course_classes WHERE id = ?", [$linkId]);
        return true;
    }

    /**
     * Fetch students and their attendance status for a specific date and course
     */
    public static function getStudentsWithStatus(int $courseId, string $level, int $room, string $date): array
    {
        $sql = "SELECT s.id, s.student_code, s.title, s.first_name, s.last_name, 
                       r.status, r.id as record_id
                FROM students s
                LEFT JOIN attendance_records r ON s.id = r.student_id 
                     AND r.course_id = ? 
                     AND r.check_date = ?
                WHERE s.class_level = ? AND s.room_number = ?
                ORDER BY s.student_code ASC";
        
        return Database::fetchAll($sql, [$courseId, $date, $level, $room]);
    }

    /**
     * Save attendance records using UPSERT logic
     */
    public static function saveAttendance(string $date, int $courseId, string $level, int $room, array $records): bool
    {
        try {
            $pdo = Database::getInstance();
            $pdo->beginTransaction();

            $sql = "INSERT INTO attendance_records (check_date, course_id, class_level, room_number, student_id, status) 
                    VALUES (?, ?, ?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE status = VALUES(status), updated_at = CURRENT_TIMESTAMP";
            
            foreach ($records as $studentId => $status) {
                Database::query($sql, [$date, $courseId, $level, $room, $studentId, $status]);
            }

            $pdo->commit();
            return true;
        } catch (\Exception $e) {
            Database::getInstance()->rollBack();
            throw $e;
        }
    }

    /**
     * Get summary statistics for a course and classroom
     */
    public static function getSummaryReport(int $courseId, string $level, int $room): array
    {
        $sql = "SELECT 
                    s.id,
                    s.student_code,
                    s.title,
                    s.first_name,
                    s.last_name,
                    s.class_level,
                    s.room_number,
                    COUNT(r.id) as total_periods,
                    SUM(CASE WHEN r.status = 'present' THEN 1 ELSE 0 END) as count_present,
                    SUM(CASE WHEN r.status = 'late' THEN 1 ELSE 0 END) as count_late,
                    SUM(CASE WHEN r.status = 'absent' THEN 1 ELSE 0 END) as count_absent,
                    SUM(CASE WHEN r.status IN ('personal_leave', 'sick_leave') THEN 1 ELSE 0 END) as count_leave,
                    ROUND(
                        (SUM(CASE WHEN r.status IN ('present', 'late', 'personal_leave', 'sick_leave') THEN 1 ELSE 0 END) / COUNT(r.id)) * 100, 
                        2
                    ) as attendance_percentage
                FROM students s
                JOIN attendance_records r ON s.id = r.student_id
                WHERE r.course_id = ? 
                  AND r.class_level = ? 
                  AND r.room_number = ?
                GROUP BY s.id
                ORDER BY s.student_code ASC";
        
        return Database::fetchAll($sql, [$courseId, $level, $room]);
    }

    /**
     * Get detailed attendance history for a specific student and course
     */
    public static function getStudentAttendanceHistory(int $studentId, int $courseId): array
    {
        $sql = "SELECT check_date, status 
                FROM attendance_records 
                WHERE student_id = ? AND course_id = ? 
                ORDER BY check_date ASC";
        return Database::fetchAll($sql, [$studentId, $courseId]);
    }

    public static function deleteCourse(int $id, int $teacherId): bool
    {
        Database::query("DELETE FROM attendance_courses WHERE id = ? AND teacher_id = ?", [$id, $teacherId]);
        return true;
    }
}
