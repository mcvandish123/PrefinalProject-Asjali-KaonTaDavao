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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EateryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $districtId = $request->query('district');
        $featureName = $request->query('feature');

        $eateries = Eatery::with(['district', 'features', 'permit'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->when($search !== '', fn ($query) => $query->where(
                fn ($match) => $match->where('name', 'like', "%{$search}%")
                    ->orWhere('specialty', 'like', "%{$search}%")
            ))
            ->when(filled($districtId), fn ($query) => $query->where('district_id', (int) $districtId))
            ->when(filled($featureName), fn ($query) => $query->whereHas(
                'features',
                fn ($features) => $features->where('name', (string) $featureName)
            ))
            ->orderBy('name')
            ->paginate(6)
            ->withQueryString();

        return view('eateries.index', [
            'eateries' => $eateries,
            'districts' => District::orderBy('name')->get(),
            'features' => Feature::orderBy('name')->get(),
            'filters' => [
                'q' => $search,
                'district' => $districtId,
                'feature' => $featureName,
            ],
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
            ...Arr::only($data, ['district_id', 'name', 'address', 'price_range', 'specialty', 'is_open']),
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
        $eatery->load([
            'district', 'permit', 'dishes', 'features',
            'reviews' => fn ($query) => $query->with('user')->latest()->latest('id'),
        ]);

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
            ...Arr::only($data, ['district_id', 'name', 'address', 'price_range', 'specialty', 'is_open']),
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
        DB::transaction(function () use ($eatery) {
            $eatery->reviews()->delete();
            $eatery->dishes()->delete();
            $eatery->permit()->delete();
            $eatery->features()->detach();
            $eatery->delete();
        });

        return redirect()->route('eateries.index')->with('status', 'Eatery deleted.');
    }
}
