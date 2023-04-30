<?php

namespace App\Models;

use App\Casts\TitleCast;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'details',
    ];

    protected $casts = [
        'title' => TitleCast::class,
        'category_number' => 'integer'
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function ($query) use ($term) {
            $query->where('title', 'like', $term);
        });
    }

    public function scopeCategoryQuery($query)
    {
        $search_term = request('search', '');

        $sort_direction = request('sort_direction', 'desc');

        if (!in_array($sort_direction, ['asc', 'desc'])) {
            $sort_direction = 'desc';
        }

        $sort_field = request('sort_field', 'created_at');
        if (!in_array($sort_field, ['name', 'created_at'])) {
            $sort_field = 'created_at';
        }

        $query->withCount(['books'])
            ->orderBy($sort_field, $sort_direction)
            ->search(trim($search_term));
    }
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
