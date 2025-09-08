<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-6xl text-gray-800 leading-tight">
            {{ __('Ajouter un employé') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('employee.store') }}" method="POST" x-data="userValidation()">
                        @csrf
                        <div class="flex">
                            <div class="w-1/2 pr-4">
                                <div class="mb-4">
                                    <x-input-label for="first_name" :value="__('Prénom')" />
                                    <x-text-input id="first_name" class="block mt-1 w-full" type="text"
                                        name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name"
                                        x-model="fields.first_name" @blur="validate('first_name')" />
                                    <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                                    <template x-if="errors.first_name">
                                        <div class="text-red-600 text-sm mt-1" x-text="errors.first_name"></div>
                                    </template>
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="last_name" :value="__('Nom')" />
                                    <x-text-input id="last_name" class="block mt-1 w-full" type="text"
                                        name="last_name" :value="old('last_name')" required autocomplete="last_name"
                                        x-model="fields.last_name" @blur="validate('last_name')" />
                                    <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                                    <template x-if="errors.last_name">
                                        <div class="text-red-600 text-sm mt-1" x-text="errors.last_name"></div>
                                    </template>
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="code" :value="__('Code')" />
                                    <x-text-input id="code" class="block mt-1 w-full" type="text" name="code"
                                        :value="old('code')" required autocomplete="code" x-model="fields.code"
                                        @blur="validate('code')" />
                                    <x-input-error :messages="$errors->get('code')" class="mt-2" />
                                    <template x-if="errors.code">
                                        <div class="text-red-600 text-sm mt-1" x-text="errors.code"></div>
                                    </template>
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="birthdate" :value="__('Date de naissance')" />
                                    <x-text-input id="birthdate" class="block mt-1 w-full" type="date"
                                        name="birthdate" :value="old('birthdate')" required autocomplete="birthdate"
                                        x-model="fields.birthdate" />
                                    <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="phone_number" :value="__('Numéro de téléphone')" />
                                    <x-text-input id="phone_number" class="block mt-1 w-full" type="text"
                                        name="phone_number" :value="old('phone_number')" required autocomplete="phone_number"
                                        x-model="fields.phone_number" @blur="validate('phone_number')" />
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                    <template x-if="errors.phone_number">
                                        <div class="text-red-600 text-sm mt-1" x-text="errors.phone_number"></div>
                                    </template>
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="email" :value="__('Courriel')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                        :value="old('email')" required autocomplete="email" x-model="fields.email"
                                        @blur="validate('email')" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    <template x-if="errors.email">
                                        <div class="text-red-600 text-sm mt-1" x-text="errors.email"></div>
                                    </template>
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="password" :value="__('Mot de passe')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password"
                                        name="password" required autocomplete="new-password" x-model="fields.password"
                                        @blur="validate('password')" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    <template x-if="errors.password">
                                        <div class="text-red-600 text-sm mt-1" x-text="errors.password"></div>
                                    </template>
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" />
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                        name="password_confirmation" required autocomplete="new-password"
                                        x-model="fields.password_confirmation" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>
                            <div class="w-1/2 pl-4">
                                <div class="mb-4">
                                    <x-input-label for="note" :value="__('Note')" />
                                    <textarea id="note" name="note"
                                        class="block mt-1 w-full h-40 shadow-sm border-gray-300 rounded-md focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        x-model="fields.note">{{ old('note') }}</textarea>
                                    <x-input-error :messages="$errors->get('note')" class="mt-2" />
                                </div>
                                <div class="mb-4">
                                    <x-input-label for="roles" :value="__('Rôle(s)')"
                                        class="block text-gray-700 font-bold mb-2" />
                                    <div class="flex flex-col">
                                        @foreach ($roles as $role)
                                            <label class="flex items-center mb-2">
                                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                                    class="form-checkbox h-5 w-5 text-indigo-600">
                                                <span class="ml-2 text-gray-700">{{ $role->role }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <div class="flex space-x-4">
                                <button type="button"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                    @click="resetForm()">
                                    {{ __('Éffacer les champs') }}
                                </button>
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    {{ __('Ajouter l\'employé') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
