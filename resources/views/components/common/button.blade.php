<head>
    @vite('resources/scss/button.scss')
</head>

<button type="{{ $type }}" name="{{ $name }}"
    {{ $attributes->class(['buttonContainer'])->merge(['type' => 'button']) }}>
    {{ $slot }}
</button>
