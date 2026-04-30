<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // individual, team, family
            $table->string('subject_rf');
            $table->string('participation_level'); // municipal, regional
            $table->string('municipality')->nullable();
            $table->date('event_date')->nullable();
            $table->string('discipline')->nullable();
            $table->string('email');
            $table->json('data'); // Full form data as JSON
            $table->string('status')->default('pending');
            $table->string('confirmation_token')->nullable();
            $table->boolean('sent_to_crm')->default(false);
            $table->timestamps();
        });

        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('patronymic')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->string('participant_status')->nullable(); // rural, apk_worker, apk_student
            $table->string('status_detail')->nullable(); // address/workplace/school
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('clothing_size')->nullable();
            $table->boolean('is_minor')->default(false);
            $table->boolean('is_captain')->default(false);
            $table->integer('age')->nullable();
            $table->json('extra_data')->nullable();
            $table->timestamps();
        });

        Schema::create('legal_representatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->onDelete('cascade');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('patronymic')->nullable();
            $table->string('status'); // parent, adopter, guardian, trustee
            $table->string('document')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_representatives');
        Schema::dropIfExists('participants');
        Schema::dropIfExists('applications');
    }
};
