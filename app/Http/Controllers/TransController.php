<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use App\Models\Transaction_detail as DetailTransaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransController extends Controller
{
    function beliBarang(Request $request) {
        $validator = Validator::make($request->all(), [
            'pesan' => 'required',
          ]);

          if ($validator->fails()) {
            return response()->json([
                "status"=>false,
                "message"=>$validator->errors()->all()
            ],400);
}
          $user = Auth::guard('api')->user();
          $order = new Transaction();
          $order->transaction_date = Carbon::now();
          $order->user_id = $user->id;
          $order->save();
          // insert detail
          for ($i = 0; $i < count($request->pesan); $i++) {
            $detail = new DetailTransaction();
            $detail->transaction_id = $order->id;
            $detail->barang_id = $request->pesan[$i]['barang_id'];
            $detail->quantity = $request->pesan[$i]['qty'];
            $detail->save();
          }
          $dataTransaksi = Transaction::select("users.name as nama_admin",'transaction_date as tgl_transaksi')
          ->join('users','users.id','transaction.user_id')
          ->where('transaction.id',$order->id)->first();
          $dataDetail = DetailTransaction::select("barang_id","barang.nama_barang","quantity")
          ->join('transaction','transaction.id','transaction_detail.transaction_id')
          ->join('barang','barang.id','transaction_detail.barang_id')
          ->where('transaction_detail.transaction_id', $order->id)->get();
          if ($order && $detail) {
            return response()->json([
              'status' => true,
              'message' => 'pesan sudah diproses',
              'data' => [
                "order"=>$dataTransaksi,
                "detailOrder"=>$dataDetail,
              ]
            ], 200);
          } else {
            return response()->json([
              'status' =>false,
              'message' => 'pesanan gagal dibuat'
            ], 400);
          }
    }
}








