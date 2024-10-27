<?php

namespace App\Http\Controllers;

use App\Models\Subarea;
use App\Models\User;
use App\Models\Userapk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request) {
        try {
            // Dapatkan token dari request
            $token = $request->bearerToken();
            
            // Cek apakah token valid dengan memanggil fungsi checkToken()
            $checkToken = $this->checkToken($token);
    
            // Jika token tidak valid, kembalikan respons 'Unauthorized'
            if (!$checkToken) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            // Validasi input username dan password
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
    
            // Dapatkan username dan password dari request
            $username = $request->username;
            $password = $request->password;
           
            // Cari user berdasarkan username
            $user = Userapk::join('subarea', 'subarea.id', '=', 'userapk.subarea_id')
            ->join('area', 'area.id', '=', 'subarea.area_id')
            ->select('userapk.*', 'area.area_nama', 'subarea.subarea_nama')
            ->where('username', $username)->first();

            // Cek apakah user ditemukan dan password benar
            if ($user && Hash::check($password, $user->password)) {
                // Generate token untuk autentikasi pengguna
                $token = $user->createToken('authToken')->plainTextToken;
    
                // Kembalikan respons JSON dengan informasi user dan token
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'token' => $token
                ], 200);
            } else {
                // Kembalikan error jika username atau password salah
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (\Exception $e) {
            // Jika terjadi error, tangani di sini dan kembalikan respons error
            return response()->json([
                'error' => 'An error occurred during login',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    public function getDataStt(Request $request)
    {
        $token = $request->bearerToken();
        $nostt = $request->input('nostt');
        $username = $request->username;

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
                $subarea_nama = $this->getSubarea($results["destination"]);
                // $subarea_id = Userapk::where('username', $username)->first()->subarea_id;
                // $subarea_nama = Subarea::where('id', $subarea_id)->first()->subarea_nama;

                $data = [
                    'is_exist' => $results["is_exist"],
                    'stt' => $results["q"],
                    'daerah' => $subarea_nama,
                ];
            } else {
                $data = [
                    'is_exist' => $results["is_exist"],
                    'stt' => $results["q"],
                    'daerah' => '',
                ];
            }

            return $data;
        } else {
            return response()->json(['error' => 'Unable to fetch data'], $response->status());
        }
    }

    private function getSubarea($alamat)
    {
        $segments = explode(',', $alamat);

        $segments = array_map('trim', $segments);
        $totalSegments = count($segments);
        if ($totalSegments >= 3) {
            return $segments[$totalSegments - 3];
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
