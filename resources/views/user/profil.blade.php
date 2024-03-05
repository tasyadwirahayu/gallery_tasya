<!--navbar-->
@extends('layout.master')
<!--/navbar-->
@section('content')
    <section class="mt-32">
        <form action="/ubahprofil" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-wrap justify-between flex-container">
                <div class="flex flex-col items-center w-2/6 bg-white rounded-md shadow-md max-[480px]:w-full">
                    <img src="/pic/{{ old('foto_profil', Auth::User()->foto_profil) }}" alt=""
                        class="rounded-full w-36 h-36">
                    <input type="file" name="file" class="items-center w-48 h-10 mt-4 border rounded-md">
                    <button type="submit" class="w-48 py-2 mt-4 mb-4 text-white rounded-md bg-blue-500">Ubah Photo</button>
                </div>
        </form>
        <div class="w-3/5 max-[480px]:w-full">
            <div class="bg-white rounded-md shadow-md">
                <form action="/updateprofile" method="POST">
                    @csrf
                    <div class="flex flex-col px-4 py-4 ">
                        <h5 class="text-3xl text-center font-kaushan">Your Profile</h5>
                        <h5>Username</h5>
                        <input type="text" name="username" value="{{ $dataprofile->username }}" class="py-1 rounded-md">
                        <h5>Nama Lengkap</h5>
                        <input type="text" name="nama_lengkap" value="{{ $dataprofile->nama_lengkap }}"
                            class="py-1 rounded-md">
                        <h5>Email</h5>
                        <input type="text" name="email" value="{{ $dataprofile->email }}" class="py-1 rounded-md">
                        <h5>No Telepon</h5>
                        <input type="number" name="no_telephone" value="{{ $dataprofile->no_telephone }}"
                            class="py-1 rounded-md">
                        <h5>Jenis Kelamin</h5>
                        <select name="jenis_kelamin" id="" class="py-1 rounded-md">
                            @foreach (['Laki-laki','Perempuan'] as $option)
                                <option value="{{ $option }}" {{$option==$dataprofile->jenis_kelamin ? 'selected':''}}>{{ $option }}</option>
                            @endforeach
                        </select>
                        <h5>Alamat</h5>
                        <input type="text" name="alamat" value="{{ $dataprofile->alamat }}" class="py-2 rounded-md">
                        <h5>Bio</h5>
                        <input type="text" name="bio" value="{{ $dataprofile->bio }}" class="py-2 rounded-md">
                        <button type="submit" class="py-2 mt-4 text-white rounded-md bg-blue-500 hover:bg-blue-600">Ubah Profil</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
        <div class="mb-28"></div>
    </section>
    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
    <script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
    @include('sweetalert::alert') 
@endsection

