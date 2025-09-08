<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" x-data="userValidation()">
        @csrf
        @method('patch')

        <div class="mb-4">
            <x-input-label for="first_name" :value="__('First name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text"
                name="first_name" required autocomplete="first_name"
                x-init="fields.first_name = '{{ $user->first_name }}'" x-model="fields.first_name"
                @blur="validate('first_name')" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            <template x-if="errors.first_name">
                <div class="text-red-600 text-sm mt-1" x-text="errors.first_name"></div>
            </template>
        </div>
        <div class="mb-4">
            <x-input-label for="last_name" :value="__('Last name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text"
                name="last_name" required autocomplete="last_name"
                x-init="fields.last_name = '{{ $user->last_name }}'" x-model="fields.last_name" @blur="validate('last_name')" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            <template x-if="errors.last_name">
                <div class="text-red-600 text-sm mt-1" x-text="errors.last_name"></div>
            </template>
        </div>
        <div class="mb-4">
            <x-input-label for="code" :value="__('Code')" />
            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code"
                required autocomplete="code" x-init="fields.code = '{{ $user->code }}'" x-model="fields.code"
                @blur="validate('code')" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
            <template x-if="errors.code">
                <div class="text-red-600 text-sm mt-1" x-text="errors.code"></div>
            </template>
        </div>
        <div class="mb-4">
            <x-input-label for="birthdate" :value="__('Birthdate')" />
            <x-text-input id="birthdate" class="block mt-1 w-full" type="date"
                name="birthdate" required autocomplete="birthdate"
                x-init="fields.birthdate = '{{ $user->birthdate }}'" x-model="fields.birthdate" />
            <x-input-error :messages="$errors->get('birthdate')" class="mt-2" />
        </div>
        <div class="mb-4">
            <x-input-label for="phone_number" :value="__('Phone number')" />
            <x-text-input id="phone_number" class="block mt-1 w-full" type="text"
                name="phone_number" required autocomplete="phone_number"
                x-init="fields.phone_number = '{{ $user->phone_number }}'" x-model="fields.phone_number" @blur="validate('phone_number')" />
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            <template x-if="errors.phone_number">
                <div class="text-red-600 text-sm mt-1" x-text="errors.phone_number"></div>
            </template>
        </div>
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                required autocomplete="email" x-init="fields.email = '{{ $user->email }}'" x-model="fields.email"
                @blur="validate('email')" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <template x-if="errors.email">
                <div class="text-red-600 text-sm mt-1" x-text="errors.email"></div>
            </template>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
