<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. salary_structures
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();
            $table->decimal('base_salary', 12, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });

        // 2. salary_components
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_structure_id')
                ->nullable()
                ->constrained('salary_structures')
                ->onDelete('cascade');
            $table->string('name', 150);
            $table->string('code', 50)->nullable();
            $table->string('type', 20); // earning, deduction
            $table->string('calculation_type', 20)->default('fixed'); // fixed, percentage
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->decimal('percentage', 5, 2)->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_taxable')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index('salary_structure_id');
            $table->index('type');
            $table->index('is_active');
        });

        // 3. employee_contracts
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('restrict');
            $table->foreignId('salary_structure_id')
                ->nullable()
                ->constrained('salary_structures')
                ->onDelete('set null');
            $table->string('contract_number', 50)->unique();
            $table->string('contract_type', 50); // permanent, fixed_term, probation, part_time, intern
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('salary', 12, 2)->nullable()->default(0.00);
            $table->decimal('work_hours_per_week', 5, 2)->nullable()->default(40.00);
            $table->string('status', 30)->default('active'); // draft, active, expired, terminated, renewed
            $table->string('document_path', 255)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamps();

            $table->index('employee_id');
            $table->index('salary_structure_id');
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });

        // 4. payrolls
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('restrict');
            $table->foreignId('salary_structure_id')
                ->nullable()
                ->constrained('salary_structures')
                ->onDelete('set null');
            $table->string('payroll_number', 50)->unique();
            $table->unsignedTinyInteger('month'); // 1-12
            $table->unsignedSmallInteger('year');
            $table->date('pay_date')->nullable();
            $table->decimal('basic_salary', 12, 2)->default(0.00);
            $table->decimal('total_earnings', 12, 2)->default(0.00);
            $table->decimal('total_deductions', 12, 2)->default(0.00);
            $table->decimal('net_salary', 12, 2)->default(0.00);
            $table->string('status', 30)->default('draft'); // draft, pending, approved, paid, cancelled
            $table->string('payment_method', 50)->nullable(); // bank_transfer, cash, cheque
            $table->string('payment_reference', 100)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('paid_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');
            $table->timestamps();

            $table->unique(['employee_id', 'month', 'year'], 'unique_employee_payroll_month');
            $table->index('status');
            $table->index(['month', 'year']);
            $table->index('pay_date');
        });

        // 5. payroll_details
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')
                ->constrained('payrolls')
                ->onDelete('cascade');
            $table->foreignId('salary_component_id')
                ->nullable()
                ->constrained('salary_components')
                ->onDelete('set null');
            $table->string('component_name', 150);
            $table->string('component_type', 20); // earning, deduction
            $table->string('calculation_type', 20)->default('fixed'); // fixed, percentage
            $table->decimal('rate', 12, 2)->nullable();
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('payroll_id');
            $table->index('salary_component_id');
            $table->index('component_type');
        });

        // 6. payroll_deductions
        Schema::create('payroll_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')
                ->constrained('payrolls')
                ->onDelete('cascade');
            $table->string('deduction_type', 50); // unpaid_leave, advance_repayment, late_penalty, fine, tax_adjustment, other
            $table->string('description', 255);
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->timestamps();

            $table->index('payroll_id');
            $table->index('deduction_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_deductions');
        Schema::dropIfExists('payroll_details');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('employee_contracts');
        Schema::dropIfExists('salary_components');
        Schema::dropIfExists('salary_structures');
    }
};
