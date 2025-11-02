<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use Illuminate\Http\Request;

Auth::routes();
//redirect login
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/user/verify/{token}', 'Auth\RegisterController@verifyUser');
Route::get('/user/resendemail', 'Auth\RegisterController@getResendEmail');
Route::post('/user/resendemail', 'Auth\RegisterController@resendEmail')->name('user.resendemail');
Route::get('/user/changeemail', 'Auth\RegisterController@getChangeEmail');
Route::post('/user/changeemail', 'Auth\RegisterController@changeEmail')->name('user.changeemail');


// Route::group(['prefix' => 'home', 'middleware' => ['auth']], function () {
//     Route::get('/', function () {
//         $data['role'] = \App\RoleUser::whereUserId(Auth::id())->get();
//         return view('home', $data);
//     });
// });

Route::get('/', 'HomeController@index');


Route::post('/upload_image', function (Request $request) {
    if ($request->hasFile('upload')) {
        $file = $request->file('upload'); //SIMPAN SEMENTARA FILENYA KE VARIABLE
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); //KITA GET ORIGINAL NAME-NYA
        //KEMUDIAN GENERATE NAMA YANG BARU KOMBINASI NAMA FILE + TIME
        // $fileName = $fileName . '_' . time() . '.' . $file->getClientOriginalExtension();
        // $file->move(public_path('uploads'), $fileName); //SIMPAN KE DALAM FOLDER PUBLIC/UPLOADS
        $filename = rand(1000, 9999) . $file->getClientOriginalName();
        $file->move(storage_path() . '/app/public/wysiwyg/', $filename);
        $url = url('storage/wysiwyg/' . $filename);

        //KEMUDIAN KITA BUAT RESPONSE KE CKEDITOR
        $ckeditor = $request->input('CKEditorFuncNum');
        // $url = asset('uploads/' . $fileName);
        $msg = 'Image uploaded successfully';
        //DENGNA MENGIRIMKAN INFORMASI URL FILE DAN MESSAGE
        $response = "<script>window.parent.CKEDITOR.tools.callFunction($ckeditor, '$url', '$msg')</script>";

        //SET HEADERNYA
        @header('Content-type: text/html; charset=utf-8');
        return $response;
    }
})->name('image.upload');

