<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEateryRequest;
use App\Http\Requests\UpdateEateryRequest;
use App\Models\District;
use App\Models\Eatery;
use App\Models\Feature;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EateryController extends Controller
{
    public function index(Request $request): View
    {
        $activeFeature = $request->filled('feature') ? Feature::find($request->query('feature')) : null;

        $eateries = Eatery::with(['district', 'features', 'permit'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($activeFeature, fn ($query) => $query->whereHas(
                'features',
                fn ($features) => $features->whereKey($activeFeature->id)
            ))
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();

        return view('eateries.index', [
            'eateries' => $eateries,
            'features' => Feature::orderBy('name')->get(),
            'activeFeature' => $activeFeature,
        ]);
    }

    public function create(): View
    {
        return view('eateries.create', [
            'districts' => District::orderBy('name')->get(),
            'features' => Feature::orderBy('name')->get(),
        ]);
    }

    public function store(StoreEateryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $eatery = Eatery::create([
            ...collect($data)->only(['district_id', 'name', 'address', 'price_range', 'specialty', 'is_open'])->all(),
            'slug' => Str::slug($data['name']),
        ]);

        $eatery->permit()->create([
            'permit_no' => $data['permit_no'],
            'expires_on' => $data['expires_on'],
        ]);
        $eatery->features()->sync($data['features'] ?? []);

        return redirect()->route('eateries.show', $eatery)->with('status', 'Eatery added.');
    }

    public function show(Eatery $eatery): View
    {
        $eatery->load(['district', 'permit', 'dishes', 'features', 'reviews.user']);

        return view('eateries.show', [
            'eatery' => $eatery,
            'averageRating' => $eatery->reviews->avg('rating'),
            'reviewers' => User::orderBy('name')->get(),
        ]);
    }

    public function edit(Eatery $eatery): View
    {
        $eatery->load(['permit', 'features']);

        return view('eateries.edit', [
            'eatery' => $eatery,
            'districts' => District::orderBy('name')->get(),
            'features' => Feature::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateEateryRequest $request, Eatery $eatery): RedirectResponse
    {
        $data = $request->validated();

        $eatery->update([
            ...collect($data)->only(['district_id', 'name', 'address', 'price_range', 'specialty', 'is_open'])->all(),
            'slug' => Str::slug($data['name']),
        ]);
        $eatery->permit()->updateOrCreate([], [
            'permit_no' => $data['permit_no'],
            'expires_on' => $data['expires_on'],
        ]);
        $eatery->features()->sync($data['features'] ?? []);

        return redirect()->route('eateries.show', $eatery)->with('status', 'Eatery updated.');
    }

    public function destroy(Eatery $eatery): RedirectResponse
    {
        $eatery->reviews()->delete();
        $eatery->dishes()->delete();
        $eatery->permit()->delete();
        $eatery->features()->detach();
        $eatery->delete();

        return redirect()->route('eateries.index')->with('status', 'Eatery deleted.');
    }
}
