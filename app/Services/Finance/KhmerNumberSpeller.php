<?php

namespace App\Services\Finance;

class KhmerNumberSpeller
{
    private static array $digits = [
        0 => 'សូន្យ',
        1 => 'មួយ',
        2 => 'ពីរ',
        3 => 'បី',
        4 => 'បួន',
        5 => 'ប្រាំ',
        6 => 'ប្រាំមួយ',
        7 => 'ប្រាំពីរ',
        8 => 'ប្រាំបី',
        9 => 'ប្រាំបួន',
    ];

    private static array $tens = [
        1 => 'ដប់',
        2 => 'ម្ភៃ',
        3 => 'សាមសិប',
        4 => 'សែសិប',
        5 => 'ហាសិប',
        6 => 'ហុកសិប',
        7 => 'ចិតសិប',
        8 => 'ប៉ែតសិប',
        9 => 'កៅសិប',
    ];

    /**
     * Convert an integer (up to 999,999,999,999) into Khmer text.
     */
    public static function spell(int $number): string
    {
        if ($number === 0) {
            return self::$digits[0];
        }

        if ($number < 0) {
            return 'ដក '.self::spell(abs($number));
        }

        $parts = [];

        // Billions (ពាន់លាន)
        if ($number >= 1_000_000_000) {
            $billions = intdiv($number, 1_000_000_000);
            $parts[] = self::spellThreeDigits($billions).'ពាន់លាន';
            $number %= 1_000_000_000;
        }

        // Millions (លាន)
        if ($number >= 1_000_000) {
            $millions = intdiv($number, 1_000_000);
            $parts[] = self::spellThreeDigits($millions).'លាន';
            $number %= 1_000_000;
        }

        // Hundred Thousands (សែន)
        if ($number >= 100_000) {
            $hundredThousands = intdiv($number, 100_000);
            $parts[] = self::$digits[$hundredThousands].'សែន';
            $number %= 100_000;
        }

        // Ten Thousands (ម៉ឺន)
        if ($number >= 10_000) {
            $tenThousands = intdiv($number, 10_000);
            $parts[] = self::$digits[$tenThousands].'ម៉ឺន';
            $number %= 10_000;
        }

        // Thousands (ពាន់)
        if ($number >= 1_000) {
            $thousands = intdiv($number, 1_000);
            $parts[] = self::$digits[$thousands].'ពាន់';
            $number %= 1_000;
        }

        // Hundreds (រយ)
        if ($number >= 100) {
            $hundreds = intdiv($number, 100);
            $parts[] = self::$digits[$hundreds].'រយ';
            $number %= 100;
        }

        // Tens and Units
        if ($number > 0) {
            $parts[] = self::spellTwoDigits($number);
        }

        return implode('', $parts);
    }

    /**
     * Spell 2 digits (1 - 99).
     */
    private static function spellTwoDigits(int $number): string
    {
        if ($number <= 0) {
            return '';
        }

        if ($number < 10) {
            return self::$digits[$number];
        }

        $ten = intdiv($number, 10);
        $unit = $number % 10;

        if ($unit === 0) {
            return self::$tens[$ten];
        }

        return self::$tens[$ten].self::$digits[$unit];
    }

    /**
     * Spell 3 digits (1 - 999).
     */
    private static function spellThreeDigits(int $number): string
    {
        $parts = [];
        if ($number >= 100) {
            $hundreds = intdiv($number, 100);
            $parts[] = self::$digits[$hundreds].'រយ';
            $number %= 100;
        }

        if ($number > 0) {
            $parts[] = self::spellTwoDigits($number);
        }

        return implode('', $parts);
    }

    /**
     * Convert currency amount (e.g. 50.00) into Khmer spoken money format.
     *
     * @param  string  $currency  'USD' or 'KHR'
     */
    public static function spellCurrency(float $amount, string $currency = 'USD'): string
    {
        $amount = round($amount, 2);
        $integerPart = (int) floor($amount);
        $cents = (int) round(($amount - $integerPart) * 100);

        if ($currency === 'KHR') {
            $words = self::spell($integerPart).'រៀលគត់';

            return $words;
        }

        // Default: USD
        $dollarWords = self::spell($integerPart).'ដុល្លារអាមេរិក';

        if ($cents > 0) {
            return $dollarWords.' និង'.self::spell($cents).'សេន';
        }

        return $dollarWords.'គត់';
    }
}
