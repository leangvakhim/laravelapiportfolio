<?php

namespace App\Http\Controllers;

use App\Http\Requests\TextRequest;
use App\Models\Text;
use Exception;
use Illuminate\Http\Request;

class TextController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/text",
     *     tags={"Text"},
     *     summary="Get all text",
     *     @OA\Response(
     *         response=200,
     *         description="List of text"
     *     )
     * )
     */
    public function index()
    {
        try {
            $text = Text::where('active', 1)->get();

            return $this->sendResponse($text);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve text', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/text/{id}",
     *     tags={"Text"},
     *     summary="Get an text by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Text retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Text not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $text = Text::where('t_id', $id)->where('active', 1)->first();

            if (!$text) {
                return $this->sendResponse(!$text, 404, 'Text not found');
            }

            return $this->sendResponse($text);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve text', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/text",
     *     tags={"Text"},
     *     summary="Create a new text",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"t_detail", "t_type", "display"},
     *             @OA\Property(property="t_detail", type="string"),
     *             @OA\Property(property="t_type", type="integer"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Text created successfully"
     *     )
     * )
     */
    public function create(TextRequest $request)
    {
        try {
            $data = $request->validated();

            $data['active'] = 1;

            $text = Text::create($data);

            return $this->sendResponse($text, 201, 'Text created');
        } catch (Exception $e) {
            return $this->sendError('Failed to create text', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/text/{id}",
     *     tags={"Text"},
     *     summary="Update a text by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="t_detail", type="string"),
     *             @OA\Property(property="t_type", type="integer"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Text updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Text not found"
     *     )
     * )
     */
    public function update(TextRequest $request, $id)
    {
        try {
            $text = Text::find($id);

            if (!$text) {
                return $this->sendResponse(!$text, 404, 'Text not found');
            }

            $validated = $request->validated();

            $text->update($validated);

            return $this->sendResponse($text, 200, "Update successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to update text', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/text/{id}",
     *     tags={"Text"},
     *     summary="Delete an text by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="delete text successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Text not found"
     *     )
     * )
     */
    public function visible($id)
    {
        try {
            $text = Text::find($id);
            if (!$text) {
                return $this->sendError('Text Not found', 404);
            }

            $text->active = $text->active == 1 ? 0 : 1;
            $text->save();

            return $this->sendResponse([], 200, "Text delete successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to delete the text', 500, [$e->getMessage()]);
        }
    }
}
