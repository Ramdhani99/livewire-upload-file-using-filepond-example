<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload images using filepond in livewire example</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
            integrity="sha384-cNc6Ohk6WSbK+sByRXr9COZWTDVEag1x/Qb7jg15rtdQ4eOqYCfzwQGAxvoh+FQX" crossorigin="anonymous">
    </script>

    @livewireStyles
    @stack('css')
</head>

<body>
    <main class="flex h-screen w-screen items-center justify-center overflow-y-auto overflow-x-hidden">
        <div class="container h-full w-full px-4">
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
    @stack('js')
    @stack('scripts')
</body>

</html>
