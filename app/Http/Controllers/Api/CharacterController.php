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
    public function index(Request $request)
    {
        $pageSize = (int)$request->get('pageSize', 1);

        return response()->json($this->characterRepository->paginate($pageSize), Response::HTTP_OK);
    }

    public function show(int $id)
    {
        $character = $this->characterRepository->findById($id);

        return response()->json($character, $character ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    public function create(Request $request)
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
            return response()->json($character, Response::HTTP_OK);
        }

        return response()->json(['error' => ['Could not update character']], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

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

    public function addFriend(Character $character, Character $friend)
    {
        $this->characterRepository->addFriend($character, $friend);

        return response()->json($this->characterRepository->findById($character->getKey()), Response::HTTP_OK);
    }

    public function removeFriend(Character $character, Character $friend)
    {
        $this->characterRepository->removeFriend($character, $friend);

        return response()->json($this->characterRepository->findById($character->getKey()), Response::HTTP_OK);
    }
}
