<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getDataStt(Request $request)
    {
        $token = $request->bearerToken();
        $nostt = $request->input('nostt');

        $checkToken = $this->checkToken($token);
        if (!$checkToken) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $url = 'https://api-internal-web.thelionparcel.com/v2/track/data?q=' . $nostt;
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2NvdW50X2dyb3VwIjoiQURNSU4iLCJhY2NvdW50X2lkIjoxLCJlbWFpbCI6Imxpb25wYXJjZWxAbGlvbnBhcmNlbC5jb20iLCJleHAiOjE4NzIzMTE2NjMsInBvc2l0aW9uIjoiU1RBRkYiLCJ1c2VybmFtZSI6Imxpb25wYXJjZWwifQ.GYc9YHeSwfq77PWynYaZT2wRrF9MG7iXDKYmtJnVXVw';
        $response = Http::withToken($token)->get($url);

        if ($response->successful()) {
            $data = $response->json();
            $results = $data["data"][0];
            if ($results["is_exist"] === true) {
                // dd($results["destination"]);

                $input = 'BATAM KOTA, BATAM, BATAM';
                // Pisahkan string berdasarkan koma
                $segments = explode(',', $input);

                // Trim semua segmen untuk menghilangkan spasi berlebih
                $segments = array_map('trim', $segments);
                $totalSegments = count($segments);
                if ($totalSegments >= 3) {
                    // Ambil kata ketiga dari belakang
                    return $segments[$totalSegments - 3];
                }
                dd('salah');
            }
            return $data;
        } else {
            return response()->json(['error' => 'Unable to fetch data'], $response->status());
        }
    }

    private function checkToken($token)
    {
        $expectedToken = env('TOKEN');
        if ($token !== $expectedToken) {
            return false;
        }
        return true;
    }
}
