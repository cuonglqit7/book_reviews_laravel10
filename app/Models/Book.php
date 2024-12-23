<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author'];

    protected static function booted()
    {
        static::created(fn(Book $book) => cache()->forget('book' . $book->id));
        static::updated(fn(Book $book) => cache()->forget('book' . $book->id));
        static::deleted(fn(Book $book) => cache()->forget('book' . $book->id));
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'book_id');
    }

    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withCount(
            ['reviews' => fn(Builder $query) => $this->dateRangFillter($query, $from, $to)]
        );
    }

    public function scopeWithAvgRating(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withAvg(
            [
                'reviews' => fn(Builder $query) => $this->dateRangFillter($query, $from, $to)
            ],
            'rating'
        );
    }

    private function dateRangFillter(Builder $query, $from = null, $to = null)
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif ($to && !$from) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->where('created_at', '<=', [$from, $to]);
        }
    }

    public function scopePopular(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withReviewsCount()->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query): Builder
    {
        return $query->withAvgRating()->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviews(Builder $query, int $minReviews): Builder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    public function scopePopularLastMonth(Builder $query): Builder
    {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(5);
    }

    public function scopePopularLastSixMonths(Builder $query): Builder
    {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(5);
    }

    public function scopeHighestRatedLastMonth(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(5);
    }

    public function scopeHighestRatedLastSixMonths(Builder $query): Builder
    {
        return $query->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(5);
    }
}