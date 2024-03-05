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
        <div class="max-w-screen-md mx-auto ">
            <div class="flex flex-wrap px-4 py-2 flex-container bg-white shadow-md rounded-md">
                <div class="w-3/5 max-[480px]:w-full">
                    <div class="flex justify-center overflow-hidden">
                        <img src=""alt="" class="rounded-md" id="fotodetail">
                        <!--foto-->
                    </div>
                    <br>
                    <div class="flex flex-col px-2">
                        <div class="font-semibold" id="judulfoto">
                            judulfoto
                        </div>
                        <div>
                            <small class="text-abuabu" id="deskripsifoto">deskripsifoto</small>
                        </div>
                        <div class="text-blue-500 text-sm" id="album">
                            <!--album-->
                        </div>
                    </div>
                </div>
                <br>
                <div class="w-2/5 max-[480px]:w-full ">
                    <div class="ml-2 px-4 py-2 flex flex-wrap items-center justify-between border rounded-md ">
                        <div class="flex flex-row items-center ">
                            <div>
                                <img src="" alt="User Avatar" class="w-10 h-10 rounded-full" id="fotoprofil">
                            </div>
                            <div class="ml-2 flex flex-col">
                                <a href="/profil_public" class="text-md font-semibold" id="username"></a>
                                <small class="text-xs text-abuabu" id="jumlahpengikut"></small>
                            </div>
                        </div>
                        <div id="tombolfollow">
                            <button class="px-4 rounded-full bg-black text-white"></button>
                        </div>
                    </div>
                    <div class="font-semibold ml-2 mt-[10px] mb-[15px]">
                        @csrf
                        Komentar
                    </div>
                    <div class="ml-2 px-4 py-2 relative flex flex-col overflow-y-auto h-[200px] scrollbar-hidden border rounded-md" id="komentar">
                        <!--komentar-->
                    </div>
                    <div class="flex gap-2 mt-2">
                        <div class="w-3/4">
                            <input type="text" name="textkomentar" id="" placeholder="Tulis Comment..."
                                class="ml-2 w-full px-2 py-1 rounded-full border-slate-500">
                        </div>
                        <button class="ml-2 px-6 rounded-full bg-black" onclick="kirimkomentar()"><span
                                class="text-white bi bi-send"></span></button>
                    </div>
                </div>
            </div>
            <section class="mt-20">
                @csrf
                <div class="max-w-screen-md mx-auto">
                    <div class="flex flex-wrap items-center flex-container gap-2 " id="exploredatapostingan">
                        
                    </div>
                </div>
                <div class="mb-28"></div>
            </section>
        </div>
    </section>
    <script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
@endsection
@push('footerjsexternal')
    <script src="/javascript/exploredetail.js"></script>
@endpush
