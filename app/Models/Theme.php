<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Theme extends Model implements HasMedia
{
    use Auditable, Filterable, HasFactory, InteractsWithMedia;

    /**
     * Table name (optional if it matches Laravel's pluralization).
     *
     * @var string
     */
    protected $table = 'themes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',

        // Background colors
        'chat_header_bg_color',
        'chat_body_bg_color',
        'chat_bot_bg_color',
        'chat_user_bg_color',

        // Text colors
        'chat_header_text_color',
        'chat_bot_text_color',
        'chat_user_text_color',

        // Buttons (only bg color is stored in DB, image handled by Media Library)
        'chat_button_bg_color',

        // Toggle (only bg color is stored in DB, image handled by Media Library)
        'chat_toggle_bg_color',

        // Fonts
        'chat_header_font_family',
        'chat_header_font_size',
        'chat_message_font_family',
        'chat_message_font_size',

        // Active theme
        'is_active',
    ];

    /**
     * Casts to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('chat_button_image')
            ->singleFile();

        $this
            ->addMediaCollection('chat_toggle_image')
            ->singleFile();
    }

    /**
     * Accessor for chat_button_image URL.
     */
    public function getChatButtonImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('chat_button_image');
    }

    /**
     * Accessor for chat_toggle_image URL.
     */
    public function getChatToggleImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('chat_toggle_image');
    }
}
