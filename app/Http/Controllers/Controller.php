<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Laravel Star Wars Api",
     *      description="L5 Swagger OpenApi description",
     *      @OA\Contact(
     *          email="admin@admin.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=HOST,
     *      description="Laravel Star Wars Api"
     * )
     *
     * @OA\Tag(
     *     name="Characters",
     *     description="API Endpoints of Characters"
     * )
     *
     * @OA\Tag(
     *     name="Planets",
     *     description="API Endpoints of Planets"
     * )
     *
     * @OA\Tag(
     *     name="Episodes",
     *     description="API Endpoints of Episodes"
     * )
     */
}
