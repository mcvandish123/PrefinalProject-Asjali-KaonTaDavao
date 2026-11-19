@php
    $selected = old('features', isset($eatery) ? $eatery->features->pluck('id')->all() : []);
    $isOpen = old('is_open', $eatery->is_open ?? true);
@endphp
@csrf

<x-form-field name="name" label="Name">
    <input id="name" name="name" type="text" value="{{ old('name', $eatery->name ?? '') }}" class="w-full rounded border border-stone-300 px-3 py-2">
</x-form-field>

<x-form-field name="district_id" label="District">
    <select id="district_id" name="district_id" class="w-full rounded border border-stone-300 px-3 py-2">
        <option value="">Choose a district</option>
        @foreach ($districts as $district)
            <option value="{{ $district->id }}" @selected(old('district_id', $eatery->district_id ?? null) == $district->id)>{{ $district->name }}</option>
        @endforeach
    </select>
</x-form-field>

<x-form-field name="address" label="Address">
    <input id="address" name="address" type="text" value="{{ old('address', $eatery->address ?? '') }}" class="w-full rounded border border-stone-300 px-3 py-2">
</x-form-field>

<x-form-field name="price_range" label="Price range">
    <select id="price_range" name="price_range" class="w-full rounded border border-stone-300 px-3 py-2">
        <option value="">Choose a price range</option>
        @foreach (['budget', 'mid', 'splurge'] as $range)
            <option value="{{ $range }}" @selected(old('price_range', $eatery->price_range ?? null) === $range)>{{ ucfirst($range) }}</option>
        @endforeach
    </select>
</x-form-field>

<x-form-field name="specialty" label="Specialty (optional)">
    <input id="specialty" name="specialty" type="text" value="{{ old('specialty', $eatery->specialty ?? '') }}" class="w-full rounded border border-stone-300 px-3 py-2">
</x-form-field>

<div class="mb-4">
    <input type="hidden" name="is_open" value="0">
    <label class="inline-flex items-center gap-2 text-sm font-medium">
        <input type="checkbox" name="is_open" value="1" @checked($isOpen)>
        Currently open
    </label>
</div>

<x-form-field name="permit_no" label="Business permit number">
    <input id="permit_no" name="permit_no" type="text" value="{{ old('permit_no', $eatery->permit->permit_no ?? '') }}" class="w-full rounded border border-stone-300 px-3 py-2">
</x-form-field>

<x-form-field name="expires_on" label="Permit expiry date">
    <input id="expires_on" name="expires_on" type="date" value="{{ old('expires_on', isset($eatery->permit) ? $eatery->permit->expires_on->toDateString() : '') }}" class="w-full rounded border border-stone-300 px-3 py-2">
</x-form-field>

<fieldset class="mb-4">
    <legend class="mb-1 text-sm font-medium">Features</legend>
    @foreach ($features as $feature)
        <label class="mr-4 inline-flex items-center gap-1 text-sm">
            <input type="checkbox" name="features[]" value="{{ $feature->id }}" @checked(in_array($feature->id, $selected))>
            {{ $feature->name }}
        </label>
    @endforeach
    @error('features')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error('features.*')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</fieldset>

<button type="submit" class="rounded bg-red-800 px-4 py-2 font-semibold text-white hover:bg-red-700">Save eatery</button>
