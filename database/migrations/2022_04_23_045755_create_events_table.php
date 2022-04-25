<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('event_id')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('location')->nullable();
            $table->string('creator_email')->nullable();
            $table->string('creator_name')->nullable();
            $table->string('organizer_email')->nullable();
            $table->string('organizer_name')->nullable();
            $table->string('event_start')->nullable();
            $table->string('event_end')->nullable();
            $table->string('recurring')->nullable();
            $table->string('attendees_names')->nullable();
            $table->string('attendees_emails')->nullable();
            $table->string('meeting_link')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
