<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="user-id" content="{{ auth()->id() }}">

    <title>{{ config('app.name', 'LanggengTani') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon/Logo Tab.png') }}" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/favicon/Logo Tab.png') }}" />

    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-cover bg-center bg-no-repeat relative"
        style="background-image: url('{{ asset('images/bg.jpg') }}');">
        <div class="absolute inset-0 bg-black opacity-60"></div>
        <div class="relative z-10">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>



        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg relative z-10">
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <div class="text-center mb-3">
                    <p class="mt-2" style="color: #374151;">Reset Password</p>
                </div>
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                        :value="old('email', $request->email)" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <div class="relative">
                        <x-text-input id="password" class="block mt-1 w-full pr-12" type="password" name="password"
                            required autocomplete="new-password" />
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                            onclick="togglePassword('password')">
                            <i id="password-icon" class="bx bx-hide text-gray-500 hover:text-gray-700 text-xl"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <div class="relative">
                        <x-text-input id="password_confirmation" class="block mt-1 w-full pr-12" type="password"
                            name="password_confirmation" required autocomplete="new-password" />
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
                            onclick="togglePassword('password_confirmation')">
                            <i id="password_confirmation-icon"
                                class="bx bx-show text-gray-500 hover:text-gray-700 text-xl"></i>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('Submit') }}
                    </x-primary-button>
                </div>
            </form>

            <script>
                function togglePassword(inputId) {
                    const input = document.getElementById(inputId);
                    const icon = document.getElementById(inputId + '-icon');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('bx-hide');
                        icon.classList.add('bx-show');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('bx-show');
                        icon.classList.add('bx-hide');
                    }
                }
            </script>
        </div>
    </div>
</body>

</html>
