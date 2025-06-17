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
     * @param Merchant $merchant
     * @return JsonResponse
     */
    public function show(Merchant $merchant): JsonResponse
    {
        $merchant->load('user');

        return response()->json([
            'status' => 'success',
            'data' => new MerchantResource($merchant),
        ]);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function merchantsByUser(User $user): JsonResponse
    {
        $user->load('merchants');

        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user),
        ]);
    }
}
