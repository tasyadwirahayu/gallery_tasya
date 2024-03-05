<!--navbar-->
@extends('layout.master')
@push('cssjsexternal')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush
<!--/navbar-->
@section('content')
    <section class="mt-32">
        <div class="items-center max-w-screen-md mx-auto ">
            <h3 class="text-5xl text-center text-white">  DAISY</h3>
        </div>
        <div class="flex flex-col items-center max-w-screen-md px-2 mx-auto mt-4">
            <div class="flex justify-end w-[100px] h-[100px] rounded-full mx-auto ">
                <img src="/pic/{{ old('foto_profil', Auth::User()->foto_profil) }}" alt="" class="w-20 h-20 rounded-full">
                
            </div>
            <h3 class="text-xl font-semibold text-white">
                {{ old('username', Auth::User()->username) }}
            </h3>
            <div class="mb-4"></div>
            <small class="text-center text-xs text-white">{{ old('bio', Auth::User()->bio) }}</small>
            <div class="flex ml-[20px] mt-4">
                <a href="/profile" class="bg-black text-white border border-gray-300 focus:outline-none font-medium rounded-lg text-xs px-3 py-1 me-1 mb-2"
                    type="button">
                    edit profile
                </a>
                <a href="/password&username" class="bg-black text-white border border-gray-300 focus:outline-none font-medium rounded-lg text-xs px-3 py-1 me-2 mb-2"
                    type="submit "> 
                    edit password
                </a>
            </div>
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap mb-px text-sm font-medium text-center" id="default-tab"
                    data-tabs-toggle="#default-tab-content" role="tablist">
                    <!--Album-->
                    
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="postingan-tab" data-tabs-target="#postingan"
                            type="button" role="tab" aria-controls="postingan" aria-selected="false">Postingan</button>
                    </li>
                    <!--Postingan-->
                    <li class="me-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                            id="album-tab" data-tabs-target="#album" type="button" role="tab"
                            aria-controls="album" aria-selected="false">Album</button>
                    </li>
                </ul>
            </div>
        </div>
        <!--album-->
        <section class="mt-2">
            <div class="hidden" id="album" role="tabpanel" aria-roledescription="album-tab">
                <div class="max-w-screen-md mx-auto">  
                    @csrf 
                    <div class="flex items-center flex-container gap-2" id="album-foto">
                        <section class="flex flex-wrap">
                            @foreach ($tampilAlbum as $album)
                            <a href="{{ route('dalemalbum', $album->id) }}">
                            <div class="max-w-screen-md mx-auto">
                                <div class="flex items-center flex-container gap-2">
                                    <div class="flex mt-2 bg-white shadow-xl rounded-lg w-[250px] px-3">
                                        <div class="flex flex-col px-2">
                                            <div class="w-[200px] h-[192px] overflow-hidden rounded-sm">
                                                <img src="/assets/folder.png" alt="" class="">
                                            </div>
                                            <div class="flex flex-wrap items-center justify-center">
                                                <div>
                                                    <div class="flex flex-col">
                                                        <div class="font-medium ">
                                                            {{ $album->Nama_Album }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                            @endforeach
                        </section>
                    </div>
                </div>
            </div>
        </section>

        <!--postingan-->
        <section class="mt-0">
            <div class="hidden" id="postingan" role="tabpanel" aria-roledescription="postingan-tab">
                <div class="max-w-screen-md mx-auto">
                    @csrf
                    <div class="flex flex-wrap items-center flex-container gap-2 " id="postingan-foto">
                       {{-- postingan --}}
                       
                    </div>
                </div>
            </div>
            <div class="mb-28"></div>
        </section>
        </div>
        </div>
    </section>
    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
@endsection
@push('footerjsexternal')
    <script src="/javascript/album.js"></script>
@endpush
