<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loan_applications', function (Blueprint $t) {
            $t->id();
            $t->string('applicant_full_name');
            $t->string('email');
            $t->string('phone');
            $t->date('date_of_birth');
            $t->enum('vehicle_type', ['car','ute','truck','van','suv']);
            $t->string('vehicle_make');
            $t->string('vehicle_model');
            $t->decimal('purchase_price', 12, 2)->default(0);
            $t->decimal('deposit_amount', 12, 2)->default(0);
            $t->unsignedSmallInteger('term_months');
            $t->enum('status', ['submitted','in_review','approved','declined'])->default('submitted');
            $t->boolean('consent_to_credit_check');
            $t->timestamp('submitted_at')->useCurrent();
            $t->timestamps();
            $t->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_applications');
    }
};
