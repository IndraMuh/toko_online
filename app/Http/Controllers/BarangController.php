<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Barang;
use Exception;


class BarangController extends Controller
{
    function InsertBarang(Request $request){
        $validator = Validator::make($request->all(), [
            'nama_barang'=>'required',
            'gambar_barang' => 'required|max:10000|mimes:jpg,jpeg,png',
            'harga'=>'required',
            'category_id'=>'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
        try {
            $barang = new Barang();
            $file = $request->gambar_barang;
            if(!$request->hasFile('gambar_barang')){
            } else {
                $imageName = time().'-'.$file->getClientOriginalName();
                $uploadDir    = public_path().'/images';
                $file->move($uploadDir, $imageName);
                $barang->gambar_barang = 'images/'.$imageName;
            }
            
            $barang->nama_barang = $request->nama_barang;
            $barang->harga = $request->harga;
            $barang->category_id = $request->category_id;
         
            $barang->save();
            return Response()->json([
                'status'=>true,
                'message'=>'Sukses input data barang',
            ]);
        } catch (Exception $e) {
            return Response()->json(["status"=>false,'message'=>$e]);
        }
    }

    public function getBarang() {
        try {
            $barang = Barang::select('barang.*', 'category.category_name')
                ->join('category', 'category.id', '=', 'barang.category_id')
                ->get();
    
            return response()->json([
                'status' => true,
                'message' => 'Berhasil load data barang',
                'data' => $barang,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal load data barang. ' . $e->getMessage(),
            ]);
        }
    }
    public function getDetailBarang($id) {
        try {
            $barang = Barang::where('id', $id)->first();
    
            return response()->json([
                'status' => true,
                'message' => 'Berhasil load data detail Barang',
                'data' => $barang,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal load data barang. ' . $e->getMessage(),
            ]);
        }
    }

    function updateBarang($id, Request $request){
        $validator = Validator::make($request->all(), [
            'nama_barang'=>'required',
            'gambar_barang' => 'required|max:10000|mimes:jpg,jpeg,png',
            'harga'=>'required',
            'category_id'=>'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ]);
        }
    
        try {
            $barang = Barang::find($id);
            if ($barang) {
                $file = $request->gambar_barang;
                if ($request->hasFile('gambar_barang')) {
                    $imageName = time() . '-' . $file->getClientOriginalName();
                    $uploadDir = public_path() . '/images';
                    $file->move($uploadDir, $imageName);
                    $barang->gambar_barang = 'images/' . $imageName;
                }
    
                $barang->nama_barang = $request->nama_barang;
                $barang->harga = $request->harga;
                $barang->category_id = $request->category_id;
    
                $barang->save();
    
                return response()->json([
                    'status' => true,
                    'message' => 'Sukses update data barang',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'data barang ini tidak ditemukan',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e,
            ]);
        }
    }

    public function hapusBarang($id)
{
    try {
        Barang::where('id', $id)->delete();
        return response()->json([
            "status" => true,
            "message" => "Data berhasil dihapus"
        ]);
    } catch (\Exception $e) {
        return response()->json([
            "status" => false,
            "message" => "Gagal hapus Movie. " . $e->getMessage()
        ]);
    }
}
}
