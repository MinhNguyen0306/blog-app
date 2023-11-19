<head>
    @vite('resources/scss/logo.scss')
</head>

<div class="logoContainer" onclick="window.location='{{ route('home') }}'">
    <img src={{ asset('images/Logo.svg') }} alt="Logo image" />
    <h2>Goa</h2>
</div>
