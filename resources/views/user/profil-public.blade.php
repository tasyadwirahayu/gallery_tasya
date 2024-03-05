<!--navbar-->
@extends('layout.master')
@push('cssjsexternal')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush
<!--/navbar-->
@section('content')
    <section>
        @csrf
        <div class="flex flex-col items-center max-w-screen-md px-2 mx-auto mt-32   ">
            <div>
                <img src="/pic/{{$foto_profil}}" alt="" class="w-24 h-24 rounded-full">
            </div>
            <h3 class="text-xl font-semibold text-white">
                {{$username}}
            </h3>
            <small class="text-xs text-white">{{$bio}}</small>
            <div class="flex flex-row mt-3">
                <small class="mr-4 text-white" > {{ $userFollowers }} Pengikut</small>
                <small class="text-white">{{ $dataFollowCount }} Mengikuti</small>
            </div>
            <div id="tombolfollow">
            <button class="px-4 mt-4 text-white bg-black rounded-full">ikuti</button>
        </div>
        </div>
    </section>
    <section class="mt-10">
        <input type="hidden" value="{{$user_id}}" id="input-user_id">
        <div class="max-w-screen-md mx-auto">
            <div class="flex flex-wrap items-center flex-container gap-2" id="publicfoto">

            </div>
        </div>
    </section>
    <script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
@endsection
@push('footerjsexternal')
    <script src="/javascript/profilpublic.js"></script>
@endpush
