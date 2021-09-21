<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    private $type;
    private $enpoint;
    private $kurir;

    public function __construct() {
        $this->type = strtolower(env('RAJAONGKIR_PACKAGE', 'Key Dari ENV'));
        $this->enpoint = "https://".$this->type.".rajaongkir.com/api/";
        $this->kurir = Courier::where('status', 1)->get();
    }

    public function get_all_courier() {
        return response()->json($this->kurir, 200);
    }

    public function ongkir(Request $request) {
        $http = Http::post($this->enpoint."cost", [
            'key'               => env('RAJAONGKIR_API_KEY', 'Key Dari ENV'),
            'origin'            => $request->origin, // Asal Id Kota atau id Kecamatan
            'originType'        => "subdistrict", // city atau subdistrict
            'destination'       => $request->destination, // Tujuan Id Kota atau id Kecamatan
            'destinationType'   => "subdistrict", // city atau subdistrict
            'weight'            => $request->weight, // Berat dalam gram
            'courier'           => $request->courier // Kode Kurir
        ]);
        return response($http['rajaongkir'])->header('Content-Type', 'application/json');
    }

    public function lacak(Request $request) {
        $http = Http::post($this->enpoint."waybill", [
            'key'               => env('RAJAONGKIR_API_KEY', 'Key Dari ENV'),
            'waybill'           => $request->waybill, // Resi
            'courier'           => $request->courier, // Kurir
        ]);
        return response($http['rajaongkir'])->header('Content-Type', 'application/json');
    }
}
