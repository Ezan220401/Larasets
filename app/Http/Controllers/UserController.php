<?php

namespace App\Http\Controllers;

use App\Mail\NotifMail;
use App\Mail\passwordMail;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PHPUnit\TextUI\XmlConfiguration\Group;
use App\Notifications\PasswordResetNotification;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin(Request $request)
    {
        $groups = UserGroup::all();
        $itemsPerPage = $request->input('items_per_page', 10); // Default 10

        // Mengambil data users yang tidak termasuk dalam group_id 5 (Dosen) dan 7 (Mahasiswa)
        $query = User::whereNotIn('group_id', [5, 7]);

        // Jika ada filter pencarian
        if ($request->has('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('user_name', 'like', '%' . $request->search . '%')
                    ->orWhere('user_number_id', 'like', '%' . $request->search . '%')
                    ->orWhere('user_phone', 'like', '%' . $request->search . '%');
            });
        }

        // Jika ada filter kategori
        if ($request->has('categories') && $request->categories != 'all') {
            $query->where('group_id', $request->categories);
        }

        // Memanggil with sebelum paginate
        $users = $query->with('group')->orderBy('user_name', 'desc')->paginate($itemsPerPage);
        return view('users.index_admin', compact('users', 'groups'));
    }



    public function student(Request $request)
    {
         $groups = UserGroup::all();
        $itemsPerPage = $request->input('items_per_page', 10); // Default 10

        // Mengambil data users yang termasuk dalam group_id 5 (Dosen) dan 7 (Mahasiswa)
        $query = User::whereIn('group_id', [5, 7]);

        // Jika ada filter pencarian
        if ($request->has('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('user_name', 'like', '%' . $request->search . '%')
                    ->orWhere('user_number_id', 'like', '%' . $request->search . '%')
                    ->orWhere('user_phone', 'like', '%' . $request->search . '%');
            });
        }

        // Jika ada filter kategori
        if ($request->has('categories') && $request->categories != 'all') {
            $query->where('group_id', $request->categories);
        }

        // Memanggil with sebelum paginate
        $users = $query->with('group')->orderBy('user_name', 'desc')->paginate($itemsPerPage);
        return view('users.index_student', compact('users', 'groups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = UserGroup::all();
        return view('users.createUser', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'user_name' => 'required|string|min:3',
            'user_phone' => 'required|string|min:10',
            'user_number_id' => 'required|string|min:8',
            'user_photo' => 'required|image|file|max:5110|mimes:jpeg,png,jpg',
            'user_email' => 'required|email'
        ]);  

        $userId = Auth::user()->group_id;
        if ($userId != 2) {
            abort(403, 'Forbidden');
        }

        $image = $request->file('user_photo');
        $imagePath = $image->store('user_images', 'public');
        
        // Membuat OTP
        $password = random_bytes(5);
        $otp = random_int(10000, 99999);

        $userName = Auth::user()->user_name;
        $userGroup = Auth::user()->group->group_name;
        $created_by = $userName . ' selaku ' . $userGroup;

        // Cek email pada data
        $sameEmail = User::where('user_email', $request['user_email'])->exists();
        if ($sameEmail) {
            return redirect()->back()->withInput()->with(['error' => 'Email sudah terdaftar']);
        }

        // Cek Id pada data
        $sameID = User::where('user_number_id', $request['user_number_id'])->exists();
        if ($sameID) {
            return redirect()->back()->withInput()->with(['error' => 'NPM sudah terdaftar']);
        }

        // Mencari nama grup berdasarkan group_id
        $group = UserGroup::where('group_id', $request->group)->first();
        if (!$group) {
            return redirect()->back()->withErrors(['group' => 'Group tidak ditemukan']);
        }
        $groupName = $group->group_name;

        // Membuat pengguna
        $user = User::create([
            'user_name' => $request['user_name'],
            'user_phone' => $request['user_phone'],
            'user_number_id' => $request['user_number_id'],
            'user_password' => bcrypt($password), 
            'user_email' => $request['user_email'],
            'user_photo' => $imagePath,
            'group_id' => $request->group,
            'otp' => $otp,
            'created_by' =>  $created_by
        ]);

        //Kirim perintah ganti password dengan email
        $mailData =[
            'otp' => $otp,
            'title' => 'Perintah untuk mengganti password',
            'name' => $request['user_name'],
            'group_name' => $groupName
        ];

        try {
            Mail::to($user->user_email)->send(new NotifMail($mailData));
            return redirect()->back()->with('success', 'Email pembuatan password dikirim kepada pengguna, tambah data pengguna lagi?');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email. Silakan coba lagi.');
        }

        if ($user) {
            if ($request->group == 7 || $request->group == 5) {
                return redirect()->to('/users/students')->with([
                    'success' => 'Data pengguna baru berhasil dibuat'
                ]);
            } else {
                return redirect()->to('/users/admins')->with([
                    'success' => 'Data pengguna baru berhasil dibuat'
                ]);
            }
        } else {
            return redirect()->back()->with([
                'error' => 'Terjadi masalah. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $user = User::findOrFail($user_id);
        $groups = UserGroup::all();

        return view('users.view', compact('user', 'groups'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);
        $userName = $user->user_name;
        $userId = $user->user_id;
        $groups = UserGroup::all();

        return view('users.updateUser', compact('user', 'groups', 'userName', 'userId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $user_id)
    {
        $this->validate( $request, [
            'user_name' => 'required|string|min:3',
            'user_number_id' => 'required|integer|min:10',
            'user_phone' => 'required|integer|min:8',
            'user_email' => 'required|string|email|',
            'user_photo' => 'image|file|max:5110|mimes:jpeg,png,jpg',
        ]);

        $userName = Auth::user()->user_name;
        $userGroup = Auth::user()->group_id;
        $user = User::findOrFail($user_id);

        if($userName != $user->user_name){
            abort(403, 'Forbidden');
        }

        // memperbarui data yang diminta
        $image = $request->file('user_photo');
        if($image != null){
            $imagePath = $image->store('user_images', 'public');
            $user->update([
                'user_photo' => $imagePath,
            ]);
        }

        
        $updated_by = $userName . ' selaku ' . $userGroup;

        $userName = $request->user_name;
        if($userName != null){
            $user->update([
                'user_phone' => $request->user_phone,
                'user_name' => $request->user_name,
                'user_email' => $request->user_email,
                'updated_by' => $updated_by
            ]);
        }

        if ($user) {
            return redirect()
                ->route('home.index')
                ->with([
                    'success' => 'Datamu berhasil diubah'
                ]);
        } else {
            return redirect()
                ->back()
                ->with([
                    'error' => 'Ada masalah, silahkan coba lagi'
                ]);
        }
    }

    public function requestPasswordReset(Request $request)
    {
        // Validasi email
        $request->validate(['user_email' => 'required|email']);

        // Mencari user berdasarkan email
        $user = User::where('user_email', $request->user_email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['user_email' => 'Email tidak ditemukan']);
        }

        // Generate OTP
        $otp = random_int(10000, 99999);

        // Simpan OTP ke database
        $user->otp = $otp;
        $user->save();

        // Kirim email berisi OTP
        $mailData =[
            'title' => 'Permintaan mengganti password',
            'name' => $user->user_name,
            'otp' => $otp
        ];

        try {
            Mail::to($user->user_email)->send(new PasswordMail($mailData));
            return redirect()->back()->with('success', 'Pengguna dapat mengganti password, silahkan cek email anda.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim email. Silakan coba lagi.');
        }
    }

    public function resetPassword(Request $request)
    {
        // Validasi input
        $this->validate($request, [
            'email' => 'required|email:dns',
            'otp' => 'required|integer',
            'password' => ['required', 'string', 'min:8', 'regex:/[a-z]/', 'regex:/[0-9]/', 'regex:/[\W_]/'],
            'password2' => 'required|same:password'
        ]);

        // Mencari user berdasarkan email
        $user = User::where('user_email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email tidak terdaftar, silakan hubungi Akademik']);
        }

        // Verifikasi OTP
        if ($request->otp === $user->otp) {
            return redirect()->back()->withErrors(['otp' => 'OTP anda invalid']);
        }

        // Mengupdate password dan menghapus OTP
        $user->user_password = bcrypt($request->password);
        $user->otp = null;
        $user->save();

        return redirect()->route('login')->with('status', 'Password berhasil dihapus');
        if ($user) {
            return redirect()
                ->route('login')
                ->with([
                    'success' => 'Password berhasil diganti'
                ]);
        } else {
            return redirect()
                ->back()
                ->with([
                    'error' => 'Ada masalah, silahkan coba lagi'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $user_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);

        if($user->user_photo){
            Storage::delete($user->user_photo);
        }

        $deleted = $user->delete();

    if ($deleted) {
        return redirect()->back();
    } else {
        return redirect()->back()
                ->withInput()
                ->with([
                    'error' => 'Ada masalah, silahkan coba lagi nanti.'
                ]);
        }
    }

    public function information()
    {
        $groups = UserGroup::all();
        $users = User::all();

        return view('users.categories', compact('groups', 'users'));
    }

    public function category_store(Request $request)
    {
        $this->validate( $request, [
            'group_name' => 'required|string|min:3',
            'group_desc' => 'required|string|min:5',
        ]);

        $userId = Auth::user()->group_id;
        if ($userId != 2) {
            abort(403, 'Forbidden');
        }

        // Cek apakah nama kategori sudah ada, kecuali untuk kategori yang sedang diperbarui
        $check_group_name = UserGroup::where('group_name', $request->group_name)
            ->first();
            
        if ($check_group_name) {
            return redirect()->back()->withInput()->with([
                'error' => 'Nama kategori sudah ada'
            ]);
        }

        $userName = Auth::user()->user_name;
        $userGroup = Auth::user()->group->group_name;
        $created_by = $userName . ' selaku ' . $userGroup;

        $group_category = UserGroup::create([
            'group_name' => $request->group_name,
            'group_desc' => $request->group_desc,
            'created_by' => $created_by
        ]);

        if ($group_category) {
            return redirect()
                ->route('user.information')
                ->with([
                    'success' => 'Kategori Berhasil Ditambahkan'
                ]);
        } else {
            return redirect()
                ->back()
                ->with([
                    'error' => 'Ada masalah, silahkan coba lagi'
                ]);
        }
    }
    
    public function category_edit($group_id)
    {
        $category = UserGroup::findOrFail($group_id);
        return view('categories.updateUserCategory', compact('category'));
    }

    public function category_update(Request $request, $group_id)
    {
        
        $this->validate( $request, [
            'group_name' => 'required|string|min:3',
            'group_desc' => 'required|string|min:5',
        ]);
        
        $userName = Auth::user()->user_name;
        $userId = Auth::user()->group_id;

        if ($userId != 2) {
            abort(403, 'Forbidden');
        }

        $updated_by = $userName . ' selaku ' . $userId;

        $group_category = UserGroup::findOrFail($group_id);

        $group_category -> update([
            'group_name' => $request->group_name,
            'group_desc' => $request->group_desc,
            'updated_by' => $updated_by
        ]);

        if ($group_category) {
            return redirect()
                ->route('user.information')
                ->with([
                    'success' => 'Kategori Berhasil Diperbarui'
                ]);
        } else {
            return redirect()
                ->back()
                ->with([
                    'error' => 'Ada masalah, silahkan coba lagi'
                ]);
        }
    }

    public function category_destroy($group_id)
    {
        $group = UserGroup::findOrFail($group_id);
        $group->delete();

        if ($group) {
            return redirect()
                ->route('user.information')
                ->with([
                    'success' => 'Kategori pengguna berhasil dihapus'
                ]);
        } else {
            return redirect()
                ->route('user.information')
                ->with([
                    'error' => 'Ada masalah, silahkan coba lagi nanti.'
                ]);
        }
    }
}
