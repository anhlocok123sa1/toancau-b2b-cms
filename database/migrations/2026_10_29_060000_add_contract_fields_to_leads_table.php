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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('contract_number')->nullable()->after('closed_reason');
            $table->decimal('contract_value', 15, 2)->nullable()->after('contract_number');
            $table->string('contract_term')->nullable()->after('contract_value');
            $table->string('contract_payment_terms')->nullable()->after('contract_term');
            $table->date('contract_signed_at')->nullable()->after('contract_payment_terms');
            $table->text('contract_notes')->nullable()->after('contract_signed_at');
            $table->string('contract_attachment_path')->nullable()->after('contract_notes');
            $table->string('contract_attachment_name')->nullable()->after('contract_attachment_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'contract_number',
                'contract_value',
                'contract_term',
                'contract_payment_terms',
                'contract_signed_at',
                'contract_notes',
                'contract_attachment_path',
                'contract_attachment_name',
            ]);
        });
    }
};
