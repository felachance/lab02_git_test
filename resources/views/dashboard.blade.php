<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-6xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    @php
        if ($branch){
            $branchRoute =  route('shifts.schedule', ['branch' => $branch->id, 'week' => date('Ymd', strtotime('last sunday'))]);
        } else{
            $branchRoute = route('branches.index');
        }
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="p-6 text-gray-900">
                    <div class="flex justify-between my-10">
                        <h1 class="text-4xl font-bold m-auto">{{ __('Bienvenue, ') . Auth::user()->first_name . " " . Auth::user()->last_name }}</h1>
                    </div>
                </div>
                <div class="flex justify-around my-10">

                    <a href="{{ $branchRoute }}" >
                        <div class="bg-white p-6 rounded-3xl shadow w-[250px] h-[250px] flex flex-col justify-center items-center hover:scale-105 transition-transform duration-300">
                            <h2 class="text-2xl font-semibold mb-4 text-center">{{__('Quarts cette semaine')}}</h2>
                            <h3 class="text-black-700 text-8xl font-bold mb-4">{{ $nbShifts }}</h3>
                        </div>
                    </a>
                    <a href="{{ $branchRoute }}" >
                        <div class="bg-white p-6 rounded-3xl shadow w-[250px] h-[250px] flex flex-col justify-center items-center hover:scale-105 transition-transform duration-300">
                            <h2 class="text-2xl font-semibold mb-4 text-center">{{__('Heures totales cette semaine')}}</h2>
                            <h3 class="text-black-700 text-8xl font-bold mb-4">{{ $nbHours }}</h3>
                        </div>
                    </a>
                    <a href="{{ route('timeoff.index') }}" >
                        <div class="bg-white p-6 rounded-3xl shadow w-[250px] h-[250px] flex flex-col justify-center items-center hover:scale-105 transition-transform duration-300">
                            <h2 class="text-2xl font-semibold mb-4 text-center">{{__('Demandes de congé en attente')}}</h2>
                            <h3 class="text-black-700 text-8xl font-bold mb-4">{{ $nbUnapprovedTimeOffRequests }}</h3>
                        </div>
                    </a>
                </div>
                <div class="flex justify-around my-10">
                    <a href="{{ route('employee.index') }}" >
                        <div class="bg-white p-6 rounded-3xl shadow w-[250px] h-[250px] flex flex-col justify-center items-center hover:scale-105 transition-transform duration-300">
                            <h2 class="text-2xl font-semibold mb-4 text-center">{{__('Employés actifs')}}</h2>
                            <h3 class="text-black-700 text-8xl font-bold mb-4">{{ $nbEmployees }}</h3>
                        </div>
                    </a>
                    <a href="{{ route('branches.index') }}" >
                        <div class="bg-white p-6 rounded-3xl shadow w-[250px] h-[250px] flex flex-col justify-center items-center hover:scale-105 transition-transform duration-300">
                            <h2 class="text-2xl font-semibold mb-4 text-center">{{__('Succursales')}}</h2>
                            <h3 class="text-black-700 text-8xl font-bold mb-4">{{ $nbBranches }}</h3>
                        </div>
                    </a>
                    <a href="{{ route('replacements.index') }}" >
                        <div class="bg-white p-6 rounded-3xl shadow w-[250px] h-[250px] flex flex-col justify-center items-center hover:scale-105 transition-transform duration-300">
                            <h2 class="text-2xl font-semibold mb-4 text-center">{{__('Remplacements acceptés')}}</h2>
                            <h3 class="text-black-700 text-8xl font-bold mb-4">{{ $nbReplacements }}</h3>
                        </div>
                    </a>
                </div>
        </div>
    </div>
</x-app-layout>

