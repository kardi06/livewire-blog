<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\User;
use Storage;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'image',
        'published_at',
        'featured'
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime'
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', Carbon::now());
    }

    public function scopeWithCategory($query, string $category)
    {
        return $query->whereHas('categories', function ($query) {
            $query->where('slug', $category);
        });
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function getExcerpt()
    {
        return STR::limit(strip_tags($this->body), 150);
    }
    public function getReadingTime()
    {
        $words = str_word_count($this->body);
        return ceil($words / 250) < 1 ? 1 : ceil($words / 250);
    }

    public function getThumbnailImage()
    {
        $isUrl = str_contains($this->image, 'http');

        return $isUrl ? $this->image : Storage::disk('public')->url($this->image);
    }
}
