@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'fw-medium text-body-sm text-success']) }}>
        {{ $status }}
    </div>
@endif
