<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthApiController extends Controller
{
    // list All Api Users And Keys
    public function index(Request $request)
    {
        //validating request
        $validator = Validator::make($request->all(), [
            'api_user' => 'required',
            'api_key' => 'required',
        ]);

        if ($validator->fails()) {
            $res = [
                'status' => false,
                'data' => $validator
            ];
            return response()->json($res);
        }

        // Check if Api Auth is True
        $api = Api::where('api_user', '=', $request->api_user)->get();
        if (count($api) > 0) {

            if ($api[0]->api_key == $request->api_key) { //if Api auth is true (api_user & api_key)
                $Apis = Api::all();
                if (count($Apis) > 0) {
                    $res = [
                        'status' => true,
                        'data' => $Apis
                    ];
                    return response()->json($res);
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'No Api_User'
                    ];
                    return response()->json($res);
                }
            } else { //if Not Api auth is true (api_key)
                $res = [
                    'status' => false,
                    'data' => 'API_KEY Not correct'
                ];
                return response()->json($res);
            }
        } else { //if Not Api auth is true (api_user)
            $res = [
                'status' => false,
                'data' => 'API_USER Not Found'
            ];
            return response()->json($res);
        }
    }

    // Get Api user By name Given when creating the Api
    public function getByName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_user' => 'required',
            'api_key' => 'required',
            'api_name' => 'required',
        ]);

        if ($validator->fails()) {
            $res = [
                'status' => false,
                'data' => $validator
            ];
            return response()->json($res);
        }

        $api = Api::where('api_user', '=', $request->api_user)->get();
        if (count($api) > 0) {
            if ($api[0]->api_key == $request->api_key) {
                $Api_i = Api::where('name', '=', $request->api_name)->get();
                if (count($Api_i) > 0) {
                    $res = [
                        'status' => true,
                        'data' => $Api_i
                    ];
                    return response()->json($res);
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'No API_USER With this name In System'
                    ];
                    return response()->json($res);
                }
            } else {
                $res = [
                    'status' => false,
                    'data' => 'API_KEY Not correct'
                ];
                return response()->json($res);
            }
        } else {
            $res = [
                'status' => false,
                'data' => 'API_USER Not Found'
            ];
            return response()->json($res);
        }
    }

    // Creating Api User (for Auth)
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_user' => 'required',
            'api_key' => 'required',
            'new_api_name' => 'required',
            'new_api_user' => 'required',
            'customer' => 'nullable',
            'permission' => 'nullable',
        ]);

        if ($validator->fails()) {
            $res = [
                'status' => false,
                'data' => $validator
            ];
            return response()->json($res);
        }

        $api = Api::where('api_user', '=', $request->api_user)->get();
        $token = Str::random(60);

        if (count($api) > 0) {
            if ($api[0]->api_key == $request->api_key) {
                $arrayToInsert = [
                    'name' => $request->new_api_name,
                    'api_user' => $request->new_api_user,
                    'api_key' => $token,
                    'customer' => $request->customer,
                    'permission' => $request->permission,
                ];
                $Api_i = Api::create($arrayToInsert);
                if ($Api_i) {
                    $res = [
                        'status' => true,
                        'data' => $Api_i
                    ];
                    return response()->json($res);
                } else {
                    $res = [
                        'status' => false,
                        'data' => 'Api Not Created'
                    ];
                    return response()->json($res);
                }
            } else {
                $res = [
                    'status' => false,
                    'data' => 'API_KEY Not correct'
                ];
                return response()->json($res);
            }
        } else {
            $res = [
                'status' => false,
                'data' => 'API_USER Not Found'
            ];
            return response()->json($res);
        }
    }
}
