<!--navbar-->
@extends('layout.master')
<!--/navbar-->
@section('content')
<form action="/updatepassword" method="POST">
    @csrf
    <section class="max-w-[500px] mx-auto mt-32">
            <div class="max-[480px]:w-full">
                <div class="items-center max-w-screen-md mx-auto ">
                    <h3 class="text-5xl text-center font-hurricane">  DAISY </h3>
                </div>
                <div class="bg-white rounded-md shadow-md ">
                    <div class="flex flex-col px-4 py-4 ">
                        <h5 class="text-3xl text-center font-kaushan">Edit Password</h5>
                        <h5>Old Password</h5>
                        @error('password')
                            <small class="text-red-600 mt-2 text-sm">{{ $message }}</small>
                        @enderror
                        <input type="password" class="py-1 mt-1 border border-gray-200 rounded-md font-poppins"
                        name="current_password">
                        <h5>New Password</h5>
                        @error('password')
                            <small class="text-red-600 mt-2 text-sm">{{ $message }}</small>
                        @enderror
                        <input type="password" class="py-1 mt-1 border border-gray-200 rounded-md font-poppins"
                            name="password">
                        <h5>Confirm Password</h5>
                        @error('password')
                        <small class="text-red-600 mt-2 text-sm">{{ $message }}</small>
                    @enderror
                    <input type="password" class="py-1 mt-1 border border-gray-200 rounded-md font-poppins"
                        name="password_confirmation">
                    <button type="submit" class="py-2 mt-4 text-white rounded-md bg-blue-500 hover:bg-blue-500">Perbaharui</button>
                    </div>
                </div>
            </div>
    </section>
</form>
    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.js"></script>
    <script src="/node_modules/flowbite/dist/flowbite.min.js"></script>
    @include('sweetalert::alert')
@endsection