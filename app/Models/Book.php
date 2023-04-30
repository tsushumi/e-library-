<?php

namespace App\Models;

use App\Casts\TitleCast;
use Spatie\Sluggable\HasSlug;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Book extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use HasSlug;
    use InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'title',
        'author',
        'slug',
        'ISBN_10',
        'ISBN_13',
        'edition',
        'value',
        'copies',
        'publisher',
        'bookable',
        'borrowed_books'
    ];

    protected $casts = [
        'title' => TitleCast::class,
        'author' => TitleCast::class,
        'publisher' => TitleCast::class,
        'bookable' => 'boolean',
        'borrowed_books' => 'integer'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['title', 'author'])
            ->saveSlugsTo('slug');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }

    public function scopeSearch($query, $term)
    {
        $term = "%$term%";

        $query->where(function ($query) use ($term) {
            $query->where('title', 'like', $term)
                ->orWhere('ISBN_10', 'like', $term)
                ->orWhere('ISBN_13', 'like', $term)
                ->orWhere('slug', 'like', $term)
                ->orWhere('edition', 'like', $term)
                ->orWhere('author', 'like', $term);
        });
    }

    public function scopeBookQuery($query)
    {
        $search_term = request('search', '');

        $sort_direction = request('sort_direction', 'desc');

        if (!in_array($sort_direction, ['asc', 'desc'])) {
            $sort_direction = 'desc';
        }

        $sort_field = request('sort_field', 'created_at');
        if (!in_array($sort_field, ['title', 'created_at'])) {
            $sort_field = 'created_at';
        }

        $query->with(['category'])
            ->orderBy($sort_field, $sort_direction)
            ->search(trim($search_term));
    }
}
