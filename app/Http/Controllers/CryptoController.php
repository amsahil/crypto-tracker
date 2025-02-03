<?php

namespace App\Http\Controllers;

use App\Services\CryptoService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CryptoController extends Controller
{
    protected $cryptoService;

    public function __construct(CryptoService $cryptoService)
    {
        $this->cryptoService = $cryptoService;
    }

    /**
     * Display a list of cryptocurrency prices.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $cryptos = collect($this->cryptoService->getPrices());

        if ($search) {
            $cryptos = $cryptos->filter(function ($crypto) use ($search) {
                return stripos($crypto['name'], $search) !== false || stripos($crypto['symbol'], $search) !== false;
            });
        }

        // Paginate the collection: Slice and paginate
        $perPage = 10;
        $currentPage = $request->input('page', 1);
        $items = $cryptos->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $cryptos = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $cryptos->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('crypto.index', compact('cryptos'));
    }

    public function updatePrice(Request $request, $cryptoId)
    {
        $newPrice = $request->input('price');
        $crypto = Crypto::find($cryptoId);

        if ($crypto) {
            $crypto->current_price = $newPrice;
            $crypto->save();

            // Fire the event to broadcast the price update
            event(new CryptoPriceUpdated($crypto));

            return response()->json(['message' => 'Price updated successfully']);
        }

        return response()->json(['message' => 'Crypto not found'], 404);
    }
}
