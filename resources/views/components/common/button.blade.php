<head>
    @vite('resources/scss/button.scss')
</head>

<button type="{{ $type }}" {{ $attributes->class(['buttonContainer'])->merge(['type' => 'button']) }}>
    {{ $slot }}
</button>
