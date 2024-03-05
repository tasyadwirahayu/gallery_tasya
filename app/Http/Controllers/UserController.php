<?php

namespace App\Http\Controllers;
                
use App\Models\foto;
use App\Models\User;
use App\Models\album;
use App\Models\folowers;
use App\Models\likefoto;
use App\Models\komenfoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Testing\Fluent\Concerns\Has;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //proses register
    public function register(Request $request)
    {
        $messages = [
            'username' => 'Username sudah terdaftar',
            'email' => 'Email sudah terdaftar',
            'password' => 'Minimal password ada 8'
        ];
        //validasi
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:8',
            'email' => 'required|unique:users,email',
        ]);
        //simpan
        $dataStore = [
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email' => $request->email,
        ];
        User::create($dataStore);
        Alert::success('Registrasi berhasil', 'Silahkan melakukan login');
        return redirect('/login'); 
    }
        //log in
    public function ceklogin (Request $request){
        //validate
        $request->validate([
            'email' => ['required', 'email'],
            'password'  => ['required'],
        ]);
        //proses log in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            if (auth()->user()->role == 'user') {
                Alert::success('Login berhasil!', 'Selamat datang di halaman user');
                return redirect('explore');
            } else {
                Alert::success('Login berhasil!', 'Selamat datang di halaman dashboard');
                return redirect('beranda');
            }
        } else {
            Alert::error('Gagal Login', 'Email atau password salah');
            return redirect('login');
        }
    }
    //logout
    public function logout(Request $request){
        $request->session()->invalidate();
        $request->session()->regenerate();
        Alert::success('Berhasil Keluar', 'Anda telah berhasil keluar dari aplikasi ini');
        return redirect('/');
    }
    //upload foto
    public function upload_foto(Request $request)
    {
        $namafile   = pathinfo($request->file, PATHINFO_FILENAME);
        $extensi    = $request->file->getClientOriginalExtension();
        $namafoto   = 'postingan' . time() . '.' . $extensi;
        $request->file->move('postingan', $namafoto);
        //simpan
        $datasimpan = [
            'users_id' => auth()->User()->id,
            'judul_foto' => $request->judul_foto,
            'deksripsi_foto' => $request->deksripsi_foto,
            'lokasi_file'   => $namafoto,
            'album_id' => $request->album,
           
        ];
        foto::create($datasimpan);
        Alert::success('Berhasil Upload', 'Anda telah berhasil upload foto');
        return redirect('/explore');
    }
    //getDataExplore
    public function getdata(Request $request){
        if ($request->cari !== 'null'){
            $explore = foto::with(['likefoto','album','users'])->where ('judul_foto','like','%'.$request->cari.'%')-> orderBy('id','DESC')->withCount(['likefoto','komenfoto'])->paginate();
        } else {
            $explore = foto::with(['likefoto','album','users'])->orderBy('id','DESC')->withCount(['likefoto','komenfoto'])->paginate();
        }
        return response()->json([
            'data' => $explore,
            'statuscode' => 200,
            'idUser'    => auth()->user()->id
        ]);
    }
    //likefoto
    public function likesfoto(Request $request)
    {
        try {
            $request->validate([
                'idfoto' => 'required'
            ]);
            $existingLike = likefoto::where('foto_id', $request->idfoto)->where('users_id', auth()->user()->id)->first();
            if(!$existingLike){
                $dataSimpan = [
                    'foto_id'   => $request->idfoto,
                    'users_id'   => auth()->user()->id
                ];
                likefoto::create($dataSimpan);
            } else {
                likefoto::where('foto_id', $request->idfoto)->where('users_id', auth()->user()->id)->delete();
            }

            return response()->json('Data berhasil di simpan ', 200);
        } catch (\Throwable $th) {
            return response()->json('Something want wrong', 500,);
        }
    }
    //explore
    public function explore()
    {
        return view('user.explore');
    }
    //tambah album
    public function tambahalbum(Request $request)
    {
        //simpan
        $tambahalbum = [
            'users_id' => auth()->User()->id,
            'Nama_Album' => $request->Nama_Album,
            'deskripsi' => $request->deskripsi,
        ];
        album::create($tambahalbum);
        Alert::success('Album berhasil di tambah');
        return redirect('/upload');
    }
    //getDataPostingansemua
    public function getdatapostingan(Request $request){
        $postinganuserid = auth()->user()->id;
        $explore = foto::with(['likefoto','album','users'])->orderBy('id','DESC')->withCount(['likefoto','komenfoto'])->whereHas('users', function($query) use($postinganuserid){ $query->where('users_id', $postinganuserid);})->paginate(4);
        return response()->json([
            'data' => $explore,
            'statuscode' => 200,
            'idUser'    => auth()->user()->id
        ]);
    }
    //getDataAlbum
    public function getdataalbum(Request $request){
        $postinganuserid = auth()->user()->id;
        $explore = foto::with(['likefoto','album','users'])->orderBy('id','DESC')->withCount(['likefoto','komenfoto'])->whereHas('users', function($query) use($postinganuserid){ $query->where('users_id', $postinganuserid);})->where('album_id','!=',null)->paginate();
        return response()->json([
            'data' => $explore,
            'statuscode' => 200,
            'idUser'    => auth()->user()->id
        ]);
    }

    public function dalemalbum($id)
    {
        $album =album::with('foto')->FindOrFail($id);
       return view('user.dalemalbum' , compact('album'));
    }
    public function album(){
        $user = auth()->user();

        $data_album = $user->album;
        $userFollowers = DB::table('folowers')->where('id_following', $user->id)->count();
        $dataFollowCount = DB::table('folowers')->where('users_id', $user->id)->count();

        $tampilUpload = foto::with('album')->orderBy('id','DESC')->where('users_id', auth()->user()->id)->get();
        $tampilAlbum = album::with('foto')->orderBy('id','DESC')->where('users_id', auth()->user()->id)->get();
        $profile = User::where('id', auth()->user()->id)->first();
        return view('user.album', compact('tampilUpload', 'tampilAlbum','profile', 'data_album', 'userFollowers', 'dataFollowCount'));
    }
    
    //profil
    public function profil()
    {
        $data = [
            'dataprofile'   => User::where('id', auth()->user()->id)->first()
        ];
        return view('user.profil', $data);
    }
    //updatedataprofile
    public function updatedataprofile(Request $request)
    {
        $dataupdate = [
            'username' =>$request->username,
            'nama_lengkap' =>$request->nama_lengkap,
            'jenis_kelamin' =>$request->jenis_kelamin,
            'no_telephone'  =>$request->no_telephone,
            'alamat'    =>$request->alamat,
            'bio'   =>$request->bio,
        ];
        ////proses update
        User::where('id', auth()->user()->id)->update($dataupdate);
        Alert::success('Profil berhasil di ubah');
        return redirect('/profile');
    }
    //fotoprofil
    public function fotoprofil(Request $request)
    {
        $namafile   = pathinfo($request->file, PATHINFO_FILENAME);
        $extensi    = $request->file->getClientOriginalExtension();
        $namafoto   = 'profile' . time() . '.' . $extensi;
        $request->file->move('pic', $namafoto);
        //data
        $dataupdate = [
            'foto_profil'  =>$namafoto,
        ];
        //proses update
        User::where('id', auth()->user()->id)->update($dataupdate);
        Alert::success('Photo berhasil di ubah');
        return redirect('/profile');
    }
    //upload
    public function upload()
    {
        $data_album = album::with('users')->orderBy('id','DESC')->where('users_id', auth()->user()->id)->get();
        return view('user.upload',compact('data_album'));

    }
    //about
    public function about()
    {
        return view('user.about');

    }
    //explore detail
    public function explore_detail($id)
    {
        return view('user.explore-detail');
    }
    //Exploredatadetail
    public function getdatadetail(Request $request, $id){
        $dataDetailFoto     = foto::with(['users','album'])->where('id', $id)->firstOrFail();
        $dataJumlahPengikut = DB::table('folowers')->selectRaw('count(id_following) as jmlfolow')->where('id_following', $dataDetailFoto->users->id)->first();
        $dataFollow         = folowers::where('id_following', $dataDetailFoto->users->id)->where('users_id', auth()->user()->id)->first();
        return response()->json([
            'dataDetailFoto'    => $dataDetailFoto,
            'dataJumlahFollow'  => $dataJumlahPengikut,
            'dataUser'          => auth()->user()->id,
            'dataFollow'        => $dataFollow,
        ], 200);
    }
    //datakomentar
    public function ambildatakomentar(Request $request, $id){
        $ambilkomentar = komenfoto::with('users')->orderBy('id','DESC')->where('foto_id', $id)->get();
        return response()->json([
            'data'  => $ambilkomentar,
        ], 200);
    }
    //kirimkomentar
    public function kirimkomentar(Request $request){
        try {
            $request->validate([
                'idfoto'   => 'required',
                'isi_komentar'  => 'required',
            ]);
            $dataStoreKomentar = [
                'users_id'  => auth()->user()->id,
                'foto_id'   => $request->idfoto,
                'isi_komentar'  => $request->isi_komentar,
            ];
            komenfoto::create($dataStoreKomentar);
            return response()->json([
                'data'      => 'Data berhasil di simpan',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json('Data komentar  gagal di simpann', 500);
        }
    }
    //explore edit password&username
    public function edit_password_username()
    {
        return view('user.changepassword');
    }
    public function updatePassword(Request $request)
{
   $request->validate([
       'current_password' => 'required',
       'password' => 'required|min:8',
   ]);

   $user = Auth::user();
//dd($request->current_password);
   if (!Hash::check($request->current_password, $user->password)) {
       return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
    }

    $user->update([
        'password' => bcrypt($request->password),
     ]);
     Alert::success('Berhasil', 'Password berhasil diubah ');
   return redirect()->back();
}


    //explore profil public
    public function profil_public($id)
    {
        $user = User::find($id);
        $userFollowers = DB::table('folowers')->where('id_following', $user->id)->count();
        $dataFollowCount = DB::table('folowers')->where('users_id', $user->id)->count();

        return view('user.profil-public',compact('userFollowers','dataFollowCount'),[
            'username' => $user->username,
            'foto_profil' => $user->foto_profil,
            'bio'   => $user->bio,
            'user_id'   => $id,
        ]);
    }

    public function getdatapin(Request $request, $id){
        $dataUser = User::where('id', $id)->first();
        $dataJumlahFollower = DB::select('SELECT COUNT(users_id) as jmlfollower FROM folowers where id_following ='.$id);
        $dataJumlahFollow   = DB::select('SELECT COUNT(id_following) as jmlfollow FROM folowers where users_id ='.$id);
        $dataFollow         = folowers::where('id_following', $id)->where('users_id', auth()->user()->id)->first();
        return response()->json([
            'dataUser'         => $dataUser,
            'jumlahFollower'   => $dataJumlahFollower,
            'jumlahFollow'     => $dataJumlahFollow,
            'dataUserActive'   => auth()->user()->id,
            'dataFollow'       => $dataFollow
        ], 200);
    }
    public function getdatapostinganbaru(Request $request, $id){
        $explore = foto::with(['likefoto','album','user'])->withCount(['likefoto','komentar'])->where('users_id', $id)->paginate();
        return response()->json([
            'data' => $explore,
            'statuscode' => 200,
            'idUser'    => auth()->user()->id
        ]);
    }
    //getDataPublic
    public function getdatapublic(Request $request,$id){
        $publicuserid = auth()->user()->id;
        $explore = foto::with(['likefoto','album','users'])->withCount(['likefoto','komenfoto'])->where('users_id', $id)->paginate(4);
        return response()->json([
            'data' => $explore,
            'statuscode' => 200,
            'idUser'    => auth()->user()->id
        ]);
    }
    //Follow Unfollow
    public function ikuti(Request $request){
        try {
            $request->validate([
                'idfollow'      => 'required'
            ]);

            $existingFollow = folowers::where('users_id', auth()->user()->id)->where('id_following', $request->idfollow)->first();
            if(!$existingFollow){
                $dataSimpan = [
                    'users_id'      => auth()->user()->id,
                    'id_following'     => $request->idfollow,
                ];
                folowers::create($dataSimpan);
            } else {
                folowers::where('users_id', auth()->user()->id)->where('id_following', $request->idfollow)->delete();
            }
            return response()->json('Data berhasil di eksekusi', 200);
        } catch (\Throwable $th) {
            return response()->json('Something went wrong', 500);
        }
    }

    public function deletefoto(Request $request, $id)
    {
        try {
            // Find the foto record
            $foto = foto::findOrFail($id);

            // Delete associated komen and like records
            $foto->komenfoto()->delete();
            $foto->likefoto()->delete();

            // Delete the file associated with the foto
            $filePath = ('postingan/' . $foto->file_name); // Adjust the file path based on your actual structure

            // Check if the file exists
            if (File::exists($filePath)) {
                // Delete the file
                File::delete($filePath);
            }

            // Delete the foto record
            $foto->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus foto dan data terkait.'], 500);
        }
    }
}
