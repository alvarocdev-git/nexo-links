<?php

namespace App\Models;

use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $username
 * @property string|null $bio
 * @property string|null $avatar_path
 * @property string|null $banner_path
 * @property string $theme
 * @property string $background_type
 * @property string|null $background_start
 * @property string|null $background_end
 */
#[Fillable([
    'username',
    'bio',
    'avatar_path',
    'banner_path',
    'theme',
    'background_type',
    'background_start',
    'background_end',
])]
class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(fn (Page $page) => $page->flushCache());
    }

    /**
     * Forget the cached public page in every locale.
     */
    public function flushCache(): void
    {
        foreach (array_keys(config('nexo.locales')) as $locale) {
            cache()->forget("page:{$this->id}:{$locale}");
        }
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Link, $this>
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class)->orderBy('position');
    }

    /**
     * @return HasMany<SocialLink, $this>
     */
    public function socialLinks(): HasMany
    {
        return $this->hasMany(SocialLink::class)->orderBy('position');
    }

    /**
     * @return HasMany<Report, $this>
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Inline CSS for a custom background, or null to use the default one.
     */
    public function backgroundCss(): ?string
    {
        return match ($this->background_type) {
            'solid' => $this->background_start !== null
                ? "background: {$this->background_start}"
                : null,
            'gradient' => $this->background_start !== null && $this->background_end !== null
                ? "background: linear-gradient(160deg, {$this->background_start}, {$this->background_end})"
                : null,
            default => null,
        };
    }

    /**
     * Whether a custom background is light, to pick a readable ink color.
     * Uses the YIQ brightness formula on the (top) background color.
     */
    public function hasLightBackground(): bool
    {
        $hex = $this->background_start;

        if ($hex === null || preg_match('/^#[0-9a-f]{6}$/i', $hex) !== 1) {
            return true;
        }

        [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x');

        return ($r * 299 + $g * 587 + $b * 114) / 1000 >= 128;
    }

    /**
     * Accent colors of the selected theme preset.
     *
     * @return array{label: string, from: string, to: string}
     */
    public function themeAccent(): array
    {
        return config('nexo.themes.'.$this->theme, config('nexo.themes.default'));
    }
}
