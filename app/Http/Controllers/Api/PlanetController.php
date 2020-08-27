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

    /**
     * @OA\Get(
     *      path="/api/planets",
     *      tags={"Planets"},
     *      summary="Get paginated list of planets",
     *      description="Returns paginated list of planets",
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

        return response()->json($this->planetRepository->paginate($pageSize), Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *      path="/api/planets/{id}",
     *      tags={"Planets"},
     *      summary="Get planet information",
     *      description="Returns planet data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Planet id",
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
        $planet = $this->planetRepository->findById($id);

        return response()->json($planet, $planet ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    /**
     * @OA\Post(
     *      path="/api/planets",
     *      tags={"Planets"},
     *      summary="Store new planet",
     *      description="Returns planet data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *            @OA\Property(property="name", type="string", example="Tatooine"),
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
        $planet = new Planet([
            'name' => $request->input('name'),
        ]);

        if ($this->planetRepository->save($planet)) {
            return response()->json($this->planetRepository->findById($planet->getKey()), Response::HTTP_OK);
        }

        return response()->json(['error' => ['Could not create planet']], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @OA\Put(
     *      path="/api/planet/{id}",
     *      tags={"Planets"},
     *      summary="Update existing planet",
     *      description="Returns updated planet data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Planet id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *            @OA\Property(property="name", type="string", example="Tatooine"),
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

    /**
     * @OA\Delete(
     *      path="/api/planets/{id}",
     *      tags={"Planets"},
     *      summary="Delete existing planet",
     *      description="Deletes a record and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Planet id",
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
