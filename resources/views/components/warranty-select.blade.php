@props([
    'name' => 'warranty_period',
    'id' => null,
    'selected' => null,
    'class' => 'w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200',
])

{{--
    Single source of truth for the warranty dropdown.

    "No Warranty" is a real, selectable option. Not every item a computer shop
    sells is covered (cables, consumables, most peripherals, second hand stock),
    and leaving it blank previously made the POS guess "1 Year" on the receipt.
--}}
@php
    $current = ($selected === null || $selected === '')
        ? null
        : \App\Models\Product::normalizeWarranty($selected);
@endphp

<select name="{{ $name }}" @if ($id) id="{{ $id }}" @endif class="{{ $class }}">
    <option value="" @selected($current === null)>Select Warranty</option>
    @foreach (\App\Http\Requests\ProductRequest::WARRANTY_OPTIONS as $option)
        <option value="{{ $option }}" @selected($current === $option)>{{ $option }}</option>
    @endforeach
</select>
