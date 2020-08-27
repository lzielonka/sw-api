<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Planet;
use App\Repositories\PlanetRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PlanetController extends Controller
{
    /**
     * @var PlanetRepository
     */
    private $planetRepository;

    public function __construct(PlanetRepository $planetRepository)
    {
        $this->planetRepository = $planetRepository;
    }

    public function index(Request $request)
    {
        $pageSize = (int)$request->get('pageSize', 1);

        return response()->json($this->planetRepository->paginate($pageSize), Response::HTTP_OK);
    }

    public function show(int $id)
    {
        $planet = $this->planetRepository->findById($id);

        return response()->json($planet, $planet ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $planet = new Planet([
            'name' => $request->input('name'),
        ]);

        if ($this->planetRepository->save($planet)) {
            return response()->json($planet, Response::HTTP_OK);
        }

        return response()->json(['error' => ['Could not create planet']], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function update(Request $request, Planet $planet)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => collect($validator->errors())->flatten()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $planet = $planet->fill([
            'name'      => $request->input('name'),
        ]);

        if ($this->planetRepository->save($planet)) {
            return response()->json($planet, Response::HTTP_OK);
        }

        return response()->json(['error' => ['Could not update planet']], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function destroy(Planet $planet)
    {
        try {
            if (!$this->planetRepository->delete($planet)) {
                return response()->json(['error' => ['Could not delete planet']], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => ['Could not delete planet']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(null, Response::HTTP_OK);
    }
}
