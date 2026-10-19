<?php

namespace App\Services\Telegram;

use App\Models\Core\School;
use App\Models\Core\SchoolSetting;
use App\Models\Employees\Employee;
use App\Models\Employees\EmployeeCard;
use App\Models\Employees\EmployeeLeave;
use App\Models\Finance\StudentPayment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TelegramNotificationService
{
    protected ?string $botToken = null;

    protected ?string $chatId = null;

    protected bool $isEnabled = false;

    protected bool $notifyStaffAttendance = true;

    protected bool $notifyStaffLeave = true;

    protected ?string $leaveBotToken = null;

    protected ?string $leaveChatId = null;

    protected ?School $school = null;

    public function __construct()
    {
        $this->school = School::first();
        $schoolId = $this->school?->id ?? 1;

        $settings = SchoolSetting::where('school_id', $schoolId)
            ->whereIn('setting_key', [
                'telegram_bot_token',
                'telegram_chat_id',
                'telegram_bot_enabled',
                'telegram_notify_attendance',
                'telegram_notify_staff_attendance',
                'telegram_notify_payment',
                'telegram_notify_staff_leave',
                'telegram_leave_bot_token',
                'telegram_leave_chat_id',
            ])
            ->pluck('setting_value', 'setting_key');

        $this->botToken = $settings['telegram_bot_token'] ?? config('services.telegram.bot_token', env('TELEGRAM_BOT_TOKEN'));
        $this->chatId = $settings['telegram_chat_id'] ?? config('services.telegram.chat_id', env('TELEGRAM_CHAT_ID'));
        $this->isEnabled = (bool) ($settings['telegram_bot_enabled'] ?? env('TELEGRAM_BOT_ENABLED', false));
        $this->notifyStaffAttendance = (bool) ($settings['telegram_notify_staff_attendance'] ?? true);
        $this->notifyStaffLeave = (bool) ($settings['telegram_notify_staff_leave'] ?? true);
        $this->leaveBotToken = ! empty($settings['telegram_leave_bot_token']) ? $settings['telegram_leave_bot_token'] : null;
        $this->leaveChatId = ! empty($settings['telegram_leave_chat_id']) ? $settings['telegram_leave_chat_id'] : null;
    }

    /**
     * Check if Telegram Bot is configured and active.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->botToken) && ! empty($this->chatId);
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled && $this->isConfigured();
    }

    /**
     * Send arbitrary HTML formatted message to Telegram chat.
     */
    public function sendMessage(string $text, ?string $targetChatId = null, string $parseMode = 'HTML', ?string $customToken = null): array
    {
        $token = $customToken ?: $this->botToken;
        $chat = $targetChatId ?: $this->chatId;

        if (empty($token) || empty($chat)) {
            return [
                'success' => false,
                'message' => 'Telegram Bot Token ឬ Chat ID មិនទាន់ត្រូវបានកំណត់នៅក្នុងការកំណត់ប្រព័ន្ធឡើយ។',
            ];
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        try {
            $response = Http::timeout(10)->post($url, [
                'chat_id' => $chat,
                'text' => $text,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => true,
            ]);

            $json = $response->json();

            if ($response->successful() && ($json['ok'] ?? false)) {
                return [
                    'success' => true,
                    'message' => 'សារត្រូវបានផ្ញើទៅកាន់ Telegram ដោយជោគជ័យ!',
                    'data' => $json['result'] ?? [],
                ];
            }

            $errorDesc = $json['description'] ?? 'Telegram API Error';
            Log::warning("Telegram Send Error: {$errorDesc}");

            return [
                'success' => false,
                'message' => "បរាជ័យពី Telegram API: {$errorDesc}",
            ];
        } catch (\Throwable $e) {
            Log::error('Telegram Connection Exception: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'កំហុសបណ្តាញ ឬ Timeout ក្នុងការតភ្ជាប់ទៅ Telegram API: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Send photo with caption to Telegram chat.
     */
    public function sendPhoto(
        string $photoPathOrUrl,
        string $caption = '',
        ?string $targetChatId = null,
        string $parseMode = 'HTML',
        ?string $customToken = null
    ): array {
        $token = $customToken ?: $this->botToken;
        $chat = $targetChatId ?: $this->chatId;

        if (empty($token) || empty($chat)) {
            return [
                'success' => false,
                'message' => 'Telegram Bot Token ឬ Chat ID មិនទាន់ត្រូវបានកំណត់នៅក្នុងការកំណត់ប្រព័ន្ធឡើយ។',
            ];
        }

        $url = "https://api.telegram.org/bot{$token}/sendPhoto";

        try {
            if (file_exists($photoPathOrUrl)) {
                $response = Http::timeout(30)
                    ->attach('photo', file_get_contents($photoPathOrUrl), basename($photoPathOrUrl))
                    ->post($url, [
                        'chat_id' => $chat,
                        'caption' => $caption,
                        'parse_mode' => $parseMode,
                    ]);
            } else {
                $response = Http::timeout(30)->post($url, [
                    'chat_id' => $chat,
                    'photo' => $photoPathOrUrl,
                    'caption' => $caption,
                    'parse_mode' => $parseMode,
                ]);
            }

            $json = $response->json();

            if ($response->successful() && ($json['ok'] ?? false)) {
                return [
                    'success' => true,
                    'message' => 'រូបភាពត្រូវបានផ្ញើទៅកាន់ Telegram ដោយជោគជ័យ!',
                    'data' => $json['result'] ?? [],
                ];
            }

            $errorDesc = $json['description'] ?? 'Telegram API Error';
            Log::warning("Telegram SendPhoto Error: {$errorDesc}. Falling back to text message.");

            return $this->sendMessage($caption, $targetChatId, $parseMode, $token);
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();
            Log::error('Telegram SendPhoto Exception: '.$errorMsg);

            // ប្រសិនបើតេឡេក្រាមទទួលបានរូបភាពរួចហើយ តែខកខានឆ្លើយតបមកវិញ (cURL error 28 / Timeout) កុំផ្ញើសារអក្សរស្ទួនដាច់ខាត
            if (str_contains(strtolower($errorMsg), 'timed out') || str_contains($errorMsg, '28')) {
                return [
                    'success' => true,
                    'message' => 'រូបភាពត្រូវបានបញ្ជូនទៅ Telegram រួចរាល់។',
                ];
            }

            return $this->sendMessage($caption, $targetChatId, $parseMode, $token);
        }
    }

    /**
     * Send test message to verify connection.
     */
    public function sendTestMessage(?string $customToken = null, ?string $customChatId = null): array
    {
        $originalToken = $this->botToken;
        $originalChatId = $this->chatId;

        if ($customToken) {
            $this->botToken = $customToken;
        }
        if ($customChatId) {
            $this->chatId = $customChatId;
        }

        $schoolName = $this->school?->name ?? 'សាលារៀន ប្រទីបបញ្ញា';
        $now = now()->format('d-M-Y H:i:s');

        $text = "🤖 <b>ការតភ្ជាប់ Telegram Bot ជោគជ័យ (Test Successful)</b>\n\n";
        $text .= "🏫 <b>ស្ថាប័ន៖</b> {$schoolName}\n";
        $text .= "📡 <b>ប្រព័ន្ធ៖</b> Modern School Management System\n";
        $text .= "🕒 <b>កាលបរិច្ឆេទ & ម៉ោង៖</b> <code>{$now}</code>\n";
        $text .= '✅ <b>ស្ថានភាព៖</b> ប្រព័ន្ធដំណឹង Telegram Bot ដំណើរការយ៉ាងរលូន!';

        $res = $this->sendMessage($text);

        // Restore original
        $this->botToken = $originalToken;
        $this->chatId = $originalChatId;

        return $res;
    }

    /**
     * Send Attendance alert to Telegram.
     */
    public function sendAttendanceAlert(string $className, array $stats, ?string $date = null): array
    {
        if (! $this->isEnabled()) {
            return ['success' => false, 'message' => 'Telegram Bot ត្រូវបានបិទដំណើរការ។'];
        }

        $schoolName = $this->school?->name ?? 'សាលារៀន ប្រទីបបញ្ញា';
        $attendanceDate = $date ?: now()->format('d-M-Y');
        $present = (int) ($stats['present'] ?? 0);
        $absent = (int) ($stats['absent'] ?? 0);
        $late = (int) ($stats['late'] ?? 0);
        $excused = (int) ($stats['excused'] ?? 0);
        $total = $present + $absent + $late + $excused;
        $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;

        $text = "📋 <b>របាយការណ៍វត្តមានប្រចាំថ្ងៃ (Daily Attendance Alert)</b>\n";
        $text .= "🏫 <b>សាលា៖</b> {$schoolName}\n";
        $text .= "📚 <b>ថ្នាក់រៀន៖</b> <b>{$className}</b>\n";
        $text .= "📅 <b>កាលបរិច្ឆេទ៖</b> {$attendanceDate}\n";
        $text .= "────────────────────\n";
        $text .= "✅ វត្តមាន (Present): <b>{$present}</b> នាក់\n";
        $text .= "❌ អវត្តមាន (Absent): <b>{$absent}</b> នាក់\n";
        $text .= "⏰ មកយឺត (Late): <b>{$late}</b> នាក់\n";
        $text .= "📝 មានច្បាប់ (Excused): <b>{$excused}</b> នាក់\n";
        $text .= "────────────────────\n";
        $text .= "👥 សិស្សសរុប៖ <b>{$total}</b> នាក់ | អត្រាវត្តមាន៖ <b>{$rate}%</b>";

        return $this->sendMessage($text);
    }

    /**
     * Send Staff/Employee Attendance (Check In / Check Out) alert to Telegram.
     */
    public function sendStaffAttendanceAlert(
        Employee $employee,
        string $action,
        string $time,
        string $status = 'Present',
        ?string $remarks = null,
        ?string $photoPath = null
    ): array {
        if (! $this->isEnabled() || ! $this->notifyStaffAttendance) {
            return ['success' => false, 'message' => 'Telegram Staff Attendance notification ត្រូវបានបិទដំណើរការ។'];
        }

        $employee->loadMissing(['user', 'employee_department', 'employee_position', 'activeCard', 'employeeCards', 'employee_documents']);

        // ឈ្មោះបុគ្គលិកជាភាសាខ្មែរ (បង្ហាញតែមួយ កុំឱ្យស្ទួន)
        $khName = trim(($employee->last_name_khmer ?? '').' '.($employee->first_name_khmer ?? ''));
        $ltName = trim(($employee->last_name ?? '').' '.($employee->first_name ?? ''));
        if (! empty($khName)) {
            $empName = $khName;
        } elseif (! empty($ltName)) {
            $empName = $ltName;
        } else {
            $empName = $employee->user->name ?? 'បុគ្គលិក';
        }

        $empCode = $employee->employee_code ?? '—';
        $department = $employee->employee_department->name ?? 'ទូទៅ';
        $position = $employee->employee_position->name ?? 'បុគ្គលិក';

        // ពិនិត្យកាតបុគ្គលិក (Active Staff Card) - ធានាថាមានបង្ហាញជានិច្ច
        $activeCard = $employee->activeCard
            ?? $employee->employeeCards()->where('status', 'active')->latest('id')->first()
            ?? $employee->employeeCards()->latest('id')->first();

        if (! $activeCard) {
            try {
                $activeCard = EmployeeCard::getOrCreateActiveFor($employee);
            } catch (\Throwable $e) {
                // Ignore fallback
            }
        }
        $cardNum = $activeCard?->card_number ?? ('CRD-'.now()->format('Y').'-'.str_pad((string) $employee->id, 5, '0', STR_PAD_LEFT));

        // បំប្លែងកាលបរិច្ឆេទជាភាសាខ្មែរ
        $khmerMonths = [
            1 => 'មករា', 2 => 'កុម្ភៈ', 3 => 'មីនា', 4 => 'មេសា',
            5 => 'ឧសភា', 6 => 'មិថុនា', 7 => 'កក្កដា', 8 => 'សីហា',
            9 => 'កញ្ញា', 10 => 'តុលា', 11 => 'វិច្ឆិកា', 12 => 'ធ្នូ',
        ];
        $d = str_pad(now()->day, 2, '0', STR_PAD_LEFT);
        $m = $khmerMonths[now()->month] ?? now()->format('M');
        $y = now()->year;
        $dateKh = "{$d}-{$m}-{$y}";

        $isCheckIn = str_contains(strtolower($action), 'in');
        $actionKh = $isCheckIn ? 'ចូលបម្រើការងារ' : 'ចេញពីការងារ';
        $actionIcon = $isCheckIn ? '🟢' : '🔴';

        $statusKh = match (strtolower($status)) {
            'late' => '⏰ មកយឺត',
            'present' => '✅ វត្តមានទាន់ពេល',
            'absent' => '❌ អវត្តមាន',
            default => $status,
        };

        $text = "{$actionIcon} <b>ដំណឹងវត្តមានបុគ្គលិក • {$actionKh}</b>\n";
        $text .= "────────────────────\n";
        $text .= "👤 <b>ឈ្មោះបុគ្គលិក៖</b> <b>{$empName}</b>\n";
        $text .= "🆔 <b>អត្តលេខ៖</b> <code>{$empCode}</code>\n";
        $text .= "🪪 <b>កាតបុគ្គលិក៖</b> <code>{$cardNum}</code>\n";
        $text .= "🏢 <b>ដេប៉ាតឺម៉ង់៖</b> {$department}\n";
        $text .= "💼 <b>មុខតំណែង៖</b> {$position}\n";
        $text .= "────────────────────\n";
        $text .= "📌 <b>សកម្មភាព៖</b> <b>{$actionKh}</b>\n";
        $text .= "⏰ <b>ម៉ោង៖</b> <b>{$time}</b>\n";
        $text .= "📊 <b>ស្ថានភាព៖</b> {$statusKh}\n";
        $text .= "📅 <b>កាលបរិច្ឆេទ៖</b> {$dateKh}\n";
        if ($remarks && ! str_starts_with($remarks, 'QR Scanner Attendance')) {
            $text .= "📝 <b>សម្គាល់៖</b> {$remarks}\n";
        }

        // ស្វែងរករូបភាពកាត ឬរូបថតបុគ្គលិក (Staff Card / Photo Attachment)
        $cardImagePath = null;

        // 1. បង្កើតរូបកាតបុគ្គលិកឌីជីថល (Digital Staff Badge) ភ្ជាប់ជាមួយរូបថត និងព័ត៌មានបុគ្គលិក
        try {
            $cardImagePath = $this->generateDigitalStaffCard($employee, $activeCard);
        } catch (\Throwable $e) {
            Log::warning('Generate Digital Staff Card error: '.$e->getMessage());
        }

        // 2. Fallback: រូបថតផ្លូវការ ឬឯកសារកាតបុគ្គលិកពី employee_documents
        if (! $cardImagePath || ! file_exists($cardImagePath)) {
            $photoDoc = $employee->employee_documents?->where('document_type', 'photo')->first();
            if (! $photoDoc) {
                $photoDoc = $employee->employee_documents()->where('document_type', 'photo')->latest()->first();
            }

            if ($photoDoc && $photoDoc->document_path && Storage::disk('public')->exists($photoDoc->document_path)) {
                $cardImagePath = Storage::disk('public')->path($photoDoc->document_path);
            } elseif (! empty($employee->photo) && file_exists(public_path($employee->photo))) {
                $cardImagePath = public_path($employee->photo);
            } elseif (! empty($employee->photo) && Storage::disk('public')->exists($employee->photo)) {
                $cardImagePath = Storage::disk('public')->path($employee->photo);
            } else {
                $idDoc = $employee->employee_documents()->whereIn('document_type', ['id_card', 'card', 'staff_card'])->latest()->first();
                if ($idDoc && $idDoc->document_path && Storage::disk('public')->exists($idDoc->document_path)) {
                    $cardImagePath = Storage::disk('public')->path($idDoc->document_path);
                }
            }
        }

        // 3. Fallback: រូបថតពី Webcam (ប្រសិនបើមាន)
        if (! $cardImagePath && $photoPath && file_exists($photoPath)) {
            $cardImagePath = $photoPath;
        }

        // 4. ប្រសិនបើមានរូបកាតបុគ្គលិក ផ្ញើជារូបភាព sendPhoto
        if ($cardImagePath && file_exists($cardImagePath)) {
            return $this->sendPhoto($cardImagePath, $text);
        }

        return $this->sendMessage($text);
    }

    /**
     * Send Face-Verified Staff Attendance alert to Telegram with Staff Card image.
     * Specification: Telegram receives the official Staff Card image of the QR card owner, NOT the live camera face.
     */
    public function sendFaceVerifiedAttendanceAlert(
        Employee $employee,
        string $action,
        string $time,
        string $status = 'Present',
        ?string $staffCardImagePath = null
    ): array {
        if (! $this->isEnabled() || ! $this->notifyStaffAttendance) {
            return ['success' => false, 'message' => 'Telegram Staff Attendance notification ត្រូវបានបិទដំណើរការ។'];
        }

        $employee->loadMissing(['user', 'employee_department', 'employee_position']);
        $empName = $employee->full_name ?? $employee->user?->name ?? 'បុគ្គលិក';
        $empCode = $employee->employee_code ?? '—';

        $isCheckIn = str_contains(strtolower($action), 'in');
        $actionIcon = $isCheckIn ? '🟢' : '🔴';
        $statusBadge = strtolower($status) === 'late' ? ' [⏰ យឺត / Late]' : '';

        $text = "{$actionIcon} <b>Attendance Verified</b>\n";
        $text .= "👤 <b>{$empName}</b>\n";
        $text .= "🆔 <code>{$empCode}</code>\n";
        $text .= "📌 <b>{$action}</b>{$statusBadge}\n";
        $text .= "🕐 {$time}\n";
        $text .= "🤳 Face: <b>Verified</b>\n";
        $text .= '🪪 <b>Staff Card</b>';

        // 1. If explicit staff card image provided and exists
        if ($staffCardImagePath && file_exists($staffCardImagePath)) {
            return $this->sendPhoto($staffCardImagePath, $text);
        }

        // 2. Fallback to employee's stored photo card
        $photoRel = $employee->photo ?? null;
        if (! $photoRel) {
            $doc = $employee->employee_documents()->where('document_type', 'photo')->first();
            $photoRel = $doc?->document_path;
        }
        if ($photoRel) {
            $fullPath = Storage::disk('public')->path($photoRel);
            if (file_exists($fullPath)) {
                return $this->sendPhoto($fullPath, $text);
            }
        }

        // 3. Fallback to digital staff card badge
        try {
            $digitalCard = $this->generateDigitalStaffCard($employee);
            if ($digitalCard && file_exists($digitalCard)) {
                return $this->sendPhoto($digitalCard, $text);
            }
        } catch (\Throwable $e) {
            // Ignore fallback
        }

        // 4. Fallback to text message if no image file
        return $this->sendMessage($text);
    }

    /**
     * Send Staff Leave request or approval/rejection alert to Telegram.
     */
    public function sendStaffLeaveAlert(EmployeeLeave $leave, string $action = 'created'): array
    {
        $targetToken = $this->leaveBotToken ?: $this->botToken;
        $targetChatId = $this->leaveChatId ?: $this->chatId;

        if (! $this->isEnabled || ! $this->notifyStaffLeave) {
            return ['success' => false, 'message' => 'Telegram Staff Leave notification ត្រូវបានបិទដំណើរការនៅក្នុងការកំណត់ប្រព័ន្ធ។'];
        }

        if (empty($targetToken) || empty($targetChatId)) {
            return ['success' => false, 'message' => 'Telegram Leaves Bot Token ឬ Chat ID មិនទាន់ត្រូវបានកំណត់ឡើយ។'];
        }

        $leave->loadMissing([
            'employee.user',
            'employee.employee_department',
            'employee.employee_position',
            'employee.activeCard',
            'employee.employeeCards',
            'employee.employee_documents',
            'leave_type',
            'approvedBy',
        ]);

        $employee = $leave->employee;

        // ឈ្មោះបុគ្គលិកតែមួយគត់ (បង្ហាញតែមួយ កុំឱ្យស្ទួន)
        $khName = trim(($employee?->last_name_khmer ?? '').' '.($employee?->first_name_khmer ?? ''));
        $ltName = trim(($employee?->last_name ?? '').' '.($employee?->first_name ?? ''));
        if (! empty($khName)) {
            $empName = $khName;
        } elseif (! empty($ltName)) {
            $empName = $ltName;
        } else {
            $empName = $employee?->user?->name ?? 'បុគ្គលិក';
        }

        $empCode = $employee?->employee_code ?? '—';
        $department = $employee?->employee_department?->name ?? 'ទូទៅ';
        $position = $employee?->employee_position?->name ?? 'បុគ្គលិក';

        // ពិនិត្យកាតបុគ្គលិក (Staff Card Number)
        $activeCard = $employee?->activeCard ?? $employee?->employeeCards?->where('status', 'active')->first();
        $cardNum = $activeCard?->card_number ?? null;

        // ប្រភេទច្បាប់ជាភាសាខ្មែរ
        $leaveTypeName = $leave->leave_type?->khmer_name ?? $leave->leave_type?->name ?? 'ច្បាប់ឈប់សម្រាក';

        // បំប្លែងខែជាភាសាខ្មែរ
        $khmerMonths = [
            1 => 'មករា', 2 => 'កុម្ភៈ', 3 => 'មីនា', 4 => 'មេសា',
            5 => 'ឧសភា', 6 => 'មិថុនា', 7 => 'កក្កដា', 8 => 'សីហា',
            9 => 'កញ្ញា', 10 => 'តុលា', 11 => 'វិច្ឆិកា', 12 => 'ធ្នូ',
        ];

        $fmtKhmerDate = function ($date) use ($khmerMonths): string {
            if (! $date) {
                return '—';
            }
            $d = str_pad($date->day, 2, '0', STR_PAD_LEFT);
            $m = $khmerMonths[$date->month] ?? $date->format('M');
            $y = $date->year;

            return "{$d}-{$m}-{$y}";
        };

        $startKh = $fmtKhmerDate($leave->start_date);
        $endKh = $fmtKhmerDate($leave->end_date);
        $days = $leave->duration_days;
        $durationText = ($startKh === $endKh) ? "{$startKh} (១ ថ្ងៃ)" : "{$startKh} ដល់ {$endKh} ({$days} ថ្ងៃ)";

        // ចំណងជើង និងស្ថានភាពជាភាសាខ្មែរសុទ្ធ
        $actionConfig = match (strtolower($action)) {
            'approved' => [
                'icon' => '✅',
                'title' => 'បានអនុម័តច្បាប់ឈប់សម្រាក',
                'badge' => '✅ បានអនុម័ត',
            ],
            'rejected' => [
                'icon' => '❌',
                'title' => 'បានបដិសេធច្បាប់ឈប់សម្រាក',
                'badge' => '❌ មិនអនុម័ត',
            ],
            'updated' => [
                'icon' => '🔄',
                'title' => 'កែប្រែច្បាប់ឈប់សម្រាក',
                'badge' => $leave->status === 'Approved' ? '✅ បានអនុម័ត' : ($leave->status === 'Rejected' ? '❌ មិនអនុម័ត' : '⏳ រង់ចាំពិនិត្យ'),
            ],
            default => [
                'icon' => '📝',
                'title' => 'ពាក្យស្នើសុំច្បាប់ឈប់សម្រាកថ្មី',
                'badge' => $leave->status === 'Approved' ? '✅ បានអនុម័ត' : ($leave->status === 'Rejected' ? '❌ មិនអនុម័ត' : '⏳ រង់ចាំពិនិត្យ'),
            ],
        };

        $text = "{$actionConfig['icon']} <b>{$actionConfig['title']}</b>\n";
        $text .= "────────────────────\n";
        $text .= "👤 <b>ឈ្មោះបុគ្គលិក៖</b> <b>{$empName}</b>\n";
        $text .= "🆔 <b>អត្តលេខ៖</b> <code>{$empCode}</code>\n";
        if ($cardNum) {
            $text .= "🪪 <b>កាតបុគ្គលិក៖</b> <code>{$cardNum}</code>\n";
        }
        $text .= "🏢 <b>ផ្នែក៖</b> {$department}\n";
        $text .= "💼 <b>មុខតំណែង៖</b> {$position}\n";
        $text .= "────────────────────\n";
        $text .= "🏷️ <b>ប្រភេទច្បាប់៖</b> <b>{$leaveTypeName}</b>\n";
        $text .= "📅 <b>កាលបរិច្ឆេទឈប់៖</b> <b>{$durationText}</b>\n";
        $text .= "📊 <b>ស្ថានភាព៖</b> {$actionConfig['badge']}\n";
        if (! empty($leave->reason)) {
            $safeReason = htmlspecialchars($leave->reason, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $text .= "📝 <b>មូលហេតុ៖</b> {$safeReason}\n";
        }
        if ($leave->status === 'Approved' && $leave->approvedBy) {
            $approverName = $leave->approvedBy->name ?? 'Admin';
            $text .= "✍️ <b>អនុម័តដោយ៖</b> {$approverName}\n";
        }
        $nowKh = now()->format('d-').($khmerMonths[now()->month] ?? now()->format('M')).now()->format('-Y').' ម៉ោង '.now()->format('H:i');
        $text .= "🕒 <i>កាលបរិច្ឆេទកត់ត្រា៖ {$nowKh}</i>";

        // ស្វែងរករូបភាពកាត ឬរូបថតបុគ្គលិក (Staff Card / Photo Attachment)
        $cardImagePath = null;
        $photoDoc = $employee?->employee_documents?->where('document_type', 'photo')->first();
        if (! $photoDoc && $employee) {
            $photoDoc = $employee->employee_documents()->where('document_type', 'photo')->latest()->first();
        }

        if ($photoDoc && $photoDoc->document_path && Storage::disk('public')->exists($photoDoc->document_path)) {
            $cardImagePath = Storage::disk('public')->path($photoDoc->document_path);
        } elseif (! empty($employee?->photo) && file_exists(public_path($employee->photo))) {
            $cardImagePath = public_path($employee->photo);
        } elseif (! empty($employee?->photo) && Storage::disk('public')->exists($employee->photo)) {
            $cardImagePath = Storage::disk('public')->path($employee->photo);
        } else {
            $idDoc = $employee?->employee_documents()->whereIn('document_type', ['id_card', 'card', 'staff_card'])->latest()->first();
            if ($idDoc && $idDoc->document_path && Storage::disk('public')->exists($idDoc->document_path)) {
                $cardImagePath = Storage::disk('public')->path($idDoc->document_path);
            }
        }

        // ប្រសិនបើគ្មានឯកសារកាតទេ បង្កើតរូបកាតឌីជីថល (Digital Staff Badge) ជូន
        if (! $cardImagePath && $employee) {
            try {
                $cardImagePath = $this->generateDigitalStaffCard($employee, $activeCard);
            } catch (\Throwable $e) {
                // Ignore fallback
            }
        }

        // ប្រសិនបើមានរូបថតកាតបុគ្គលិក ផ្ញើជារូបភាព (Photo with Caption)
        if ($cardImagePath && file_exists($cardImagePath)) {
            return $this->sendPhoto($cardImagePath, $text, $targetChatId, 'HTML', $targetToken);
        }

        return $this->sendMessage($text, $targetChatId, 'HTML', $targetToken);
    }

    /**
     * Send Student tuition payment receipt alert.
     */
    public function sendPaymentAlert(StudentPayment $payment): array
    {
        if (! $this->isEnabled()) {
            return ['success' => false, 'message' => 'Telegram Bot ត្រូវបានបិទដំណើរការ។'];
        }

        $payment->load(['student.studentProfile', 'student.schoolClass', 'payment_method', 'student_invoice']);
        $student = $payment->student;
        $prof = $student?->studentProfile;
        $studentName = trim(($prof?->last_name_khmer ?? '').' '.($prof?->first_name_khmer ?? ''))
            ?: (trim(($prof?->first_name ?? '').' '.($prof?->last_name ?? '')) ?: ($student?->user?->name ?? 'Student'));

        $invoice = $payment->student_invoice;
        $balance = $invoice ? (float) $invoice->remaining_balance : 0;
        $amount = number_format((float) $payment->amount, 2);
        $method = $payment->payment_method?->name ?? 'Cash / KHQR';
        $now = now()->format('d-M-Y H:i');

        $text = "💳 <b>បង្កាន់ដៃបង់ប្រាក់ថ្លៃសិក្សា (Tuition Payment Received)</b>\n";
        $text .= '🧾 <b>បង្កាន់ដៃលេខ៖</b> #PAY-'.str_pad($payment->id, 5, '0', STR_PAD_LEFT)."\n";
        $text .= "👤 <b>សិស្ស៖</b> <b>{$studentName}</b> (".($student?->admission_number ?? 'ID').")\n";
        $text .= '📚 <b>ថ្នាក់រៀន៖</b> '.($student?->schoolClass?->name ?? '—')."\n";
        $text .= "💵 <b>ចំនួនទឹកប្រាក់បង់៖</b> <b>\${$amount}</b>\n";
        $text .= "💳 <b>វិធីសាស្រ្តទូទាត់៖</b> {$method}\n";
        $text .= "────────────────────\n";
        $text .= "📊 <b>សមតុល្យនៅសល់៖</b> \${$balance}\n";
        $text .= "🕒 <b>កាលបរិច្ឆេទ៖</b> {$now}";

        return $this->sendMessage($text);
    }

    /**
     * Send School Announcement broadcast.
     */
    public function sendBroadcast(string $title, string $message, ?string $sender = null): array
    {
        $schoolName = $this->school?->name ?? 'សាលារៀន ប្រទីបបញ្ញា';
        $now = now()->format('d-M-Y H:i');

        $text = "📢 <b>សេចក្តីជូនដំណឹងសាលា (School Announcement)</b>\n";
        $text .= "🏫 <b>{$schoolName}</b>\n\n";
        $text .= "📌 <b>ប្រធានបទ៖</b> <b>{$title}</b>\n";
        $text .= "────────────────────\n";
        $text .= "{$message}\n";
        $text .= "────────────────────\n";
        if ($sender) {
            $text .= "✍️ <i>ចេញផ្សាយដោយ៖ {$sender}</i>\n";
        }
        $text .= "🕒 <i>កាលបរិច្ឆេទ៖ {$now}</i>";

        return $this->sendMessage($text);
    }

    /**
     * Generate an official Digital Staff ID Card badge image with photo, details, and branding.
     */
    public function generateDigitalStaffCard(Employee $employee, ?EmployeeCard $card = null): ?string
    {
        if (! extension_loaded('gd') || ! function_exists('imagecreatetruecolor')) {
            return null;
        }

        $employee->loadMissing(['user', 'employee_department', 'employee_position', 'employee_documents']);

        // Check or generate active card
        if (! $card) {
            $card = $employee->activeCard ?? $employee->employeeCards?->where('status', 'active')->first();
            if (! $card) {
                try {
                    $card = EmployeeCard::getOrCreateActiveFor($employee);
                } catch (\Throwable $e) {
                    // Ignore fallback
                }
            }
        }

        Storage::disk('public')->makeDirectory('staff-cards');
        $savePath = Storage::disk('public')->path("staff-cards/card_{$employee->id}.png");

        // Format employee names
        $khName = trim(($employee->last_name_khmer ?? '').' '.($employee->first_name_khmer ?? ''));
        $ltName = trim(($employee->last_name ?? '').' '.($employee->first_name ?? ''));
        if (! empty($khName)) {
            $empName = $khName;
        } elseif (! empty($ltName)) {
            $empName = $ltName;
        } else {
            $empName = $employee->user?->name ?? 'បុគ្គលិក';
        }

        $empCode = $employee->employee_code ?? 'EMP-'.str_pad((string) $employee->id, 4, '0', STR_PAD_LEFT);
        $cardNum = $card?->card_number ?? 'CRD-'.now()->format('Y').'-'.str_pad((string) $employee->id, 5, '0', STR_PAD_LEFT);
        $department = $employee->employee_department?->name ?? 'បុគ្គលិក';
        $position = $employee->employee_position?->name ?? 'គ្រូបង្រៀន';
        $schoolName = $this->school?->name ?? 'សាលារៀន ប្រទីបបញ្ញា';

        $w = 720;
        $h = 440;
        $im = imagecreatetruecolor($w, $h);
        if (! $im) {
            return null;
        }

        if (function_exists('imageantialias')) {
            @imageantialias($im, true);
        }

        // Color palette
        $bg = imagecolorallocate($im, 248, 250, 252);
        $white = imagecolorallocate($im, 255, 255, 255);
        $headerBg = imagecolorallocate($im, 30, 58, 138); // #1e3a8a
        $accentGold = imagecolorallocate($im, 245, 158, 11); // #f59e0b
        $footerBg = imagecolorallocate($im, 15, 23, 42); // #0f172a
        $borderColor = imagecolorallocate($im, 203, 213, 225); // #cbd5e1
        $textDark = imagecolorallocate($im, 15, 23, 42);
        $textMuted = imagecolorallocate($im, 100, 116, 139);
        $goldText = imagecolorallocate($im, 251, 191, 36);
        $activeGreenBg = imagecolorallocate($im, 16, 185, 129);
        $activeGreenText = imagecolorallocate($im, 255, 255, 255);

        // Fill background
        imagefilledrectangle($im, 0, 0, $w, $h, $bg);

        // Header
        imagefilledrectangle($im, 0, 0, $w, 88, $headerBg);
        imagefilledrectangle($im, 0, 88, $w, 94, $accentGold);

        // Footer
        imagefilledrectangle($im, 0, $h - 44, $w, $h, $footerBg);

        // Outer border
        imagerectangle($im, 0, 0, $w - 1, $h - 1, $borderColor);

        $fontPath = $this->getKhmerFontPath();

        // School Logo in Header
        $logoCandidates = [
            public_path('images/school_logo.png'),
            public_path('images/logo.png'),
            public_path('images/logo_clean.png'),
        ];
        $logoLoaded = false;
        foreach ($logoCandidates as $cand) {
            if (file_exists($cand)) {
                $logoIm = @imagecreatefrompng($cand);
                if ($logoIm) {
                    $lw = imagesx($logoIm);
                    $lh = imagesy($logoIm);
                    imagecopyresampled($im, $logoIm, 22, 14, 0, 0, 60, 60, $lw, $lh);
                    imagedestroy($logoIm);
                    $logoLoaded = true;
                    break;
                }
            }
        }

        $headerTextX = $logoLoaded ? 95 : 30;
        $this->drawText($im, 17, $headerTextX, 42, $white, $schoolName, $fontPath);
        $this->drawText($im, 11, $headerTextX, 68, $goldText, 'កាតសម្គាល់ខ្លួនបុគ្គលិកផ្លូវការ', $fontPath);

        // Photo Box (Left)
        $photoX = 35;
        $photoY = 120;
        $photoW = 180;
        $photoH = 240;

        // Photo card background & border
        imagefilledrectangle($im, $photoX, $photoY, $photoX + $photoW, $photoY + $photoH, $white);
        imagerectangle($im, $photoX, $photoY, $photoX + $photoW, $photoY + $photoH, $borderColor);

        // Check if employee has photo
        $empPhotoPath = null;
        $photoDoc = $employee->employee_documents?->where('document_type', 'photo')->first();
        if (! $photoDoc) {
            $photoDoc = $employee->employee_documents()->where('document_type', 'photo')->latest()->first();
        }
        if ($photoDoc && $photoDoc->document_path && Storage::disk('public')->exists($photoDoc->document_path)) {
            $empPhotoPath = Storage::disk('public')->path($photoDoc->document_path);
        } elseif (! empty($employee->photo) && file_exists(public_path($employee->photo))) {
            $empPhotoPath = public_path($employee->photo);
        } elseif (! empty($employee->photo) && Storage::disk('public')->exists($employee->photo)) {
            $empPhotoPath = Storage::disk('public')->path($employee->photo);
        }

        $photoDrawn = false;
        if ($empPhotoPath && file_exists($empPhotoPath)) {
            $empIm = $this->loadEmployeePhotoGd($empPhotoPath);
            if ($empIm) {
                $srcW = imagesx($empIm);
                $srcH = imagesy($empIm);
                if ($srcW > 0 && $srcH > 0) {
                    $targetInnerW = $photoW - 10;
                    $targetInnerH = $photoH - 10;
                    $srcRatio = $srcW / $srcH;
                    $targetRatio = $targetInnerW / $targetInnerH;
                    if ($srcRatio > $targetRatio) {
                        $newSrcW = (int) ($srcH * $targetRatio);
                        $srcX = (int) (($srcW - $newSrcW) / 2);
                        $srcY = 0;
                        $srcW = $newSrcW;
                    } else {
                        $newSrcH = (int) ($srcW / $targetRatio);
                        $srcY = (int) (($srcH - $newSrcH) / 2);
                        $srcX = 0;
                        $srcH = $newSrcH;
                    }
                    imagecopyresampled($im, $empIm, $photoX + 5, $photoY + 5, $srcX, $srcY, $targetInnerW, $targetInnerH, $srcW, $srcH);
                    $photoDrawn = true;
                }
                imagedestroy($empIm);
            }
        }

        if (! $photoDrawn) {
            // Draw neat placeholder avatar with initials
            $avatarCircleColor = imagecolorallocate($im, 226, 232, 240);
            imagefilledellipse($im, $photoX + (int) ($photoW / 2), $photoY + (int) ($photoH / 2) - 10, 110, 110, $avatarCircleColor);
            $initials = mb_substr($empName, 0, 2, 'UTF-8');
            $this->drawText($im, 24, $photoX + (int) ($photoW / 2) - 30, $photoY + (int) ($photoH / 2) + 2, $headerBg, $initials, $fontPath);
        }

        // Details on Right side
        $infoX = 245;
        $infoY = 150;

        // Name
        $this->drawText($im, 10, $infoX, $infoY, $textMuted, 'ឈ្មោះបុគ្គលិក', $fontPath);
        $this->drawText($im, 17, $infoX, $infoY + 24, $textDark, $empName, $fontPath);

        // Code & Card No
        $currY = $infoY + 62;
        $this->drawText($im, 10, $infoX, $currY, $textMuted, 'អត្តលេខ', $fontPath);
        $this->drawText($im, 13, $infoX, $currY + 22, $textDark, $empCode, $fontPath);

        $this->drawText($im, 10, $infoX + 220, $currY, $textMuted, 'លេខកាត', $fontPath);
        $this->drawText($im, 13, $infoX + 220, $currY + 22, $headerBg, $cardNum, $fontPath);

        // Position & Department
        $currY += 56;
        $this->drawText($im, 10, $infoX, $currY, $textMuted, 'មុខតំណែង', $fontPath);
        $this->drawText($im, 13, $infoX, $currY + 22, $textDark, $position, $fontPath);

        $this->drawText($im, 10, $infoX + 220, $currY, $textMuted, 'ដេប៉ាតឺម៉ង់', $fontPath);
        $this->drawText($im, 13, $infoX + 220, $currY + 22, $textDark, $department, $fontPath);

        // Status Badge Pill (Green)
        $badgeY = $currY + 52;
        imagefilledrectangle($im, $infoX, $badgeY, $infoX + 175, $badgeY + 28, $activeGreenBg);
        $this->drawText($im, 10, $infoX + 14, $badgeY + 19, $activeGreenText, '✓ សកម្ម (កាតដំណើរការ)', $fontPath);

        // Footer Text
        $this->drawText($im, 9.5, 25, $h - 17, $white, 'ប្រព័ន្ធគ្រប់គ្រងសាលារៀន • សុពលភាព ២០២៦-២០២៧', $fontPath);
        $this->drawText($im, 9.5, $w - 180, $h - 17, $goldText, 'កាតបុគ្គលិកផ្លូវការ', $fontPath);

        imagepng($im, $savePath, 8);
        imagedestroy($im);

        return $savePath;
    }

    /**
     * Resolve best available Khmer Unicode font path on host system.
     */
    protected function getKhmerFontPath(): ?string
    {
        $candidateFonts = [
            'C:/Windows/Fonts/KhmerOSbattambang.ttf',
            'C:/Windows/Fonts/KhmerOS.ttf',
            'C:/Windows/Fonts/KhmerUI.ttf',
            'C:/Windows/Fonts/LeelawUI.ttf',
            'C:/Windows/Fonts/arial.ttf',
        ];

        foreach ($candidateFonts as $f) {
            if (file_exists($f)) {
                return $f;
            }
        }

        return null;
    }

    /**
     * Helper to load employee photo image resource using GD.
     */
    protected function loadEmployeePhotoGd(string $path)
    {
        if (! file_exists($path)) {
            return null;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        try {
            if (in_array($ext, ['jpg', 'jpeg'])) {
                return @imagecreatefromjpeg($path);
            } elseif ($ext === 'png') {
                return @imagecreatefrompng($path);
            } elseif ($ext === 'webp' && function_exists('imagecreatefromwebp')) {
                return @imagecreatefromwebp($path);
            }
        } catch (\Throwable $e) {
            return null;
        }

        return null;
    }

    /**
     * Robust text drawing method supporting TrueType Unicode and ASCII fallbacks.
     */
    protected function drawText(
        $im,
        float $size,
        int $x,
        int $y,
        int $color,
        string $text,
        ?string $fontPath = null
    ): void {
        if ($fontPath && file_exists($fontPath) && function_exists('imagettftext')) {
            try {
                @imagettftext($im, $size, 0, $x, $y, $color, $fontPath, $text);

                return;
            } catch (\Throwable $e) {
                // fallback below
            }
        }

        if (function_exists('imagestring')) {
            $ascii = preg_replace('/[^\x20-\x7E]/', '', $text);
            if (! empty($ascii)) {
                @imagestring($im, 4, $x, $y - (int) $size, $ascii, $color);
            }
        }
    }
}
