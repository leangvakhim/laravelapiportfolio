<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialRequest;
use App\Models\Social;
use Exception;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/social",
     *     tags={"Social"},
     *     summary="Get all social",
     *     @OA\Response(
     *         response=200,
     *         description="List of social"
     *     )
     * )
     */
    public function index()
    {
        try {
            $social = Social::with([
                'image'
            ])->where('active', 1)->get();

            return $this->sendResponse($social);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve social', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/social/{id}",
     *     tags={"Social"},
     *     summary="Get an social by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Social retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Social not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $social = Social::with([
                'image'
            ])->where('s_id', $id)->where('active', 1)->first();

            if (!$social) {
                return $this->sendResponse(!$social, 404, 'Social not found');
            }

            return $this->sendResponse($social);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve social', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/social",
     *     tags={"Social"},
     *     summary="Create a new social",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"s_img", "s_title", "s_link", "display"},
     *             @OA\Property(property="s_img", type="integer"),
     *             @OA\Property(property="s_title", type="string"),
     *             @OA\Property(property="s_link", type="string"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Social created successfully"
     *     )
     * )
     */
    public function create(SocialRequest $request)
    {
        try {
            $data = $request->validated();

            $data['active'] = 1;

            $social = Social::create($data);

            return $this->sendResponse($social, 201, 'Social created');
        } catch (Exception $e) {
            return $this->sendError('Failed to create social', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/social/{id}",
     *     tags={"Social"},
     *     summary="Update a social by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="s_img", type="integer"),
     *             @OA\Property(property="s_title", type="string"),
     *             @OA\Property(property="s_link", type="string"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Social updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Social not found"
     *     )
     * )
     */
    public function update(SocialRequest $request, $id)
    {
        try {
            $social = Social::find($id);

            if (!$social) {
                return $this->sendResponse(!$social, 404, 'Social not found');
            }

            $validated = $request->validated();

            $social->update($validated);

            return $this->sendResponse($social, 200, "Update successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to update social', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/social/{id}",
     *     tags={"Social"},
     *     summary="Delete an social by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="delete social successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Social not found"
     *     )
     * )
     */
    public function visible($id)
    {
        try {
            $social = Social::find($id);
            if (!$social) {
                return $this->sendError('Social Not found', 404);
            }

            $social->active = $social->active == 1 ? 0 : 1;
            $social->save();

            return $this->sendResponse([], 200, "Social delete successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to delete the social', 500, [$e->getMessage()]);
        }
    }
}
