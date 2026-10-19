<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add all columns to the student_notes table.
     *
     * Core fields  : student_id, note
     * Extended fields: note_type, note_date, visibility,
     *                  tags, is_important, follow_up_required
     */
    public function up(): void
    {
        Schema::table('student_notes', function (Blueprint $table) {

            // ── Core ──────────────────────────────────────────────
            if (! Schema::hasColumn('student_notes', 'student_id')) {
                $table->unsignedBigInteger('student_id')->nullable()->after('id');
                $table->foreign('student_id')
                    ->references('id')->on('students')
                    ->onDelete('cascade');
            }

            if (! Schema::hasColumn('student_notes', 'note')) {
                $table->text('note')->nullable()->after('student_id');
            }

            // ── Extended ──────────────────────────────────────────
            if (! Schema::hasColumn('student_notes', 'note_type')) {
                $table->string('note_type', 50)->nullable()->after('note');
            }

            if (! Schema::hasColumn('student_notes', 'note_date')) {
                $table->date('note_date')->nullable()->after('note_type');
            }

            if (! Schema::hasColumn('student_notes', 'visibility')) {
                $table->string('visibility', 30)->default('private')->after('note_date');
            }

            if (! Schema::hasColumn('student_notes', 'tags')) {
                $table->string('tags', 500)->nullable()->after('visibility');
            }

            if (! Schema::hasColumn('student_notes', 'is_important')) {
                $table->boolean('is_important')->default(false)->after('tags');
            }

            if (! Schema::hasColumn('student_notes', 'follow_up_required')) {
                $table->boolean('follow_up_required')->default(false)->after('is_important');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_notes', function (Blueprint $table) {
            // Drop foreign key first
            if (Schema::hasColumn('student_notes', 'student_id')) {
                $table->dropForeign(['student_id']);
            }

            $table->dropColumnIfExists([
                'student_id',
                'note',
                'note_type',
                'note_date',
                'visibility',
                'tags',
                'is_important',
                'follow_up_required',
            ]);
        });
    }
};
