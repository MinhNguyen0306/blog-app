<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Goa Social Media</title>

    @vite(['resources/css/app.css', 'resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <x-common.left-content />

        <x-common.mid-content>
            {{ $slot }}
        </x-common.mid-content>

        <x-common.right-content />
    </div>
</body>

</html>