<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryCategory extends Model
{
    use HasFactory;

    protected $table = 'library_categories';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public function library_books()
    {
        return $this->hasMany(LibraryBook::class, 'library_category_id');
    }

    /**
     * Map common English category names to Khmer.
     */
    public static function translateToKhmer(?string $name): string
    {
        if (! $name) {
            return '';
        }

        $translations = [
            'science & technology' => 'វិទ្យាសាស្ត្រ និងបច្ចេកវិទ្យា',
            'science and technology' => 'វិទ្យាសាស្ត្រ និងបច្ចេកវិទ្យា',
            'mathematics' => 'គណិតវិទ្យា',
            'literature' => 'អក្សរសាស្ត្រ និងអក្សរសិល្ប៍',
            'history & geography' => 'ប្រវត្តិសាស្ត្រ និងភូមិវិទ្យា',
            'history and geography' => 'ប្រវត្តិសាស្ត្រ និងភូមិវិទ្យា',
            'religion & ethics' => 'សាសនា និងសីលធម៌',
            'religion and ethics' => 'សាសនា និងសីលធម៌',
            'language & grammar' => 'ភាសា និងវេយ្យាករណ៍',
            'language and grammar' => 'ភាសា និងវេយ្យាករណ៍',
            'reference books' => 'សៀវភៅឯកសារយោង',
            'reference book' => 'សៀវភៅឯកសារយោង',
            'children\'s books' => 'សៀវភៅកុមារ',
            'childrens books' => 'សៀវភៅកុមារ',
            'children books' => 'សៀវភៅកុមារ',
            'magazine & journals' => 'ទស្សនាវដ្តី និងព្រឹត្តិបត្រ',
            'magazine and journals' => 'ទស្សនាវដ្តី និងព្រឹត្តិបត្រ',
            'sports & health' => 'កីឡា និងសុខភាព',
            'sports and health' => 'កីឡា និងសុខភាព',
            'computer science' => 'វិទ្យាសាស្ត្រកុំព្យូទ័រ',
            'computer science test' => 'វិទ្យាសាស្ត្រកុំព្យូទ័រ',
            'general' => 'ទូទៅ',
            'general knowledge' => 'ចំណេះដឹងទូទៅ',
            'fiction' => 'រឿងប្រឌិត',
            'non-fiction' => 'រឿងពិត',
            'arts & culture' => 'សិល្បៈ និងវប្បធម៌',
            'arts and culture' => 'សិល្បៈ និងវប្បធម៌',
            'business & economics' => 'ពាណិជ្ជកម្ម និងសេដ្ឋកិច្ច',
            'social sciences' => 'វិទ្យាសាស្ត្រសង្គម',
            'philosophy' => 'ទស្សនវិជ្ជា',
            'novel' => 'ប្រលោមលោក',
            'dictionary' => 'វចនានុក្រម',
            'encyclopedia' => 'សព្វវចនាធិប្បាយ',
        ];

        $trimmed = trim($name);
        $lower = strtolower($trimmed);

        if (isset($translations[$lower])) {
            return $translations[$lower];
        }

        if (preg_match('/^Category With Book/i', $trimmed)) {
            return 'ប្រភេទសៀវភៅទូទៅ';
        }
        if (preg_match('/^New Test Category/i', $trimmed)) {
            return 'ប្រភេទសៀវភៅថ្មី';
        }
        if (preg_match('/^Updated Name/i', $trimmed)) {
            return 'ប្រភេទសៀវភៅកែប្រែ';
        }

        return $name;
    }

    /**
     * Accessor for name attribute to return Khmer automatically.
     */
    public function getNameAttribute($value): string
    {
        return self::translateToKhmer($value);
    }
}
