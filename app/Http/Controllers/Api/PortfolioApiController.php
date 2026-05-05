<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PortfolioService;
use Illuminate\Http\Request;

class PortfolioApiController extends Controller
{
    protected $portfolioService;

    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    /**
     * Fetch a public portfolio by username.
     */
    public function show($username)
    {
        try {
            $portfolio = $this->portfolioService->getByUsername($username);
            
            return response()->json([
                'success' => true,
                'data' => $portfolio
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Portfolio not found or inactive.'
            ], 404);
        }
    }
}
