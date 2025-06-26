<?php

namespace App\Http\Controllers;

use App\Http\Requests\AchievementRequest;
use App\Models\Achievement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;

class AchievementController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/achievement",
     *     tags={"Achievement"},
     *     summary="Get all achievements",
     *     @OA\Response(
     *         response=200,
     *         description="List of achievements"
     *     )
     * )
     */
    public function index()
    {
        try {
            $achievement = Achievement::with([
                'image'
            ])->where('active', 1)->get();

            return $this->sendResponse($achievement);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve achievement', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/achievement/{id}",
     *     tags={"Achievement"},
     *     summary="Get an achievement by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Achievement retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Achievement not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $achievement = Achievement::with([
                'image'
            ])->where('a_id', $id)->where('active', 1)->first();

            if (!$achievement) {
                return $this->sendResponse(!$achievement, 404, 'Achievement not found');
            }

            return $this->sendResponse($achievement);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve achievement', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/achievement",
     *     tags={"Achievement"},
     *     summary="Create a new achievement",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"a_img", "a_type", "display"},
     *             @OA\Property(property="a_img", type="integer"),
     *             @OA\Property(property="a_type", type="integer"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Achievement created successfully"
     *     )
     * )
     */
    public function create(AchievementRequest $request)
    {
        try {
            $data = $request->validated();

            $data['active'] = 1;

            $achievement = Achievement::create($data);

            return $this->sendResponse($achievement, 201, 'Achievement created');
        } catch (Exception $e) {
            return $this->sendError('Failed to create achievement', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/achievement/{id}",
     *     tags={"Achievement"},
     *     summary="Update a achievement by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="a_img", type="integer"),
     *             @OA\Property(property="a_type", type="integer"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Achievement updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Achievement not found"
     *     )
     * )
     */
    public function update(AchievementRequest $request, $id)
    {
        try {
            $achievement = Achievement::find($id);

            if (!$achievement) {
                return $this->sendResponse(!$achievement, 404, 'Achievement not found');
            }

            $validated = $request->validated();

            $achievement->update($validated);

            return $this->sendResponse($achievement, 200, "Update successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to update achievement', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/achievement/{id}",
     *     tags={"Achievement"},
     *     summary="Delete an achievement by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="delete achievement successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Achievement not found"
     *     )
     * )
     */
    public function visible($id)
    {
        try {
            $achievement = Achievement::find($id);
            if (!$achievement) {
                return $this->sendError('Achievement Not found', 404);
            }

            $achievement->active = $achievement->active == 1 ? 0 : 1;
            $achievement->save();

            return $this->sendResponse([], 200, "Achievement delete successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to delete the achievement', 500, [$e->getMessage()]);
        }
    }
}
