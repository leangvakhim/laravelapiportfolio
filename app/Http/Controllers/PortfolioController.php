<?php

namespace App\Http\Controllers;

use App\Http\Requests\PortfolioRequest;
use App\Models\Portfolio;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PortfolioController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/portfolio",
     *     tags={"Portfolio"},
     *     summary="Get all portfolio",
     *     @OA\Response(
     *         response=200,
     *         description="List of portfolio"
     *     )
     * )
     */
    public function index()
    {
        try {
            $portfolio = Portfolio::with([
                'image'
            ])->orderBy('p_order', 'asc')->where('active', 1)->get();

            return $this->sendResponse($portfolio);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve portfolio', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/portfolio/{id}",
     *     tags={"Portfolio"},
     *     summary="Get an portfolio by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Portfolio retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Portfolio not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $portfolio = Portfolio::with([
                'image'
            ])->where('p_id', $id)->where('active', 1)->first();

            if (!$portfolio) {
                return $this->sendResponse(!$portfolio, 404, 'Portfolio not found');
            }

            return $this->sendResponse($portfolio);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve portfolio', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/portfolio",
     *     tags={"Portfolio"},
     *     summary="Create a new portfolio",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"p_title", "p_category", "p_img", "p_detail", "display"},
     *             @OA\Property(property="p_img", type="integer"),
     *             @OA\Property(property="p_title", type="string"),
     *             @OA\Property(property="p_category", type="string"),
     *             @OA\Property(property="p_detail", type="string"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Portfolio created successfully"
     *     )
     * )
     */
    public function create(PortfolioRequest $request)
    {
        try {
            $data = $request->validated();

            if (!isset($data['p_order'])) {
                $data['p_order'] = Portfolio::max('p_order') + 1;
            }

            $data['active'] = 1;

            $portfolio = Portfolio::create($data);

            return $this->sendResponse($portfolio, 201, 'Portfolio created');
        } catch (Exception $e) {
            return $this->sendError('Failed to create portfolio', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/portfolio/{id}",
     *     tags={"Portfolio"},
     *     summary="Update a portfolio by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="p_img", type="integer"),
     *             @OA\Property(property="p_title", type="string"),
     *             @OA\Property(property="p_category", type="string"),
     *             @OA\Property(property="p_detail", type="string"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Portfolio updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Portfolio not found"
     *     )
     * )
     */
    public function update(PortfolioRequest $request, $id)
    {
        try {
            $portfolio = Portfolio::find($id);

            if (!$portfolio) {
                return $this->sendResponse(!$portfolio, 404, 'Portfolio not found');
            }

            $validated = $request->validated();

            $portfolio->update($validated);

            return $this->sendResponse($portfolio, 200, "Update successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to update portfolio', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/portfolio/visible/{id}",
     *     tags={"Portfolio"},
     *     summary="Delete an portfolio by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="delete portfolio successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Portfolio not found"
     *     )
     * )
     */
    public function visible($id)
    {
        try {
            $portfolio = Portfolio::find($id);
            if (!$portfolio) {
                return $this->sendError('Portfolio Not found', 404);
            }

            $portfolio->active = $portfolio->active == 1 ? 0 : 1;
            $portfolio->save();

            return $this->sendResponse([], 200, "Portfolio delete successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to delete the portfolio', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/portfolio/reorder",
     *     tags={"Portfolio"},
     *     summary="Reorder portfolios",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="p_id", type="integer", example=1),
     *                 @OA\Property(property="p_order", type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Portfolio order updated successfully"
     *     )
     * )
     */
    public function reorder(Request $request)
    {
        try {
            $portfolio = $request->validate([
                '*.p_id' => 'required|integer|exists:tbportfolio,p_id',
                '*.p_order' => 'required|integer'
            ]);

            foreach ($portfolio as $item) {
                Portfolio::where('p_id', $item['p_id'])->update(['p_order' => $item['p_order']]);
            }

            return response()->json([
                'message' => "Portfolio order updated successfully",
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to reorder portfolio', 500, [$e->getMessage()]);
        }
    }
}
