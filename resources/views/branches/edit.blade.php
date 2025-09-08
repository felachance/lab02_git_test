<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-6xl text-gray-800 leading-tight">
            {{ __('Détail de la succursale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <form x-data="branchForm()" @submit.prevent="validateForm" method="POST" action="{{ route('branches.update', $branch->id) }}">

                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/6"></div>
                            <div class="md:w-1/6">
                                <label  for="name" class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                                    {{ __("Nom") }}
                                </label>
                            </div>
                            <div class="md:w-1/2">
                                <input x-model="name" name="name" id="name" type="text"
                                       class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700
                                              leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            </div>
                            <div class="md:w-1/3 pl-5">
                                <template x-if="errors.name">
                                    <div class="text-red-500 text-sm mt-1" x-text="errors.name"></div>
                                </template>
                            </div>
                        </div>
                        <!-- Civic No -->
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/6"></div>
                            <div class="md:w-1/6">
                                <label for="civic_no" class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                                    {{ __("#") }}
                                </label>
                            </div>
                            <div class="md:w-1/2">
                                <input x-model="civic_no" name="civic_no" id="civic_no" type="text"
                                       class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700
                                              leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            </div>
                            <div class="md:w-1/3 pl-6">
                                <template x-if="errors.civic_no">
                                    <div class="text-red-500 text-sm mt-1" x-text="errors.civic_no"></div>
                                </template>
                            </div>
                        </div>

                        <!-- Road -->
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/6"></div>
                            <div class="md:w-1/6">
                                <label for="road" class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                                    {{ __("Rue") }}
                                </label>
                            </div>
                            <div class="md:w-1/2">
                                <input x-model="road" name="road" id="road" type="text"
                                       class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700
                                              leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            </div>
                            <div class="md:w-1/3 pl-6">
                                <template x-if="errors.road">
                                    <div class="text-red-500 text-sm mt-1" x-text="errors.road"></div>
                                </template>
                            </div>
                        </div>

                        <!-- City -->
                        <div class="md:flex md:items-center mb-6">
                            <div class="md:w-1/6"></div>
                            <div class="md:w-1/6">
                                <label for="city" class="block text-gray-500 font-bold md:text-right mb-1 md:mb-0 pr-4">
                                    {{ __("Ville") }}
                                </label>
                            </div>
                            <div class="md:w-1/2">
                                <input x-model="city" name="city" id="city" type="text"
                                       class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700
                                              leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            </div>
                            <div class="md:w-1/3 pl-6">
                                <template x-if="errors.city">
                                    <div class="text-red-500 text-sm mt-1" x-text="errors.city"></div>
                                </template>
                            </div>
                        </div>


                        <div class="md:flex md:items-center">
                            <div class="md:w-1/3"></div>
                            <div class="md:w-2/3">
                                <!-- Submit Button -->
                                <button type="submit"
                                        class="shadow bg-blue-500 hover:bg-blue-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
                                    {{ __("Modifier") }}
                                </button>

                                <!-- cancel button -->
                                <a href="{{ route('branches.index') }}"
                                class="inline-block shadow bg-gray-500 hover:bg-gray-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded">
                                 {{ __("Annuler") }}
                             </a>
                            </div>
                        </div>

                    </form>
                    <script>
                        function branchForm() {
                            return {
                                name: '{{ $branch->name }}',
                                civic_no: '{{ $branch->civic_no }}',
                                road: '{{ $branch->road }}',
                                city: '{{ $branch->city }}',
                                errors: {},

                                validateForm() {
                                    this.errors = {};
                                    if(!this.name.trim()){
                                        this.errors.name = 'Le nom est requis.';
                                    }
                                    if(!this.road.trim()){
                                        this.errors.road = 'La rue est requise.';
                                    }
                                    if(!this.city.trim()){
                                        this.errors.city = 'La ville est requise.';
                                    }
                                    if(!/^\d+$/.test(this.civic_no.trim())){
                                        this.errors.civic_no = 'Le numéro civique est requis.';
                                    }
                                    if (Object.keys(this.errors).length === 0) {
                                        // If no error submit error
                                        this.$el.submit();
                                    }
                                },
                            }
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
