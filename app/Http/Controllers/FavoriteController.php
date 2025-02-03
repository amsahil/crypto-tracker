<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store($cryptoId)
    {
        Favorite::create([
            'user_id' => Auth::id(),
            'crypto_id' => $cryptoId,
        ]);

        return response()->json(['message' => 'Added to favorites!']);
    }
}

