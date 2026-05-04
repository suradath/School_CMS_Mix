<?php
declare(strict_types=1);

namespace Modules\Club\Models;

use Core\Database;

class StudentAuth
{
    public static function authenticate(string $studentCode, string $password): array|false
    {
        // Find student by code
        $student = Database::fetch("SELECT * FROM students WHERE student_code = ?", [$studentCode]);
        
        if ($student) {
            // Check password: Last 5 digits of citizen_id
            $last5 = substr($student['citizen_id'], -5);
            if ($password === $last5) {
                return $student;
            }
        }
        
        return false;
    }

    public static function getStudent(int $id): array|false
    {
        return Database::fetch("SELECT * FROM students WHERE id = ?", [$id]);
    }
}
