<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\View\View;

class DistrictController extends Controller
{
    public function index(): View
    {
        $districts = District::withCount(['eateries', 'reviews'])->orderBy('name')->get();

        return view('districts.index', compact('districts'));
    }

    public function show(District $district): View
    {
        $district->loadCount('reviews');

        $eateries = $district->eateries()
            ->with(['district', 'features', 'permit'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->orderBy('name')
            ->paginate(9);

        return view('districts.show', compact('district', 'eateries'));
    }
}
