
<x-guest-layout>
    {{-- <nav class="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left side - empty or logo -->
                <div></div>

                <!-- Right side - Login as User button -->
                <div>
                    <a href="{{ route('user.login') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        Login as User
                    </a>
                </div>
            </div>
        </div>
    </nav> --}}
    <!-- Session Status -->
        <!-- Logo (optionnel) -->
        <div class="flex justify-center mb-6">
            <img src="/logo.png" alt="Logo" class="h-11 w-40 mt-4"> {{-- Remplace par ton logo si besoin --}}
        </div>
    <x-auth-session-status class="mb-4" :status="session('status')" />
    {{-- <p class="text-white text-center ">Admin Login</p> --}}

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Identifiant ou adresse e-mail
                </label>
                <input id="email" name="email" type="email" required autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    :value="old('email')" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4 relative">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Mot de passe
                </label>
                <input id="password" name="password" type="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm pr-10 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <button type="button" onclick="togglePassword()" class="absolute right-3 top-9 text-gray-400">
                    <i id="eyeIcon" class="fas fa-eye"></i>
                </button>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-6">
                <input id="remember_me" name="remember" type="checkbox"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                    Se souvenir de moi
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-between mb-4">
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">
                    Mot de passe oublié ?
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md">
                    Se connecter
                </button>

            </div>
        </form>
        <style></style
        <!-- JS pour afficher/masquer mot de passe -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

</x-guest-layout>

