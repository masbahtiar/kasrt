<?php

namespace App\Http\Controllers;

use App\KtgUpload;
use App\Sekolah;
use App\Kelengkapan;
use App\Kerusakan;
use App\TahunAjaran;
use Illuminate\Http\Request;
use Image;
use Auth;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request as Req;

class UploadController extends Controller
{
    protected function getUploadUrl($param)
    {
        $tajar = TahunAjaran::getByTahun()->tahun;
        $upload_url = asset('upload') . "/" . $tajar . "/" . $param;
        return $upload_url;
    }
    protected function getDestUploadUrl($param)
    {
        $tajar = TahunAjaran::getByTahun()->tahun;
        $upload_url = storage_path() . "/app/public/" . $tajar . "/" . $param;
        return $upload_url;
    }

    public function lsUpload($id_sekolah)
    {
        $data['sekolah'] = Sekolah::findOrFail($id_sekolah);
        $data['breadcrumbs'] = 'admin.getlsuplaod';
        $data['upload_url'] = $this->getUploadUrl('sekolah');

        Breadcrumbs::for('admin.getlsuplaod', function ($trail) {
            $id = Req::query('id_sekolah', 0);
            $trail->parent('admin.sekolah');
            $trail->push('Upload Data', route('admin.getlsuplaod', $id));
        });
        return view('admin/sekolah/lsupload', $data);
    }
    public function lsUploadVerifikasi($id_sekolah)
    {
        $data['upload_url'] = $this->getUploadUrl('sekolah');
        $data['sekolah'] = Sekolah::findOrFail($id_sekolah);
        return view('verifikasi/lsupload', $data);
    }
    public function lsUploadSekolah()
    {
        $user = Auth::user();
        $data = [];
        $data['upload_url'] = $this->getUploadUrl('sekolah');
        if ($user) {
            $data['sekolah'] = $user->sekolahs()->first();
        }

        if ($data['sekolah']) {
            return view('sekolah/lsupload', $data);
        }
    }

    public function lsKelengkapan($id_sekolah)
    {
        $tajar = TahunAjaran::getByTahun()->id;
        $ktg = KtgUpload::get();
        $klp = Kelengkapan::where('id_sekolah', '=', $id_sekolah)
            ->where('tahun_ajaran_id', '=', $tajar)
            ->get();
        $aktg = array();
        foreach ($ktg as $key => $value) {
            $aktg[$value->id] = array("id_ktgupload" => $value->id, "nm_ktgupload" => $value->nm_ktgupload, "detail" => array());
        }
        foreach ($klp as $key => $value) {
            $aktg[$value->id_ktgupload]["detail"][] = json_decode($value);
        }
        return response()->json($aktg);
    }
    public function lsKtgUpload()
    {
        $data = KtgUpload::get();
        return response()->json($data);
    }

    public function lsKerusakan($ruang_sekolah_id, $item_ruang_id)
    {
        $krs = Kerusakan::where('ruang_sekolah_id', '=', $ruang_sekolah_id)
            ->where('item_ruang_id', '=', $item_ruang_id)
            ->get();
        return response()->json($krs);
    }


