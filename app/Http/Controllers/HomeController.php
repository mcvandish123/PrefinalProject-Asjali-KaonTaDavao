<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Eatery;
use App\Models\Review;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $topRated = Eatery::with(['district', 'features', 'permit'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->has('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->orderBy('name')
            ->take(3)
            ->get();

        return view('home', [
            'eateryCount' => Eatery::count(),
            'districtCount' => District::count(),
            'reviewCount' => Review::count(),
            'topRated' => $topRated,
        ]);
    }
}
