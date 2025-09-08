<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-6xl text-gray-800 leading-tight">
            {{ __('Horaire') }}
        </h2>
    </x-slot>
@php
if((new DateTime($week))->format('w') == 0) {
    $week = (new DateTime($week))->format('Y-m-d');
} else {
    $week = (new DateTime($week))->modify('last sunday')->format('Y-m-d');
}

function getColorById($id) {
    $colors = [
        ['bg-red-100/50', 'hover:bg-red-200', 'border-red-300'],
    ];
    return $colors[$id % count($colors)];
}
@endphp
    <div class="container mx-auto px-4 py-8" x-data="{ openEdit: false, shiftData: null, openAdd: false, availableEmployees: [], employeeAvailable: true }">
         <!-- Branch Dropdown -->
         <div class="mb-4" x-data="">
                <label for="branch" class="block text-sm font-medium text-gray-700">Succursale:</label>
                <select id="branch" name="branch" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                    x-on:change="schedule_dropdown_change($event.target.value)">
                    @foreach ($branches as $b)
                        <option value="{{ $b->id }}" {{ $b->id == $branch->id ? 'selected' : '' }}>
                            {{ $b->name }}
                        </option>
                    @endforeach
                </select>


        </div>

        <h1 class="text-2xl font-bold mb-6">Horaire de la semaine du {{ (new DateTime($week))->format('d/m/Y') }} ({{ $branch->name }})</h1>
        <div class="mt-4 flex gap-4 mb-5">
            @php
                $prevWeek = (new DateTime($week))->modify('-7 days')->format('Ymd');
                $nextWeek = (new DateTime($week))->modify('+7 days')->format('Ymd');
            @endphp
            <a href="{{ route('shifts.schedule', [$branch->id, $prevWeek]) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">{{__('Semaine précédente')}}</a>
            <a href="{{ route('shifts.schedule', [$branch->id, $nextWeek]) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">{{__('Semaine suivante')}}</a>
            <button x-on:click="openAdd = true; document.getElementById('formAdd').reset(); employeeAvailable = true;" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-auto">{{ __('Ajouter un quart') }}</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4 border">Time</th>
                        @php
                            $date = new DateTime($week);
                            $days = [];
                            for ($i = 0; $i < 7; $i++) {
                                $days[] = clone $date;
                                $date->modify('+1 day');
                            }
                        @endphp
                        @foreach ($days as $day)
                            <th class="py-2 px-4 border">
                                {{ ucfirst(\Illuminate\Support\Facades\Date::createFromFormat('Y-m-d', $day->format('Y-m-d'))->locale('fr')->translatedFormat('l')) }}<br>
                                {{ $day->format('Y-m-d') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @for ($hour = 6; $hour < 24; $hour++)
                        <tr>
                            <td class=" px-4 border align-top text-center">{{ sprintf('%02d:00', $hour) }}</td>
                            @foreach ($days as $day)
                                <td class="py-2 px-4 border relative h-[50px]">
                                    @php
                                        $dayShifts = $shifts->filter(function($shift) use ($day, $hour) {

                                            return $shift->date == $day->format('Y-m-d') &&
                                                   intval(substr($shift->start_time, 0, 2)) == $hour;
                                        });
                                        $shiftWidth = $dayShifts->count() > 0 ? (100 / $dayShifts->count()) : 100;
                                    @endphp
                                    @if($dayShifts->count() > 0)
                                        <div class="absolute inset-0 flex gap-0.5">
                                            @foreach ($dayShifts as $shift)
                                                @php //DIV des shifts
                                                    $startHour = intval(substr($shift->start_time, 0, 2));
                                                    $endHour = intval(substr($shift->end_time, 0, 2));
                                                    $duration = $endHour - $startHour;
                                                    if ($duration <= 0) {
                                                        $duration = 24 - $startHour + $endHour;
                                                    }

                                                    $user = $shift->mostRecentAssignment ? $shift->mostRecentAssignment->user : null;
                                                    $shiftData = [
                                                        'id' => $shift->id,
                                                        'date' => $shift->date,
                                                        'start_time' => $shift->start_time,
                                                        'end_time' => $shift->end_time,
                                                        'user' => $user,
                                                    ];
                                                    $colors = getColorById($user->id);
                                                @endphp
                                                <div class="p-0.5 text-xs rounded border overflow-hidden hover:cursor-pointer {{ $colors[0] }} {{ $colors[1] }} {{ $colors[2] }}"
                                                    style="height: {{ $duration * 50 }}px; width: {{ $shiftWidth }}%; z-index: 10;"
                                                    x-on:click="openEdit = true; shiftData = {{ json_encode($shiftData) }}; employeeAvailable = true;">
                                                    <div class="truncate">{{ ($shift->mostRecentAssignment?->user?->first_name ?? 'First') . " " . ($shift->mostRecentAssignment?->user?->last_name ?? 'Last') }}</div>
                                                    <div class="truncate">{{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>


        <!-- Edit shift -->
        <div
            x-cloak
            x-on:click.away="openEdit = false"
            x-show="openEdit"
            x-transition.opacity
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40">

            <div
                x-on:click.away="openEdit = false"
                x-show="openEdit"
                x-transition.opacity
                class="bg-white p-6 rounded-lg shadow-lg w-1/3 z-50 h-auto overflow-y-auto">
                <h2 class="text-xl font-bold mb-4">Modification du quart</h2>
                <p class="mb-4">Shift: <span x-text="shiftData ? shiftData.id : ''"></span></p>
                <form class="" id="formEdit" action="{{ route('shifts.update') }}" method="POST" x-ref="formEdit">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_shift" x-bind:value="shiftData ? shiftData.id : ''">
                    <input type="hidden" name="id_branch" value="{{ $branch->id }}">
                    <input type="hidden" name="date" x-bind:value="shiftData ? shiftData.date : ''" x-ref="editDate">
                    <div class="mb-4">
                        <label for="editId_user" class="block text-sm font-medium text-gray-700">Employé:</label>
                        <select id="editId_user" name="id_user" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            x-ref="editId_user"
                            x-on:change="employeeAvailable = true">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" x-bind:selected="shiftData && shiftData.user && shiftData.user.id == {{ $user->id }} ? 'selected' : ''">
                                    {{ $user->first_name . " " . $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="editEmployeeAvailableMessage" class="hidden my-4" x-show="!employeeAvailable">
                        <p class="text-red-500">L'employé n'est pas disponible pour ce quart.</p>
                        <p class="reasonText text-red-500">Raison: </p>
                    </div>
                    <div class="mb-4">
                        <label for="editStart_time" class="block text-sm font-medium text-gray-700">Heure de début:</label>
                        <select id="editStart_time" name="start_time" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            x-ref="editStart_time"
                            x-on:change="employeeAvailable = true">
                            @for ($hour = 6; $hour < 24; $hour++)
                                @for ($minute = 0; $minute < 60; $minute += 30)
                                    @php
                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                    @endphp
                                    <option value="{{ $time }}" x-bind:selected="shiftData && shiftData.start_time == '{{ $time }}:00' ? 'selected' : ''">
                                        {{ $time }}
                                    </option>
                                @endfor
                            @endfor
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="editEnd_time" class="block text-sm font-medium text-gray-700">Heure de fin:</label>
                        <select id="editEnd_time" name="end_time" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            x-ref="editEnd_time"
                            x-on:change="employeeAvailable = true">
                            @for ($hour = 6; $hour < 24; $hour++)
                                @for ($minute = 0; $minute < 60; $minute += 30)
                                    @php
                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                    @endphp
                                    <option value="{{ $time }}" x-bind:selected="shiftData && shiftData.end_time == '{{ $time }}:00' ? 'selected' : ''">
                                        {{ $time }}
                                    </option>
                                @endfor
                            @endfor
                        </select>
                    </div>


                    <button x-on:click.prevent="(async () => {
                        if(window.formEditIsValid()) {
                            employeeAvailable = await window.ifUserIsAvailable($refs.editId_user.value, {{ $branch->id }}, $refs.editDate.value, $refs.editStart_time.value, $refs.editEnd_time.value, shiftData ? shiftData.id : '');
                            if (employeeAvailable) { $refs.formEdit.submit(); }
                        }
                    })()"
                    type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-[150px] mb-2">
                        Enregistrer
                    </button>

                </form>

                <form id="deleteForm" method="POST" action="{{ route('shifts.destroy') }}" class="">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id_shift" x-bind:value="shiftData ? shiftData.id : ''">
                    <input type="hidden" name="id_branch" value="{{ $branch->id }}">
                    <input type="hidden" name="date" x-bind:value="shiftData ? shiftData.date : ''">
                    <button
                        type="button"
                        x-on:click="if (confirm('Êtes-vous sûr de vouloir supprimer ce shift ?')) { document.getElementById('deleteForm').submit(); }"
                        class="bg-red-500 text-white px-4 py-2 rounded w-[150px] mb-2">
                        Supprimer
                    </button>
                </form>
                <button type="button" x-on:click="openEdit = false" class="bg-gray-500 text-white px-4 py-2 rounded w-[150px] mb-2">
                    Annuler
                </button>
            </div>
        </div>

        <!-- Add shift -->
        <div
            x-cloak
            x-on:click.away="openAdd = false"
            x-show="openAdd"
            x-transition.opacity
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40">

            <div
                x-on:click.away="openAdd = false"
                x-show="openAdd"
                x-transition.opacity
                class="bg-white p-6 rounded-lg shadow-lg w-1/3 z-50 h-auto overflow-y-auto">
                <h2 class="text-xl font-bold mb-4">Ajout d'un quart</h2>
                <form class="" id="formAdd" action="{{ route('shifts.store') }}" method="POST" x-ref="formAdd">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="id_branch" value="{{ $branch->id }}">
                    <input type="hidden" name="date" value="{{ $week }}">
                    <div class="mb-4">
                        <label for="addId_user" class="block text-sm font-medium text-gray-700">Employé:</label>
                        <select id="addId_user" name="id_user" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            x-ref="addId_user"
                            x-on:change="employeeAvailable = true">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->first_name . " " . $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="addEmployeeAvailableMessage" class="hidden mt-4" x-show="!employeeAvailable">
                            <p class="text-red-500">L'employé n'est pas disponible pour ce quart.</p>
                            <p class="reasonText text-red-500">Raison: </p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="addDate" class="block text-sm font-medium text-gray-700">Date:</label>
                        <input type="date" id="addDate" name="date" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            x-ref="addDate"
                            x-on:change="$event.target.classList.remove('border-red-500'); employeeAvailable = true">
                    </div>
                    <div class="mb-4">
                        <label for="addStart_time" class="block text-sm font-medium text-gray-700">Heure de début:</label>
                        <select id="addStart_time" name="start_time" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            x-ref="addStart_time"
                            x-on:change="$event.target.classList.remove('border-red-500');employeeAvailable = true">
                            <option value="" selected disabled>Sélectionner une heure de début</option>
                            @for ($hour = 6; $hour < 24; $hour++)
                                @for ($minute = 0; $minute < 60; $minute += 30)
                                    @php
                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                    @endphp
                                    <option value="{{ $time }}">
                                        {{ $time }}
                                    </option>
                                @endfor
                            @endfor
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="addEnd_time" class="block text-sm font-medium text-gray-700">Heure de fin:</label>
                        <select id="addEnd_time" name="end_time" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                        x-ref="addEnd_time"
                        x-on:change="$event.target.classList.remove('border-red-500');employeeAvailable = true">
                        <option value="" selected disabled>Sélectionner une heure de fin</option>
                            @for ($hour = 6; $hour < 24; $hour++)
                                @for ($minute = 0; $minute < 60; $minute += 30)
                                    @php
                                        $time = sprintf('%02d:%02d', $hour, $minute);
                                    @endphp
                                    <option value="{{ $time }}">
                                        {{ $time }}
                                    </option>
                                @endfor
                            @endfor
                        </select>
                    </div>

                    <button x-on:click.prevent="(async () => { if(window.formAddIsValid()) { employeeAvailable = await window.ifUserIsAvailable($refs.addId_user.value, {{ $branch->id }}, $refs.addDate.value, $refs.addStart_time.value, $refs.addEnd_time.value); if (employeeAvailable) { $refs.formAdd.submit(); } } })()" type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-[150px] mb-2">
                        Ajouter
                    </button>

                </form>

                <button type="button" x-on:click="openAdd = false" class="bg-gray-500 text-white px-4 py-2 rounded w-[150px] mb-2">
                    Annuler
                </button>
            </div>
        </div>

        </div>
    </div>



</x-app-layout>
