<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Episode;
use App\Repositories\EpisodeRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class EpisodeController extends Controller
{
    /**
     * @var EpisodeRepository
     */
    private $episodeRepository;

    public function __construct(EpisodeRepository $episodeRepository)
    {
        $this->episodeRepository = $episodeRepository;
    }

    /**
     * @OA\Get(
     *      path="/api/episodes",
     *      tags={"Episodes"},
     *      summary="Get paginated list of episodes",
     *      description="Returns paginated list of episodes",
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

        return response()->json($this->episodeRepository->paginate($pageSize), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *      path="/api/episodes/{id}",
     *      tags={"Episodes"},
     *      summary="Get episode information",
     *      description="Returns episode data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Episode id",
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
        $episode = $this->episodeRepository->findById($id);

        return response()->json($episode, $episode ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/episodes",
     *      tags={"Episodes"},
     *      summary="Store new episode",
     *      description="Returns episode data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *            @OA\Property(property="name", type="string", example="LASTJEDI"),
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
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $episode = new Episode([
            'name' => $request->input('name'),
        ]);

        if ($this->episodeRepository->save($episode)) {
            return response()->json($this->episodeRepository->findById($episode->getKey()), Response::HTTP_OK);
        }

        return response()->json(['error' => ['Could not create episode']], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @OA\Put(
     *      path="/api/episodes/{id}",
     *      tags={"Episodes"},
     *      summary="Update existing episode",
     *      description="Returns updated episode data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Episode id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *            @OA\Property(property="name", type="string", example="LASTJEDI"),
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
    public function update(Request $request, Episode $episode)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $episode = $episode->fill([
            'name' => $request->input('name'),
        ]);

        if ($this->episodeRepository->save($episode)) {
            return response()->json($this->episodeRepository->findById($episode->getKey()), Response::HTTP_OK);
        }

        return response()->json(['error' => ['Could not update episode']], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @OA\Delete(
     *      path="/api/episodes/{id}",
     *      tags={"Episodes"},
     *      summary="Delete existing episode",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Episode id",
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
    public function destroy(Episode $episode)
    {
        try {
            if (!$this->episodeRepository->delete($episode)) {
                return response()->json(['error' => ['Could not delete episode']], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => ['Could not delete episode']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/api/episodes/{episode_id}/characters/{character_id}",
     *      tags={"Episodes"},
     *      summary="Add a character to episode",
     *      description="Returns episode data",
     *      @OA\Parameter(
     *          name="episode_id",
     *          description="Episode id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="character_id",
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
    public function addCharacter(Episode $episode, Character $character)
    {
        $this->episodeRepository->addCharacter($episode, $character);

        return response()->json($this->episodeRepository->findById($episode->getKey()), Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *      path="/api/episodes/{episode_id}/characters/{character_id}",
     *      tags={"Episodes"},
     *      summary="Remove a character from episode",
     *      description="Returns episode data",
     *      @OA\Parameter(
     *          name="episode_id",
     *          description="Episode id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="character_id",
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
    public function removeCharacter(Episode $episode, Character $character)
    {
        $this->episodeRepository->removeCharacter($episode, $character);

        return response()->json($this->episodeRepository->findById($episode->getKey()), Response::HTTP_OK);    }
}
