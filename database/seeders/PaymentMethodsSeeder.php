<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['name' => 'Cash',            'description' => 'Payment made in physical cash at the school counter'],
            ['name' => 'Bank Transfer',   'description' => 'Direct transfer to the school bank account'],
            ['name' => 'Online Payment',  'description' => 'Payment made through the school online portal'],
            ['name' => 'Cheque',          'description' => 'Payment by bank cheque made to the school'],
            ['name' => 'Mobile Banking',  'description' => 'Payment via mobile banking app (e.g. bKash, Nagad)'],
            ['name' => 'Card Payment',    'description' => 'Payment via debit or credit card at POS terminal'],
            ['name' => 'Scholarship',     'description' => 'Fee waived or covered by scholarship grant'],
        ];

        foreach ($methods as $method) {
            DB::table('payment_methods')->updateOrInsert(
                ['name' => $method['name']],
                array_merge($method, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('Payment methods seeded ('.count($methods).' records).');
    }
}
