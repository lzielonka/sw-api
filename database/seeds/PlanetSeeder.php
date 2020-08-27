<?php

use App\Models\Planet;
use App\Repositories\PlanetRepository;
use Illuminate\Database\Seeder;

class PlanetSeeder extends Seeder
{
    /**
     * @var PlanetRepository
     */
    private $planetRepository;
    private $data = [
        'Alderaan',
    ];

    public function __construct(PlanetRepository $planetRepository)
    {
        $this->planetRepository = $planetRepository;
    }

    public function run()
    {
        foreach ($this->data as $planet) {
            $planet = new Planet([
                'name' => $planet,
            ]);
            $this->planetRepository->save($planet);
        }
    }
}
