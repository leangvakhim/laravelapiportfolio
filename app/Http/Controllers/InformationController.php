<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformationRequest;
use App\Models\Information;
use Exception;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/information",
     *     tags={"Information"},
     *     summary="Get all information",
     *     @OA\Response(
     *         response=200,
     *         description="List of information"
     *     )
     * )
     */
    public function index()
    {
        try {
            $information = Information::with([
                'image'
            ])->where('active', 1)->get();

            return $this->sendResponse($information);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve information', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/information/{id}",
     *     tags={"Information"},
     *     summary="Get an information by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Information retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Information not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $information = Information::with([
                'image'
            ])->where('i_id', $id)->where('active', 1)->first();

            if (!$information) {
                return $this->sendResponse(!$information, 404, 'Information not found');
            }

            return $this->sendResponse($information);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve information', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/information",
     *     tags={"Information"},
     *     summary="Create a new information",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"i_img", "i_title", "i_detail", "display"},
     *             @OA\Property(property="i_img", type="integer"),
     *             @OA\Property(property="i_title", type="string"),
     *             @OA\Property(property="i_detail", type="string"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Information created successfully"
     *     )
     * )
     */
    public function create(InformationRequest $request)
    {
        try {
            $data = $request->validated();

            $data['active'] = 1;

            $information = Information::create($data);

            return $this->sendResponse($information, 201, 'Information created');
        } catch (Exception $e) {
            return $this->sendError('Failed to create information', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/information/{id}",
     *     tags={"Information"},
     *     summary="Update a information by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="i_img", type="integer"),
     *             @OA\Property(property="i_title", type="string"),
     *             @OA\Property(property="i_detail", type="string"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Information updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Information not found"
     *     )
     * )
     */
    public function update(InformationRequest $request, $id)
    {
        try {
            $information = Information::find($id);

            if (!$information) {
                return $this->sendResponse(!$information, 404, 'Information not found');
            }

            $validated = $request->validated();

            $information->update($validated);

            return $this->sendResponse($information, 200, "Update successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to update information', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/information/{id}",
     *     tags={"Information"},
     *     summary="Delete an information by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="delete information successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Information not found"
     *     )
     * )
     */
    public function visible($id)
    {
        try {
            $information = Information::find($id);
            if (!$information) {
                return $this->sendError('Information Not found', 404);
            }

            $information->active = $information->active == 1 ? 0 : 1;
            $information->save();

            return $this->sendResponse([], 200, "Information delete successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to delete the information', 500, [$e->getMessage()]);
        }
    }
}
