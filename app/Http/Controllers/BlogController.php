<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/blog",
     *     tags={"Blog"},
     *     summary="Get all blog",
     *     @OA\Response(
     *         response=200,
     *         description="List of blog"
     *     )
     * )
     */
    public function index()
    {
        try {
            $blog = Blog::with([
                'image'
            ])->orderBy('b_order', 'asc')->where('active', 1)->get();

            return $this->sendResponse($blog);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve blog', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/blog/{id}",
     *     tags={"Blog"},
     *     summary="Get an blog by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $blog = Blog::with([
                'image'
            ])->orderBy('b_order', 'asc')->where('b_id', $id)->where('active', 1)->first();

            if (!$blog) {
                return $this->sendResponse(!$blog, 404, 'Blog not found');
            }

            return $this->sendResponse($blog);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve blog', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/blog",
     *     tags={"Blog"},
     *     summary="Create a new blog",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"b_title", "b_subtitle", "b_detail", "b_img", "b_date", "display"},
     *             @OA\Property(property="b_img", type="integer"),
     *             @OA\Property(property="b_title", type="string"),
     *             @OA\Property(property="b_subtitle", type="string"),
     *             @OA\Property(property="b_date", type="date"),
     *             @OA\Property(property="b_detail", type="string"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Blog created successfully"
     *     )
     * )
     */
    public function create(BlogRequest $request)
    {
        try {
            $data = $request->validated();

            if (!isset($data['b_order'])) {
                $data['b_order'] = Blog::max('b_order') + 1;
            }

            $data['active'] = 1;

            $blog = Blog::create($data);

            return $this->sendResponse($blog, 201, 'Blog created');
        } catch (Exception $e) {
            return $this->sendError('Failed to create blog', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/blog/{id}",
     *     tags={"Blog"},
     *     summary="Update a blog by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="b_img", type="integer"),
     *             @OA\Property(property="b_title", type="string"),
     *             @OA\Property(property="b_subtitle", type="string"),
     *             @OA\Property(property="b_detail", type="string"),
     *             @OA\Property(property="b_date", type="date"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog not found"
     *     )
     * )
     */
    public function update(BlogRequest $request, $id)
    {
        try {
            $blog = Blog::find($id);

            if (!$blog) {
                return $this->sendResponse(!$blog, 404, 'Blog not found');
            }

            $validated = $request->validated();

            $blog->update($validated);

            return $this->sendResponse($blog, 200, "Update successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to update blog', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/blog/visible/{id}",
     *     tags={"Blog"},
     *     summary="Delete an blog by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="delete blog successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Blog not found"
     *     )
     * )
     */
    public function visible($id)
    {
        try {
            $blog = Blog::find($id);
            if (!$blog) {
                return $this->sendError('Blog Not found', 404);
            }

            $blog->active = $blog->active == 1 ? 0 : 1;
            $blog->save();

            return $this->sendResponse([], 200, "Blog delete successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to delete the blog', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/blog/reorder",
     *     tags={"Blog"},
     *     summary="Reorder blogs",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="b_id", type="integer", example=1),
     *                 @OA\Property(property="b_order", type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Blog order updated successfully"
     *     )
     * )
     */
    public function reorder(Request $request)
    {
        try {
            $blog = $request->validate([
                '*.b_id' => 'required|integer|exists:tbblog,b_id',
                '*.b_order' => 'required|integer'
            ]);

            foreach ($blog as $item) {
                Blog::where('b_id', $item['b_id'])->update(['b_order' => $item['b_order']]);
            }

            return response()->json([
                'message' => "Blog order updated successfully",
            ], 200);
        } catch (Exception $e) {
            return $this->sendError('Failed to reorder blog', 500, [$e->getMessage()]);
        }
    }
}
