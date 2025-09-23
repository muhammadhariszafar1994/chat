<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Theme name (e.g. "Default", "Dark")

            // Chat Colors
            $table->string('chat_header_bg_color')->nullable();
            $table->string('chat_body_bg_color')->nullable();
            $table->string('chat_bot_bg_color')->nullable();
            $table->string('chat_user_bg_color')->nullable();

            // Text Colors
            $table->string('chat_header_text_color')->nullable();
            $table->string('chat_bot_text_color')->nullable();
            $table->string('chat_user_text_color')->nullable();

            // Buttons
            $table->string('chat_button_bg_color')->nullable();
            $table->string('chat_button_image')->nullable();

            // Toggle
            $table->string('chat_toggle_bg_color')->nullable();
            $table->string('chat_toggle_image')->nullable();

            // Fonts
            $table->string('chat_header_font_family')->nullable();
            $table->string('chat_header_font_size')->nullable();
            $table->string('chat_message_font_family')->nullable();
            $table->string('chat_message_font_size')->nullable();

            // Active theme
            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
