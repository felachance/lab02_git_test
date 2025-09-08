<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="userValidation()">
        @csrf

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="first_name" :value="__('Prénom')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')"
                required autofocus autocomplete="first_name" x-model="fields.first_name"
                @blur="validate('first_name')" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            <template x-if="errors.first_name">
                <div class="text-red-600 text-sm mt-1" x-text="errors.first_name"></div>
            </template>
        </div>

        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Nom')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')"
                required autocomplete="last_name" x-model="fields.last_name" @blur="validate('last_name')" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            <template x-if="errors.last_name">
                <div class="text-red-600 text-sm mt-1" x-text="errors.last_name"></div>
            </template>
        </div>

        <!-- Phone number -->
        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Numéro de téléphone')" />
            <x-text-input id="phone_number" class="block mt-1 w-full" type="text" name="phone_number"
                :value="old('phone_number')" required autocomplete="phone_number" x-model="fields.phone_number"
                @blur="validate('phone_number')" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            <template x-if="errors.phone_number">
                <div class="text-red-600 text-sm mt-1" x-text="errors.phone_number"></div>
            </template>
        </div>

        <!-- Birthdate -->
        <div class="mt-4">
            <x-input-label for="birthdate" :value="__('Date de naissance')" />
            <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate" :value="old('birthdate')"
                required autocomplete="birthdate" />
            <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
        </div>

        <!-- Code -->
        <div class="mt-4">
            <x-input-label for="code" :value="__('Code')" />
            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code')"
                required autocomplete="code" x-model="fields.code" @blur="validate('code')" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
            <template x-if="errors.code">
                <div class="text-red-600 text-sm mt-1" x-text="errors.code"></div>
            </template>
        </div>

        <!-- Note -->
        <div class="mt-4">
            <x-input-label for="note" :value="__('Note')" />
            <x-text-input id="note" class="block mt-1 w-full" type="text" name="note" :value="old('note')"
                autocomplete="note" />
            <x-input-error :messages="$errors->get('note')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Courriel')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="email" x-model="fields.email" @blur="validate('email')" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <template x-if="errors.email">
                <div class="text-red-600 text-sm mt-1" x-text="errors.email"></div>
            </template>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" x-model="fields.password" @blur="validate('password')" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <template x-if="errors.password">
                <div class="text-red-600 text-sm mt-1" x-text="errors.password"></div>
            </template>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmation de mot de passe')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            {{-- <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a> --}}

            <x-primary-button class="ms-4" x-bind:disabled="hasErrors">
                {{ __('Enregistrer') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
