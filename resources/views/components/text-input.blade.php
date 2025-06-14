@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-[#149d80] focus:ring-[#149d80] rounded-md shadow-sm']) !!}>
