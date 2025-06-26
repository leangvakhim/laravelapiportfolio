<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResumeRequest;
use App\Models\Resume;
use Exception;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/resume",
     *     tags={"Resume"},
     *     summary="Get all resume",
     *     @OA\Response(
     *         response=200,
     *         description="List of resume"
     *     )
     * )
     */
    public function index()
    {
        try {
            $resume = Resume::where('active', 1)->get();

            return $this->sendResponse($resume);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve resume', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/resume/{id}",
     *     tags={"Resume"},
     *     summary="Get an resume by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resume retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resume not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $resume = Resume::where('r_id', $id)->where('active', 1)->first();

            if (!$resume) {
                return $this->sendResponse(!$resume, 404, 'Resume not found');
            }

            return $this->sendResponse($resume);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve resume', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/resume",
     *     tags={"Resume"},
     *     summary="Create a new resume",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"r_title", "r_duration", "r_detail", "r_type", "display"},
     *             @OA\Property(property="r_title", type="string"),
     *             @OA\Property(property="r_duration", type="string"),
     *             @OA\Property(property="r_detail", type="string"),
     *             @OA\Property(property="r_type", type="integer"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Resume created successfully"
     *     )
     * )
     */
    public function create(ResumeRequest $request)
    {
        try {
            $data = $request->validated();

            $data['active'] = 1;

            $resume = Resume::create($data);

            return $this->sendResponse($resume, 201, 'Resume created');
        } catch (Exception $e) {
            return $this->sendError('Failed to create resume', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/resume/{id}",
     *     tags={"Resume"},
     *     summary="Update a resume by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="r_title", type="string"),
     *             @OA\Property(property="r_duration", type="string"),
     *             @OA\Property(property="r_detail", type="string"),
     *             @OA\Property(property="r_type", type="integer"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resume updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resume not found"
     *     )
     * )
     */
    public function update(ResumeRequest $request, $id)
    {
        try {
            $resume = Resume::find($id);

            if (!$resume) {
                return $this->sendResponse(!$resume, 404, 'Resume not found');
            }

            $validated = $request->validated();

            $resume->update($validated);

            return $this->sendResponse($resume, 200, "Update successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to update resume', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/resume/{id}",
     *     tags={"Resume"},
     *     summary="Delete an resume by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="delete resume successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resume not found"
     *     )
     * )
     */
    public function visible($id)
    {
        try {
            $resume = Resume::find($id);
            if (!$resume) {
                return $this->sendError('Resume Not found', 404);
            }

            $resume->active = $resume->active == 1 ? 0 : 1;
            $resume->save();

            return $this->sendResponse([], 200, "Resume delete successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to delete the resume', 500, [$e->getMessage()]);
        }
    }
}
