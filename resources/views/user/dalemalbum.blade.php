@extends('layout.master')
@section('content')

<section class="flex flex-wrap mt-32">
    @foreach ($album->foto as  $foto)
    <div class="max-w-screen-md mx-auto">
        <div class="flex items-center flex-container">
            <div class="flex mt-4 bg-white shadow-xl ">
                <div class="flex flex-col px-2">
                        <div class="w-[363px] h-[180px] overflow-hidden">
                            <img src="/postingan/{{ $foto->lokasi_file }}" alt="">
                        </div>
                    <div class="flex items-center justify-between px-2 mt-2">
                        <div>
                            <div class="flex flex-col">
                                <div class="font-medium ">
                                    {{ $foto->judul_foto }}
                                </div>
                                <div class="text-xs text-gray-600">
                                    {{ $foto->deksripsi_foto }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</section>

@endsection