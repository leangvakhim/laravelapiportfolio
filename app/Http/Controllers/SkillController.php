<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkillRequest;
use App\Models\Skill;
use Exception;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/skill",
     *     tags={"Skill"},
     *     summary="Get all skill",
     *     @OA\Response(
     *         response=200,
     *         description="List of skill"
     *     )
     * )
     */
    public function index()
    {
        try {
            $skill = Skill::where('active', 1)->get();

            return $this->sendResponse($skill);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve skill', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/skill/{id}",
     *     tags={"Skill"},
     *     summary="Get an skill by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Skill retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Skill not found"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $skill = Skill::where('sk_id', $id)->where('active', 1)->first();

            if (!$skill) {
                return $this->sendResponse(!$skill, 404, 'Skill not found');
            }

            return $this->sendResponse($skill);
        } catch (Exception $e) {
            return $this->sendError('Failed to retrieve skill', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/skill",
     *     tags={"Skill"},
     *     summary="Create a new skill",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"sk_title", "sk_per", "display"},
     *             @OA\Property(property="sk_title", type="string"),
     *             @OA\Property(property="sk_per", type="integer"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Skill created successfully"
     *     )
     * )
     */
    public function create(SkillRequest $request)
    {
        try {
            $data = $request->validated();

            $data['active'] = 1;

            $skill = Skill::create($data);

            return $this->sendResponse($skill, 201, 'Skill created');
        } catch (Exception $e) {
            return $this->sendError('Failed to create skill', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/skill/{id}",
     *     tags={"Skill"},
     *     summary="Update a skill by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="sk_title", type="string"),
     *             @OA\Property(property="sk_per", type="integer"),
     *             @OA\Property(property="display", type="integer"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Skill updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Skill not found"
     *     )
     * )
     */
    public function update(SkillRequest $request, $id)
    {
        try {
            $skill = Skill::find($id);

            if (!$skill) {
                return $this->sendResponse(!$skill, 404, 'Skill not found');
            }

            $validated = $request->validated();

            $skill->update($validated);

            return $this->sendResponse($skill, 200, "Update successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to update skill', 500, [$e->getMessage()]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/skill/{id}",
     *     tags={"Skill"},
     *     summary="Delete an skill by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="delete skill successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Skill not found"
     *     )
     * )
     */
    public function visible($id)
    {
        try {
            $skill = Skill::find($id);
            if (!$skill) {
                return $this->sendError('Skill Not found', 404);
            }

            $skill->active = $skill->active == 1 ? 0 : 1;
            $skill->save();

            return $this->sendResponse([], 200, "Skill delete successful");
        } catch (Exception $e) {
            return $this->sendError('Failed to delete the skill', 500, [$e->getMessage()]);
        }
    }
}
