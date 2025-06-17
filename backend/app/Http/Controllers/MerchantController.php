<?php

namespace App\Http\Controllers;

use App\Http\Resources\MerchantResource;
use App\Http\Resources\UserResource;
use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MerchantController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $merchants = Merchant::with('user')->get();

        return response()->json([
            'status' => 'success',
            'data' => MerchantResource::collection($merchants),
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $merchant = Merchant::with('user')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => new MerchantResource($merchant),
        ]);
    }

    /**
     * @param $userId
     * @return JsonResponse
     */
    public function merchantsByUser($userId): JsonResponse
    {
        $user = User::with('merchants')->findOrFail($userId);

        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user),
        ]);
    }
}
