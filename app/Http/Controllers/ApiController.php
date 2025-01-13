<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Pengiriman;
use App\Models\Pengirimandetail;
use App\Models\Subarea;
use App\Models\User;
use App\Models\Userapk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function createPengiriman(Request $request) {
        try {
            $userapk_id = Auth::user()->id;
            $subarea_id = $request->subarea_id;
           
            $data = Pengiriman::create([
                'userapk_id' => $userapk_id,
                'subarea_id' => $subarea_id
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Create pengiriman successfully',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during login',
                'message' => $e->getMessage()
            ], 500);
        }
        
    }

    public function indexpengiriman($subarea_id)
    {
        $datauser = Auth::user();
        $userapk_id= $datauser->id;
        $exclude_subarea_id = $subarea_id;
      
        $data = Pengiriman::where('userapk_id', $userapk_id)->where('subarea_id', $exclude_subarea_id)
        ->withCount([
            'pengirimandetails as totalbarang', 
            'pengirimandetails as totalbarang_miss' => function ($query) use ($exclude_subarea_id) {
                $query->where('subarea_id', '!=', $exclude_subarea_id);
            }
        ])
        ->orderBy('created_at', 'DESC')
        ->take(20)
        ->get()
        ->map(function ($item) {
            $item->created_at = Carbon::parse($item->created_at)->format('d-m-Y H:i:s');
            return $item;
        });

        return response()->json([
            'status' => 'Success',
            'message' => 'Fetched pengiriman successfully',
            'data' => $data
        ]);
    }

    public function indexpengirimandetail($pengiriman_id)
    {
        $datauser = Auth::user();
        $userapk_id = $datauser->id;
        $exclude_subarea_id = $datauser->subarea_id;
    
        $dataPengiriman = Pengiriman::where('pengiriman.userapk_id', $userapk_id)
        ->where('pengiriman.id', $pengiriman_id)
        ->join('subarea', 'subarea.id', '=', 'pengiriman.subarea_id') // Melakukan join dengan tabel subarea
        ->withCount([
            'pengirimandetails as totalbarang', // Count tanpa filter
            'pengirimandetails as totalbarang_miss' => function ($query) use ($exclude_subarea_id) {
                $query->where('subarea_id', '!=', $exclude_subarea_id); // Count dengan filter
            }
        ])
        ->select('pengiriman.*', 'subarea.subarea_nama as areaname') // Memilih kolom dari pengiriman dan subarea_nama
        ->first(); // Mengambil hanya satu data

    if ($dataPengiriman) {
        // Format created_at jika data ditemukan
        $dataPengiriman->created_at = Carbon::parse($dataPengiriman->created_at)->format('d-m-Y H:i:s');
    }

        $data = Pengirimandetail::join('subarea', 'pengirimandetail.subarea_id', '=', 'subarea.id')
        ->where('pengirimandetail.pengiriman_id', $pengiriman_id)
        ->orderBy('pengirimandetail.created_at', 'DESC')
        // ->take(20)
        ->get(['pengirimandetail.*', 'subarea.subarea_nama'])
        ->map(function ($item) {
            $item->created_at = Carbon::parse($item->created_at)->format('d-m-Y H:i:s');
            return $item;
        });
        return response()->json([
            'status' => 'Success',
            'message' => 'Fetched pengiriman successfully',
            'data' => $dataPengiriman,
            'data_detail' => $data,
            
        ]);
    }

    public function createDetailPengiriman(Request $request) {
        try {
            $request->validate([
                'pengiriman_id' => 'required',
                'subarea_id' => 'required',
                'no_stt' => 'required'
            ]);
    
            $pengiriman_id = $request->pengiriman_id;
            $subarea_id = $request->subarea_id;
            $no_stt = $request->no_stt;
           
            $data = Pengirimandetail::create([
                'pengiriman_id' => $pengiriman_id,
                'subarea_id' => $subarea_id,
                'no_stt' => $no_stt
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Create pengiriman detail successfully',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during login',
                'message' => $e->getMessage()
            ], 500);
        }
        
    }

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
                return response()->json([
                    'status' => 'error',
                    "message" => "Invalid username or password"
                ], 401);
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
        $pengiriman_id = $request->pengiriman_id;
        $daerah = $request->daerah;

        $url = 'https://api-internal-web.thelionparcel.com/v2/track/data?q=' . $nostt;
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhY2NvdW50X2dyb3VwIjoiQURNSU4iLCJhY2NvdW50X2lkIjoxLCJlbWFpbCI6Imxpb25wYXJjZWxAbGlvbnBhcmNlbC5jb20iLCJleHAiOjE4NzIzMTE2NjMsInBvc2l0aW9uIjoiU1RBRkYiLCJ1c2VybmFtZSI6Imxpb25wYXJjZWwifQ.GYc9YHeSwfq77PWynYaZT2wRrF9MG7iXDKYmtJnVXVw';
        
        $response = Http::withToken($token)->get($url);

        if (!$response->successful()) {
            return response()->json(['error' => 'Unable to fetch data'], $response->status());
        }

        $data = $response->json();
        
        if (isset($data["data"][0])) {
            $results = $data["data"][0];

            if (isset($results["is_exist"]) && $results["is_exist"] === true) {
                $subarea_nama = $this->getSubarea($results["destination"]);

                $data = [
                    'is_exist' => $results["is_exist"],
                    'stt' => $results["q"],
                    'daerah' => $subarea_nama,
                ];

                $checkExistStt = Pengirimandetail::where('pengiriman_id', $pengiriman_id)
                    ->where('no_stt', $nostt)
                    ->first();

                if($daerah != $subarea_nama) {
                    $data["is_missrute"] = true;
                } else {
                    $data["is_missrute"] = false;
                }

                if ($checkExistStt) {
                    $data["is_exist"] = false;
                } else {
                    $subarea = Subarea::where('subarea_nama', $subarea_nama)->first();
                    if(!$subarea) {
                        Subarea::create([
                            'area_id' => 1,
                            'subarea_nama' => $subarea_nama
                        ]);
                    }

                    Pengirimandetail::create([
                        'pengiriman_id' => $pengiriman_id,
                        'subarea_id' => $subarea->id,
                        'no_stt' => $nostt
                    ]);
                }
            } else {
                $data = [
                    'is_exist' => $results["is_exist"],
                    'stt' => $results["q"],
                    'daerah' => '',
                    'is_missrute' => false
                ];
            }
        } else {
            return response()->json(['error' => 'Invalid data structure from API'], 500);
        }

        return $data;
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

    function generateNomor($lastNumber) {
        $lastYear = substr($lastNumber, 1, 2);  
        $lastMonth = substr($lastNumber, 3, 2);
        $lastSeq = (int)substr($lastNumber, 5, 3); 
    
        $currentYear = date("y");  
        $currentMonth = date("m");
    
        if ($lastYear == $currentYear && $lastMonth == $currentMonth) {
            $lastSeq++; 
        } else {
            $lastSeq = 1;
        }
    
        $newNumber = $currentYear . $currentMonth . str_pad($lastSeq, 3, '0', STR_PAD_LEFT);
    
        return $newNumber;
    }

    public function deleteDetail($pengiriman_id, $pengirimandetail_id) {
        try {
            if (!$pengiriman_id || !$pengirimandetail_id) {
                return response()->json(['status' => 'failed', 'message' => 'ID pengiriman atau ID detail pengiriman tidak valid'], 400);
            }
    
            $dataPengiriman = Pengiriman::find($pengiriman_id);
            if (!$dataPengiriman) {
                return response()->json(['status' => 'failed', 'message' => 'Pengiriman tidak terdaftar'], 404);
            }
    
            $dataPengirimanDetail = Pengirimandetail::find($pengirimandetail_id);
            if (!$dataPengirimanDetail) {
                return response()->json(['status' => 'failed', 'message' => 'Detail pengiriman tidak ditemukan'], 404);
            }
    
            $dataPengirimanDetail->delete();
    
            return response()->json(['status' => 'success', 'message' => 'Detail pengiriman berhasil dihapus'], 200);
    
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function listsubarea() {
        $data = Subarea::orderBy('subarea_nama')->get();
        return  response()->json([
            'status' => 'Success',
            'message' => 'List pengiriman detail berhasil diambil',
            'data' => $data
        ]);
    }
}
