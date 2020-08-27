<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Repositories\CharacterRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CharacterController extends Controller
{
    /**
     * @var CharacterRepository
     */
    private $characterRepository;

    public function __construct(CharacterRepository $characterRepository)
    {
        $this->characterRepository = $characterRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/characters",
     *      tags={"Characters"},
     *      summary="Get paginated list of characters",
     *      description="Returns paginated list of characters",
     *      @OA\Parameter(
     *          name="page",
     *          description="page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="pageSize",
     *          description="results per page",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function index(Request $request)
    {
        $pageSize = (int)$request->get('pageSize', 1);

        return response()->json($this->characterRepository->paginate($pageSize), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *      path="/api/characters/{id}",
     *      operationId="getCharacterById",
     *      tags={"Characters"},
     *      summary="Get character information",
     *      description="Returns character data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Character id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found"
     *      )
     * )
     */
    public function show(int $id)
    {
        $character = $this->characterRepository->findById($id);

        return response()->json($character, $character ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/characters",
     *      operationId="storeCharacter",
     *      tags={"Characters"},
     *      summary="Store new character",
     *      description="Returns character data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *            @OA\Property(property="name", type="string", example="Master Yoda"),
     *            @OA\Property(property="planet_id", type="integer", example=1)
     *        )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'planet_id' => 'sometimes|required|exists:planet,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $character = new Character([
            'name'      => $request->input('name'),
            'planet_id' => $request->input('planet_id'),
        ]);

        if ($this->characterRepository->save($character)) {
            return response()->json($character, Response::HTTP_OK);
        }

        return response()->json(['error' => ['Could not create character']], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @OA\Put(
     *      path="/api/characters/{id}",
     *      operationId="updateCharacter",
     *      tags={"Characters"},
     *      summary="Update existing character",
     *      description="Returns updated character data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Character id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *            @OA\Property(property="name", type="string", example="Master Yoda"),
     *            @OA\Property(property="planet_id", type="integer", example="1")
     *        )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function update(Request $request, Character $character)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
            'planet_id' => 'sometimes|required|exists:planet,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $character = $character->fill([
            'name'      => $request->input('name'),
            'planet_id' => $request->input('planet_id'),
        ]);

        if ($this->characterRepository->save($character)) {
            return response()->json($this->characterRepository->findById($character->getKey()), Response::HTTP_OK);
        }

        return response()->json(['error' => ['Could not update character']], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @OA\Delete(
     *      path="/api/characters/{id}",
     *      operationId="deleteCharacter",
     *      tags={"Characters"},
     *      summary="Delete existing character",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Character id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy(Character $character)
    {
        try {
            if (!$this->characterRepository->delete($character)) {
                return response()->json(['error' => ['Could not delete character']], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => ['Could not delete character']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/api/characters/{character_id}/friends/{friend_id}",
     *      operationId="addFriend",
     *      tags={"Characters"},
     *      summary="Add a friend for character",
     *      description="Returns character data",
     *      @OA\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="friend_id",
     *          description="Character id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      )
     * )
     */
    public function addFriend(Character $character, Character $friend)
    {
        $this->characterRepository->addFriend($character, $friend);

        return response()->json($this->characterRepository->findById($character->getKey()), Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *      path="/api/characters/{character_id}/friends/{friend_id}",
     *      operationId="removeFriend",
     *      tags={"Characters"},
     *      summary="Remove a friend for character",
     *      description="Returns character data",
     *      @OA\Parameter(
     *          name="character_id",
     *          description="Character id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="friend_id",
     *          description="Character id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      )
     * )
     */
    public function removeFriend(Character $character, Character $friend)
    {
        $this->characterRepository->removeFriend($character, $friend);

        return response()->json($this->characterRepository->findById($character->getKey()), Response::HTTP_OK);
    }
}
