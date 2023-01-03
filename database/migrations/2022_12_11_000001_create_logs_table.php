<?php

use dnj\UserLogger\ModelHelpers;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    use ModelHelpers;

    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('event', 128);
            $table->foreignId('user_id')->nullable();
            $table->nullableMorphs('subject', 'subject');
            $table->timestamp('created_at')->nullable();
            $table->json('properties')->nullable();
            $table->string('ip', 39)->nullable()->index();

            $userTable = $this->getUserTable();
            if ($userTable) {
                $table->foreign('user_id')
                    ->references('id')
                    ->on($userTable);
            } else {
                $table->index('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
