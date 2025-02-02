<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = User::when(request()->search, function ($query) {
                return $query->where('name', 'like', '%' . request('search') . '%');
            })
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'message' => 'Users retrieved successfully',
            'users' => $user,
        ], 200);
    }
}
