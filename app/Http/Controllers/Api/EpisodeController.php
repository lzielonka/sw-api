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
    public function index(Request $request)
    {
        $pageSize = (int)$request->get('pageSize', 1);

        return response()->json($this->episodeRepository->paginate($pageSize), Response::HTTP_OK);
    }

    public function show(int $id)
    {
        $episode = $this->episodeRepository->findById($id);

        return response()->json($episode, $episode ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    public function create(Request $request)
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
            return response()->json($episode, Response::HTTP_OK);
        }

        return response()->json(['error' => ['Could not create episode']], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

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

    public function addCharacter(Episode $episode, Character $character)
    {
        $this->episodeRepository->addCharacter($episode, $character);

        return response()->json($this->episodeRepository->findById($episode->getKey()), Response::HTTP_OK);
    }

    public function removeCharacter(Episode $episode, Character $character)
    {
        $this->episodeRepository->removeCharacter($episode, $character);

        return response()->json($this->episodeRepository->findById($episode->getKey()), Response::HTTP_OK);    }
}