// Route untuk user yang admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('/', function () {
        return view('admin');
    })->name('admin');

    Route::get('/users', 'UserController@index');
    Route::get('/users/add', 'UserController@addUser')->name('admin.adduser');
    Route::post('/users/add', 'UserController@create')->name('admin.register');
    Route::get('/users/upd/{id}', 'UserController@updUser')->name('admin.getupduser');
    Route::post('/users/update', 'UserController@update')->name('admin.upduser');
    Route::post('/users/updpassword', 'UserController@updPassword')->name('admin.updpassword');
    Route::post('/users/lsuser', 'UserController@lsuser')->name('admin.lsuser');
    Route::post('/users/rem/{id}', 'UserController@remove')->name('admin.remuser');
    Route::get('/setting/add', 'SettingController@getAdd')->name('admin.getaddsetting');
    Route::get('/setting/upd/{id}', 'SettingController@getUpdate')->name('admin.getupdsetting');
    Route::get('/setting', 'SettingController@list');
    Route::post('/setting/add', 'SettingController@add')->name('admin.addsetting');
    Route::post('/setting/update', 'SettingController@update')->name('admin.updsetting');
    Route::post('/setting/list', 'SettingController@getLsSetting')->name('admin.lssetting');


    //flash-message
    Route::get('/flashmessage', 'FlashMessageController@index')->name('admin.flashmessage.list');
    Route::post('/flashmessage/list', 'FlashMessageController@list')->name('admin.lsflashmessage');
    Route::post('/flashmessage/add', 'FlashMessageController@add')->name('admin.addflashmessage');
    Route::get('/flashmessage/add', 'FlashMessageController@getAdd')->name('admin.getaddflashmessage');
    Route::post('/flashmessage/update', 'FlashMessageController@update')->name('admin.updflashmessage');
    Route::post('/flashmessage/rem/{id}', 'FlashMessageController@remove')->name('admin.remflashmessage');
    Route::get('/flashmessage/upd/{id}', 'FlashMessageController@getUpdate')->name('admin.getupdflashmessage');


    //akun
    Route::get('/akun', 'AkunController@index')->name('admin.akun');
    Route::get('/akun/create', 'AkunController@create')->name('admin.akun.create');
    Route::get('/akun/edit/{id}', 'AkunController@edit')->name('admin.akun.edit');
    Route::post('/akun/list', 'AkunController@list')->name('admin.akun.list');
    Route::post('/akun/store', 'AkunController@store')->name('admin.akun.store');
    Route::post('/akun/update', 'AkunController@update')->name('admin.akun.update');
    Route::post('/akun/remove/{id}', 'AkunController@destroy')->name('admin.akun.remove');

    //jenis transaksi
    Route::get('/jenistransaksi', 'JenisTransaksiController@index')->name('admin.jenistransaksi');
    Route::get('/jenistransaksi/create', 'JenisTransaksiController@create')->name('admin.jenistransaksi.create');
    Route::get('/jenistransaksi/edit/{id}', 'JenisTransaksiController@edit')->name('admin.jenistransaksi.edit');
    Route::post('/jenistransaksi/list', 'JenisTransaksiController@list')->name('admin.jenistransaksi.list');
    Route::post('/jenistransaksi/store', 'JenisTransaksiController@store')->name('admin.jenistransaksi.store');
    Route::post('/jenistransaksi/update', 'JenisTransaksiController@update')->name('admin.jenistransaksi.update');
    Route::post('/jenistransaksi/remove/{id}', 'JenisTransaksiController@destroy')->name('admin.jenistransaksi.remove');

    //jurnal
    Route::get('/jurnal', 'JurnalController@index')->name('admin.jurnal');
    Route::get('/jurnal/create', 'JurnalController@create')->name('admin.jurnal.create');
    Route::get('/jurnal/edit/{id}', 'JurnalController@edit')->name('admin.jurnal.edit');
    Route::post('/jurnal/list', 'JurnalController@list')->name('admin.jurnal.list');
    Route::post('/jurnal/store', 'JurnalController@store')->name('admin.jurnal.store');
    Route::post('/jurnal/update', 'JurnalController@update')->name('admin.jurnal.update');
    Route::post('/jurnal/remove/{id}', 'JurnalController@destroy')->name('admin.jurnal.remove');
    Route::match(['get', 'post'], '/neraca/update', 'JurnalController@saveNeraca')->name('admin.saveneraca');

    //jimpitan
    Route::get('/jimpitan', 'JimpitanController@index')->name('admin.jimpitan');
    Route::get('/jimpitan/create', 'JimpitanController@create')->name('admin.jimpitan.create');
    Route::get('/jimpitan/edit/{id}', 'JimpitanController@edit')->name('admin.jimpitan.edit');
    Route::post('/jimpitan/list', 'JimpitanController@list')->name('admin.jimpitan.list');
    Route::post('/jimpitan/store', 'JimpitanController@store')->name('admin.jimpitan.store');
    Route::post('/jimpitan/update', 'JimpitanController@update')->name('admin.jimpitan.update');
    Route::post('/jimpitan/remove/{id}', 'JimpitanController@destroy')->name('admin.jimpitan.remove');

    // //laporan
    // Route::get('/laporan/bukubesar/{kdakun?}', array('as' => 'admin.laporan.bukubesar', 'uses' => 'LaporanController@lapBukuBesar'));
    // Route::get('/laporan/rekapjimpitan', array('as' => 'admin.laporan.rekapjimpitan', 'uses' => 'LaporanController@lapRekapJimpitan'));
    // Route::get('/laporan/neraca', array('as' => 'admin.laporan.neraca', 'uses' => 'LaporanController@lapNeraca'));
});

// Route untuk user yang member
Route::group(['prefix' => 'anggota', 'middleware' => ['auth', 'role:anggota']], function () {
    Route::get('/', function () {
        return view('anggota');
    })->name('anggota');
});
Route::group(['prefix' => 'pengurus', 'middleware' => ['auth', 'role:pengurus']], function () {
    Route::get('/', function () {
        return view('pengurus');
    })->name('pengurus');
});

Route::group(['prefix' => 'api', 'middleware' => ['auth']], function () {
    Route::get('/infoflash', 'FlashMessageController@showFlashMessage')->name('admin.showflashmessage');
    Route::get('/akun/getnamaakun/{id}', 'AkunController@getNamaAkun')->name('admin.akun.getnamaakun');
    Route::get('/akun/getkodeakun/{id}', 'AkunController@getKodeAkun')->name('admin.akun.getkodeakun');
    Route::get('/jimpitan/getjumlahpeserta/{tahun}/{bulan}/{periode}', 'JimpitanController@getJumlahPeserta')->name('admin.jimpitan.getjumlahpeserta');
});

Route::group(['prefix' => 'laporan', 'middleware' => ['auth']], function () {
    Route::get('/bukubesar/{kdakun?}', array('as' => 'admin.laporan.bukubesar', 'uses' => 'LaporanController@lapBukuBesar'));
    Route::get('/rekapjimpitan', array('as' => 'admin.laporan.rekapjimpitan', 'uses' => 'LaporanController@lapRekapJimpitan'));
    Route::get('/neraca', array('as' => 'admin.laporan.neraca', 'uses' => 'LaporanController@lapNeraca'));
});
