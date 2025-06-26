<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailRequest;
use App\Models\Email;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/email",
     *     tags={"Email"},
     *     summary="Get all email",
     *     @OA\Response(
     *         response=200,
     *         description="List of email"
     *     )
     * )
     */
    public function index()
    {
        try {
            $email = Email::where('active', 1)->get();

            return $this->sendResponse($email);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve email', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/email/{id}",
     *     tags={"Email"},
     *     summary="Get an email by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $email = Email::where('e_id', $id)->where('active', 1)->first();

            if (!$email) {
                return $this->sendResponse(!$email, 404, 'Email not found');
            }

            return $this->sendResponse($email);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve email', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/email",
     *     tags={"Email"},
     *     summary="Create a new email",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"e_name", "e_email", "e_detail"},
     *             @OA\Property(property="e_name", type="string"),
     *             @OA\Property(property="e_email", type="string"),
     *             @OA\Property(property="e_detail", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Email created successfully"
     *     )
     * )
     */
    public function create(EmailRequest $request)
    {
        try {
            $data = $request->validated();

            $data['active'] = 1;

            $email = Email::create($data);

            return $this->sendResponse($email, 201, 'Email created');
        } catch (Exception $e) {
            return $this->sendError('Failed to create email', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/email/{id}",
     *     tags={"Email"},
     *     summary="Update a email by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="e_name", type="string"),
     *             @OA\Property(property="e_email", type="string"),
     *             @OA\Property(property="e_detail", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email not found"
     *     )
     * )
     */
    public function update(EmailRequest $request, $id)
    {
        try {
            $email = Email::find($id);

            if (!$email) {
                return $this->sendResponse(!$email, 404, 'Email not found');
            }

            $validated = $request->validated();

            $email->update($validated);

            return $this->sendResponse($email, 200, "Update successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to update email', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/email/{id}",
     *     tags={"Email"},
     *     summary="Delete an email by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="delete email successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email not found"
     *     )
     * )
     */
    public function visible(Request $request)
    {
        try {
            $ids = $request->input('e_id');

            if (!is_array($ids)) {
                return $this->sendError('Invalid input. Array of IDs required.', 422);
            }

            $emails = Email::whereIn('e_id', $ids)->get();

            if ($emails->isEmpty()) {
                return $this->sendError('No matching emails found', 404);
            }

            foreach ($emails as $email) {
                $email->active = $email->active ? 0 : 1;
                $email->save();
            }

            return $this->sendResponse([], 200, 'Delete for selected emails');
        } catch (Exception $e) {
            return $this->sendError('Failed to delete the emails', 500, ['error' => $e->getMessage()]);
        }
    }
}
