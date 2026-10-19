<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'slug',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function role_permissions()
    {
        return $this->hasMany(RolePermission::class, 'permission_id');
    }

    /**
     * Translate module name to Khmer.
     */
    public static function translateModule(string $module): string
    {
        $translations = [
            'assignments' => 'កិច្ចការ',
            'attendance' => 'វត្តមានសិស្ស',
            'staff-attendance' => 'វត្តមានបុគ្គលិក & ស្កេន',
            'staff-leaves' => 'ច្បាប់ឈប់សម្រាកបុគ្គលិក',
            'employees' => 'បុគ្គលិក',
            'enrollments' => 'ការចុះឈ្មោះ & ផ្ទេរ',
            'exams' => 'ការប្រឡង & ពិន្ទុ',
            'finance' => 'ហិរញ្ញវត្ថុ & ចំណាយ',
            'students' => 'សិស្ស',
            'guardians' => 'អាណាព្យាបាល',
            'library' => 'បណ្ណាល័យ',
            'reports' => 'របាយការណ៍',
            'settings' => 'ការកំណត់ទូទៅ',
            'roles' => 'តួនាទី និងសិទ្ធិ',
            'classes' => 'ថ្នាក់រៀន',
            'subjects' => 'មុខវិជ្ជា',
            'timetables' => 'កាលវិភាគ',
            'academic-years' => 'ឆ្នាំសិក្សា',
            'payrolls' => 'បើកប្រាក់ខែ (Payroll)',
            'departments' => 'ដេប៉ាតឺម៉ង់ និងតួនាទី',
            'bulk-data' => 'នាំចូល/នាំចេញទិន្នន័យ (Excel)',
            'print' => 'បោះពុម្ពឯកសារ & កាត',
            'system' => 'ប្រព័ន្ធ & សុវត្ថិភាព',
        ];

        return $translations[strtolower($module)] ?? ucwords(str_replace(['-', '_'], ' ', $module));
    }

    /**
     * Get display name in Khmer.
     */
    public function getDisplayNameAttribute(): string
    {
        $translations = [
            // Assignments
            'assignments.view' => 'មើលកិច្ចការ',
            'assignments.create' => 'បង្កើតកិច្ចការ',
            'assignments.edit' => 'កែប្រែកិច្ចការ',
            'assignments.delete' => 'លុបកិច្ចការ',

            // Student Attendance
            'attendance.view' => 'មើលវត្តមានសិស្ស',
            'attendance.create' => 'កត់ត្រាវត្តមានសិស្ស',
            'attendance.edit' => 'កែប្រែវត្តមានសិស្ស',
            'attendance.delete' => 'លុបវត្តមានសិស្ស',

            // Staff Attendance
            'staff-attendance.view' => 'មើលវត្តមានបុគ្គលិក',
            'staff-attendance.create' => 'កត់ត្រាវត្តមានបុគ្គលិក',
            'staff-attendance.edit' => 'កែប្រែវត្តមានបុគ្គលិក',
            'staff-attendance.delete' => 'លុបវត្តមានបុគ្គលិក',
            'staff-attendance.scan' => 'ស្កេនវត្តមាន QR Code',
            'staff-attendance.cards' => 'បង្កើត និងគ្រប់គ្រងកាត QR',
            'staff-attendance.shifts' => 'កំណត់វេនម៉ោងស្កេនវត្តមាន',

            // Staff Leaves
            'staff-leaves.view' => 'មើលបញ្ជីច្បាប់ឈប់សម្រាក',
            'staff-leaves.create' => 'ស្នើសុំច្បាប់ឈប់សម្រាក',
            'staff-leaves.edit' => 'កែប្រែពាក្យសុំច្បាប់',
            'staff-leaves.delete' => 'លុបពាក្យសុំច្បាប់',
            'staff-leaves.approve' => 'អនុម័ត/បដិសេធ ច្បាប់ឈប់សម្រាក',

            // Employees
            'employees.view' => 'មើលបុគ្គលិក',
            'employees.create' => 'បន្ថែមបុគ្គលិក',
            'employees.edit' => 'កែប្រែបុគ្គលិក',
            'employees.delete' => 'លុបបុគ្គលិក',

            // Enrollments
            'enrollments.view' => 'មើលការចុះឈ្មោះ',
            'enrollments.create' => 'ចុះឈ្មោះសិស្សថ្មី',
            'enrollments.edit' => 'កែប្រែការចុះឈ្មោះ',
            'enrollments.delete' => 'លុបការចុះឈ្មោះ',

            // Exams
            'exams.view' => 'មើលការប្រឡង',
            'exams.create' => 'បង្កើតការប្រឡង',
            'exams.edit' => 'កែប្រែការប្រឡង',
            'exams.delete' => 'លុបការប្រឡង',
            'exams.scores' => 'បញ្ចូលពិន្ទុប្រឡង',
            'exams.calculate' => 'គណនា និងកាត់ចំណាត់ថ្នាក់',
            'exams.report-card' => 'បោះពុម្ពសៀវភៅតាមដាន/លទ្ធផល',

            // Finance
            'finance.view' => 'មើលទិន្នន័យហិរញ្ញវត្ថុ',
            'finance.create' => 'បង្កើតវិក្កយបត្រ/ចំណាយ',
            'finance.edit' => 'កែប្រែហិរញ្ញវត្ថុ',
            'finance.delete' => 'លុបទិន្នន័យហិរញ្ញវត្ថុ',
            'finance.payments' => 'ទទួលការបង់ប្រាក់ពីសិស្ស',
            'finance.receipts' => 'បោះពុម្ពវិក្កយបត្រ & បង្កាន់ដៃ',
            'finance.invoices.cancel' => 'លុបចោលវិក្កយបត្រ (Cancel)',

            // Payrolls
            'payrolls.view' => 'មើលតារាងប្រាក់ខែ',
            'payrolls.create' => 'បង្កើតតារាងប្រាក់ខែ',
            'payrolls.edit' => 'កែប្រែប្រាក់ខែ',
            'payrolls.delete' => 'លុបតារាងប្រាក់ខែ',
            'payrolls.submit' => 'ដាក់ស្នើប្រាក់ខែ (Submit)',
            'payrolls.approve' => 'អនុម័តប្រាក់ខែ (Approve)',
            'payrolls.pay' => 'ទូទាត់បើកប្រាក់ខែ (Pay)',
            'payrolls.payslip' => 'បោះពុម្ពប័ណ្ណបើកប្រាក់ខែ (Payslip)',

            // Students
            'students.view' => 'មើលបញ្ជីសិស្ស',
            'students.create' => 'បន្ថែមសិស្ស',
            'students.edit' => 'កែប្រែព័ត៌មានសិស្ស',
            'students.delete' => 'លុបសិស្ស',

            // Guardians
            'guardians.view' => 'មើលអាណាព្យាបាល',
            'guardians.create' => 'បន្ថែមអាណាព្យាបាល',
            'guardians.edit' => 'កែប្រែព័ត៌មានអាណាព្យាបាល',
            'guardians.delete' => 'លុបអាណាព្យាបាល',

            // Library
            'library.view' => 'មើលបណ្ណាល័យ',
            'library.create' => 'បន្ថែមសៀវភៅ',
            'library.edit' => 'កែប្រែទិន្នន័យបណ្ណាល័យ',
            'library.delete' => 'លុបសៀវភៅ',

            // Reports
            'reports.view' => 'មើលរបាយការណ៍',
            'reports.create' => 'បង្កើតរបាយការណ៍',
            'reports.edit' => 'កែប្រែរបាយការណ៍',
            'reports.delete' => 'លុបរបាយការណ៍',

            // Settings
            'settings.view' => 'មើលការកំណត់',
            'settings.create' => 'បង្កើតការកំណត់',
            'settings.edit' => 'កែប្រែការកំណត់',
            'settings.delete' => 'លុបការកំណត់',

            // Roles
            'roles.view' => 'មើលតួនាទី',
            'roles.create' => 'បង្កើតតួនាទី',
            'roles.edit' => 'កែប្រែតួនាទី',
            'roles.delete' => 'លុបតួនាទី',

            // Classes, Subjects, Timetables, Academic Years, Departments
            'classes.view' => 'មើលថ្នាក់រៀន',
            'classes.create' => 'បង្កើតថ្នាក់រៀន',
            'classes.edit' => 'កែប្រែថ្នាក់រៀន',
            'classes.delete' => 'លុបថ្នាក់រៀន',

            'subjects.view' => 'មើលមុខវិជ្ជា',
            'subjects.create' => 'បង្កើតមុខវិជ្ជា',
            'subjects.edit' => 'កែប្រែមុខវិជ្ជា',
            'subjects.delete' => 'លុបមុខវិជ្ជា',

            'timetables.view' => 'មើលកាលវិភាគ',
            'timetables.create' => 'បង្កើតកាលវិភាគ',
            'timetables.edit' => 'កែប្រែកាលវិភាគ',
            'timetables.delete' => 'លុបកាលវិភាគ',

            'academic-years.view' => 'មើលឆ្នាំសិក្សា',
            'academic-years.create' => 'បង្កើតឆ្នាំសិក្សា',
            'academic-years.edit' => 'កែប្រែឆ្នាំសិក្សា',
            'academic-years.delete' => 'លុបឆ្នាំសិក្សា',

            'departments.view' => 'មើលដេប៉ាតឺម៉ង់ & មុខតំណែង',
            'departments.create' => 'បង្កើតដេប៉ាតឺម៉ង់ & មុខតំណែង',
            'departments.edit' => 'កែប្រែដេប៉ាតឺម៉ង់ & មុខតំណែង',
            'departments.delete' => 'លុបដេប៉ាតឺម៉ង់ & មុខតំណែង',

            // Bulk Data
            'bulk-data.view' => 'ចូលមើលទំព័រទិន្នន័យ Excel',
            'bulk-data.import' => 'នាំចូលទិន្នន័យពី Excel (Import)',
            'bulk-data.export' => 'ទាញចេញទិន្នន័យជា Excel (Export)',

            // Printable Documents
            'print.id-cards' => 'បោះពុម្ពកាតសិស្ស & កាតបុគ្គលិក',
            'print.certificates' => 'បោះពុម្ពលិខិតបញ្ជាក់ការសិក្សា',

            // System & Security
            'system.backup' => 'បង្កើត & ទាញយក Backup ទិន្នន័យ',
            'system.telegram' => 'ផ្ញើសារជូនដំណឹង Telegram',
        ];

        if (isset($translations[$this->slug])) {
            return $translations[$this->slug];
        }

        // Fallback for custom or composite slugs
        $parts = explode('.', $this->slug);
        $mod = $parts[0] ?? '';
        $act = $parts[1] ?? '';

        $actions = [
            'view' => 'មើល',
            'create' => 'បង្កើត',
            'edit' => 'កែប្រែ',
            'delete' => 'លុប',
            'export' => 'ទាញយក',
            'import' => 'នាំចូល',
            'print' => 'បោះពុម្ព',
            'manage' => 'គ្រប់គ្រង',
            'approve' => 'អនុម័ត',
            'reject' => 'បដិសេធ',
            'scan' => 'ស្កេន',
            'cards' => 'កាត',
            'shifts' => 'វេនម៉ោង',
            'scores' => 'បញ្ចូលពិន្ទុ',
            'calculate' => 'គណនា',
            'payments' => 'បង់ប្រាក់',
            'receipts' => 'បង្កាន់ដៃ',
            'submit' => 'ដាក់ស្នើ',
            'pay' => 'ទូទាត់',
            'payslip' => 'ប័ណ្ណប្រាក់ខែ',
            'backup' => 'Backup',
            'telegram' => 'Telegram',
        ];

        $actKh = $actions[$act] ?? ucfirst($act);
        $modKh = static::translateModule($mod);

        return trim("{$actKh} {$modKh}");
    }
}
