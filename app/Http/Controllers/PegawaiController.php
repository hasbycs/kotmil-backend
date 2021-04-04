<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pegawai;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class PegawaiController extends Controller
{
    // public function index(Request $request)
	// {
    //     $perPage = 10;
    //     if(!empty($request->input('per_page'))) {
    //         $perPage = $request->input('per_page');
    //     }


    //     // mengambil data dari table pegawai
	// 	$pegawai = DB::table('pegawai')->paginate($perPage);
    //     $response = [
    //         'message' => 'List Employee',
    //         'data' => $pegawai
    //     ];
    //     // mengirim data pegawai ke view index

    //     return response()->json($response, Response::HTTP_OK);
		
 
	// }
}
