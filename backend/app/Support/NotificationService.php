<?php

namespace App\Support;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Send notification to a specific user
     */
    public static function sendToUser(int $userId, string $title, string $message, string $type = 'info'): void
    {
        Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
        ]);
    }

    /**
     * Send notification to all users with a specific role
     */
    public static function sendToRole(string $role, string $title, string $message, string $type = 'info'): void
    {
        $users = User::where('role', $role)->pluck('id');
        
        foreach ($users as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
            ]);
        }
    }

    /**
     * Send notification to all users
     */
    public static function sendToAll(string $title, string $message, string $type = 'info'): void
    {
        $users = User::pluck('id');
        
        foreach ($users as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'message' => $message,
                'type' => $type,
            ]);
        }
    }

    /**
     * Notify student about grade posted
     */
    public static function notifyGradePosted(int $studentId, string $subjectName, string $grade): void
    {
        self::sendToUser(
            $studentId,
            'Grade Posted',
            "Your grade for {$subjectName} has been posted: {$grade}",
            'success'
        );
    }

    /**
     * Notify student about violation
     */
    public static function notifyViolation(int $studentId, string $violationType, string $severity): void
    {
        self::sendToUser(
            $studentId,
            'Violation Notice',
            "A {$severity} violation has been recorded: {$violationType}. Please contact the Dean's office.",
            'warning'
        );
    }

    /**
     * Notify student about event registration
     */
    public static function notifyEventRegistration(int $studentId, string $eventName, bool $approved = true): void
    {
        if ($approved) {
            self::sendToUser(
                $studentId,
                'Event Registration Confirmed',
                "You have been successfully registered for {$eventName}.",
                'success'
            );
        } else {
            self::sendToUser(
                $studentId,
                'Event Registration',
                "Your registration for {$eventName} is pending approval.",
                'info'
            );
        }
    }

    /**
     * Notify all students about new event
     */
    public static function notifyNewEvent(string $eventName, string $category, ?string $description = null): void
    {
        $message = "A new {$category} event has been created: {$eventName}.";
        if ($description) {
            $message .= " {$description}";
        }
        
        self::sendToRole('student', 'New Event Available', $message, 'info');
    }

    /**
     * Notify faculty about new class assignment
     */
    public static function notifyFacultyAssignment(int $facultyId, string $subjectName, string $sectionName): void
    {
        self::sendToUser(
            $facultyId,
            'New Class Assignment',
            "You have been assigned to teach {$subjectName} for section {$sectionName}.",
            'info'
        );
    }

    /**
     * Notify dean about new student enrollment
     */
    public static function notifyDeanNewStudent(string $studentName, string $courseName): void
    {
        self::sendToRole(
            'dean',
            'New Student Enrolled',
            "{$studentName} has been enrolled in {$courseName}.",
            'info'
        );
    }

    /**
     * Notify dean about new faculty member
     */
    public static function notifyDeanNewFaculty(string $facultyName, string $department): void
    {
        self::sendToRole(
            'dean',
            'New Faculty Member',
            "{$facultyName} has been added to the {$department} department.",
            'info'
        );
    }

    /**
     * Send system-wide announcement
     */
    public static function sendAnnouncement(string $title, string $message, string $type = 'info'): void
    {
        self::sendToAll($title, $message, $type);
    }
}
