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
            $token = $request->bearerToken();
            
            $checkToken = $this->checkToken($token);
    
            if (!$checkToken) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
    
            $username = $request->username;
            $password = $request->password;
           
            $user = Userapk::join('subarea', 'subarea.id', '=', 'userapk.subarea_id')
            ->join('area', 'area.id', '=', 'subarea.area_id')
            ->select('userapk.*', 'area.area_nama', 'subarea.subarea_nama')
            ->where('username', $username)->first();

            if ($user && Hash::check($password, $user->password)) {
                $token = $user->createToken('authToken')->plainTextToken;
    
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'token' => $token
                ], 200);
            } else {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during login',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    public function getDestinasi(Request $request, $nostt)
    {
        $token = $request->bearerToken();
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