    public function upload(Request $request)
    {
        $tajar = TahunAjaran::getByTahun()->id;
        $data = array();
        $maxsize    = 1048576;
        if ($request->hasfile('file')) {
            //. '/upload/sekolah/' . $request->input('id_sekolah')
            //            $dest = resource_path('upload/sekolah') . $request->input('id_sekolah');
            // $dest = storage_path()  . '/app/public/sekolah/' . $request->input('id_sekolah');
            $dest = $this->getDestUploadUrl('sekolah') . '/' . $request->input('id_sekolah');
            if (!is_dir($dest)) {
                // mkdir($dest,0777);
                File::makeDirectory($dest, $mode = 0777, true, true);
                // File::makeDirectory(public_path() . '/' . $dest, 0777, true);
            }
            $time = time();
            $file = $request->file('file');
            if (($file->getSize() > $maxsize)) {
                $data['answer'] = 'File Maksimal 1 MB (' . $file->getSize() . ')';
                $data['success'] = false;
                return response()->json([
                    'message' => $data['answer']
                ], 500);
            } else {
                $name = $time . "_" . $file->getClientOriginalName();
                $thumbname = "thumb_" . $name;
                $img = Image::make($file->getRealPath(), array(
                    'width' => 100,
                    'height' => 100,
                    'grayscale' => false
                ));
                $img->save($dest . '/' . $thumbname);
                $file->move($dest, $name);
                $kelengkapan = Kelengkapan::create([
                    'id_sekolah' => $request->input('id_sekolah'),
                    'id_ktgupload' => $request->input('id_ktgupload'),
                    'image' => $name,
                    'thumb' => $thumbname,
                    'tahun_ajaran_id' => $tajar
                ]);
                $data['answer'] = 'File transfer completed';
                $data['success'] = true;
            }
        } else {
            $data['answer'] = 'No File';
            $data['success'] = false;
        }

        return response()->json($data);
    }
    public function uploadKerusakan(Request $request)
    {
        $data = array();
        $maxsize    = 1048576;
        if ($request->hasfile('file')) {
            //. '/upload/sekolah/' . $request->input('ruang_sekolah_id')
            //            $dest = resource_path('upload/sekolah') . $request->input('ruang_sekolah_id');
            // $dest = storage_path()  . '/app/public/ruang_sekolah/'
            //     . $request->input('ruang_sekolah_id') . '/' . $request->input('item_ruang_id');
            $dest = $this->getDestUploadUrl('ruang_sekolah') . '/'
                . $request->input('ruang_sekolah_id') . '/' . $request->input('item_ruang_id');
            if (!is_dir($dest)) {
                // mkdir($dest,0777);
                File::makeDirectory($dest, $mode = 0777, true, true);
                // File::makeDirectory(public_path() . '/' . $dest, 0777, true);
            }
            $time = time();
            $file = $request->file('file');
            if (($file->getSize() > $maxsize)) {
                $data['answer'] = 'File Maksimal 1 MB (' . $file->getSize() . ')';
                $data['success'] = false;
                return response()->json([
                    'message' => $data['answer']
                ], 500);
            } else {
                $name = $time . "_" . $file->getClientOriginalName();
                $thumbname = "thumb_" . $name;
                $img = Image::make($file->getRealPath(), array(
                    'width' => 100,
                    'height' => 100,
                    'grayscale' => false
                ));
                $img->save($dest . '/' . $thumbname);
                $file->move($dest, $name);
                $kelengkapan = Kerusakan::create([
                    'ruang_sekolah_id' => $request->input('ruang_sekolah_id'),
                    'judul' => $request->input('judul'),
                    'image' => $name,
                    'thumb' => $thumbname,
                    'item_ruang_id' => $request->input('item_ruang_id')
                ]);
                $data['answer'] = 'File transfer completed';
                $data['success'] = true;
            }
        } else {
            $data['answer'] = 'No File';
            $data['success'] = false;
        }

        return response()->json($data);
    }

    public function delImage($id)
    {
        $data = array();
        $klp = Kelengkapan::find($id);
        if ($klp) {
            // $dest =  storage_path()  . '/app/public/sekolah/' . $klp->id_sekolah . '/' . $klp->image;
            // $thumb_dest =  storage_path()  . '/app/public/sekolah/' . $klp->id_sekolah . '/' . $klp->thumb;
            $dest =  $this->getDestUploadUrl('sekolah')  . '/' . $klp->id_sekolah . '/' . $klp->image;
            $thumb_dest =  $this->getDestUploadUrl('sekolah')  . '/' . $klp->id_sekolah . '/' . $klp->thumb;
            if (!is_dir($dest)) {
                unlink($dest);
            }
            if (!is_dir($thumb_dest)) {
                unlink($thumb_dest);
            }

            $klp->delete();

            $data['answer'] = 'delete file sukses';
        } else {
            $data['answer'] = 'delete file gagal';
        }

        return response()->json($data);
    }
    public function delImageKerusakan($id)
    {
        $data = array();
        $klp = Kerusakan::find($id);
        if ($klp) {
            // $dest = storage_path() . '/app/public/ruang_sekolah/' . $klp->ruang_sekolah_id . '/' . $klp->item_ruang_id . '/' . $klp->image;
            // $thumb_dest = storage_path() . '/app/public/ruang_sekolah/' . $klp->ruang_sekolah_id . '/' . $klp->item_ruang_id . '/' . $klp->thumb;
            $dest =  $this->getDestUploadUrl('ruang_sekolah')  . '/' . $klp->ruang_sekolah_id . '/' . $klp->item_ruang_id . '/' . $klp->image;
            $thumb_dest =  $this->getDestUploadUrl('ruang_sekolah')  . '/' . $klp->ruang_sekolah_id . '/' . $klp->item_ruang_id . '/' . $klp->thumb;
            if (!is_dir($dest)) {
                unlink($dest);
            }
            if (!is_dir($thumb_dest)) {
                unlink($thumb_dest);
            }

            $klp->delete();

            $data['answer'] = 'delete file sukses';
        } else {
            $data['answer'] = 'delete file gagal';
        }

        return response()->json($data);
    }
}
