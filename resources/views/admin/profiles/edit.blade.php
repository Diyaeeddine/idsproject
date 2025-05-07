<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Modifier l'utilisateur
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('profile.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                        <x-text-input type="text" name="name" value="{{ $user->name }}" class="mt-1 block w-full rounded-md shadow-sm" required />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <x-text-input type="email" name="email" value="{{ $user->email }}" class="mt-1 block w-full rounded-md shadow-sm" required/>
                    </div>

<div class="mb-4">
    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rôle</label>
    <select id="role" name="role"
        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        required>
        <option value="user" {{ (isset($user) && $user->role == 'user') ? 'selected' : '' }}>Utilisateur</option>
        <option value="admin" {{ (isset($user) && $user->role == 'admin') ? 'selected' : '' }}>Admin</option>
    </select>
</div>



                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
