<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Models\Image;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Info(
 *     title="My Portfolio API",
 *     version="1.0.0",
 *     description="This is the Swagger UI documentation for my Laravel API"
 * )
 */
class ImageController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/image",
     *      tags={"Images"},
     *      summary="Get all images",
     *      @OA\Response(
     *          response=200,
     *          description="List of all images"
     *      )
     * )
     */
    public function index()
    {
        try {
            $images = Image::all()->map(function ($image) {
                return [
                    'image_id' => $image->image_id,
                    'img' => $image->img,
                    'image_url' => url("storage/uploads/{$image->img}"),
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at
                ];
            });

            return $this->sendResponse($images);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve images', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/image/{id}",
     *      tags={"Images"},
     *      summary="Get image by ID",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Image found"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Image not found"
     *      )
     * )
     */
    public function show($id)
    {
        try {
            $image = Image::find($id);
            if (!$image) {
                return $this->sendError('Image not found', 404);
            }

            return $this->sendResponse([
                'image_id' => $image->image_id,
                'img' => $image->img,
                'image_url' => url("storage/uploads/{$image->img}"),
                'created_at' => $image->created_at,
                'updated_at' => $image->updated_at
            ]);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve image', 500, [$e->getMessage()]);
        }
    }

    /**
     *  @OA\Post(
     *      path="/api/image",
     *      tags={"Images"},
     *      summary="upload a new images",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="img[]",
     *                      type="array",
     *                      @OA\Items(type="string", format="binary")
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Images uploaded"
     *      ),
     *      @OA\Response(
     *         response=422,
     *         description="Validation failed"
     *     )
     *  )
     */
    public function create(ImageRequest $request)
    {
        try {
            if (!$request->hasFile('img')) {
                return $this->sendError('No file uploaded', 400);
            }

            $uploadedImages = [];
            $existingImages = [];

            foreach ($request->file('img') as $file) {
                $fileName = $file->getClientOriginalName();

                // Check if image already exists
                if (Image::where('img', $fileName)->exists()) {
                    $existingImages[] = $fileName;
                    continue;
                }

                // Store the image
                $filePath = $file->storeAs('uploads', $fileName, 'public');

                $image = Image::create([
                    'img' => $fileName,
                ]);

                $uploadedImages[] = [
                    'image_id' => $image->image_id,
                    'img' => $fileName,
                    'image_url' => asset("storage/$filePath"),
                ];
            }

            if (!empty($uploadedImages) && !empty($existingImages)) {
                $message = "Some images were uploaded successfully, but some already exist.";
            } elseif (!empty($uploadedImages)) {
                $message = "Images uploaded successfully.";
            } else {
                $message = "Images already exist.";
            }

            return $this->sendResponse([
                'message' => $message,
                'uploaded_images' => $uploadedImages,
                'existing_images' => $existingImages
            ], 201);
        } catch (Exception $e) {
            return $this->sendError('Failed to upload images', 500, [$e->getMessage()]);
        }
    }

    /**
     *  @OA\Delete(
     *      path="/api/image/{id}",
     *      tags={"Images"},
     *      summary="Delete an image by ID",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Image delete successfully"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Image not found"
     *      )
     *  )
     */
    public function visible($id)
    {
        try {
            $image = Image::find($id);
            if (!$image) {
                return $this->sendError('Image not found', 404);
            }

            // Remove image from storage
            $filePath = storage_path("app/public/uploads/{$image->img}");
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $image->delete();

            return $this->sendResponse([], 200, 'Image deleted successfully');
        } catch (Exception $e) {
            return $this->sendError('Failed to delete image', 500, [$e->getMessage()]);
        }
    }
}
