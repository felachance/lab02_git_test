@if (Session::has('info'))
 <div role="alert">
 <div class="bg-blue-500 text-white font-bold rounded-t px-4 py-2">Information</div>
 <div class="border border-t-0 border-blue-400 rounded-b bg-blue-100 px-4 py-3 text-blue-700">
 <p>{{ Session::get('info') }}</p>
 </div>
 </div>
@elseif (Session::has('succes'))
 <div role="alert">
 <div class="bg-green-500 text-white font-bold rounded-t px-4 py-2">Succès</div>
 <div class="border border-t-0 border-green-400 rounded-b bg-green-100 px-4 py-3 text-green-700">
 <p>{{ Session::get('succes') }}</p>
 </div>
 </div>
@elseif (Session::has('alerte'))
 <div role="alert">
 <div class="bg-yellow-500 text-black font-bold rounded-t px-4 py-2">Avertissement</div>
 <div class="border border-t-0 border-yellow-400 rounded-b bg-yellow-100 px-4 py-3 text-yellow-700">
 <p>{{ Session::get('alerte') }}</p>
 </div>
 </div>
@elseif (Session::has('erreur'))
 <div role="alert">
 <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">Erreur</div>
 <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
 <p>{{ Session::get('erreur') }}</p>
 </div>
 </div>
@endif
@if ($errors->any())
 <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
 <p>Veuillez corriger l'erreur ou les erreurs suivante(s) :</p>
 <ul class="list-disc list-inside pl-2">
 @foreach ($errors->all() as $error)
 <li>{{ $error }}</li>
 @endforeach
 </ul>
 </div>
@endif