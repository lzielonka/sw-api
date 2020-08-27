<?php

use App\Models\Episode;
use App\Repositories\EpisodeRepository;
use Illuminate\Database\Seeder;

class EpisodeSeeder extends Seeder
{
    /**
     * @var EpisodeRepository
     */
    private $episodeRepository;
    private $data = [
        'NEWHOPE',
        'EMPIRE',
        'JEDI',
    ];

    public function __construct(EpisodeRepository $episodeRepository)
    {
        $this->episodeRepository = $episodeRepository;
    }

    public function run()
    {
        foreach ($this->data as $Episode) {
            $Episode = new Episode([
                'name' => $Episode,
            ]);
            $this->episodeRepository->save($Episode);
        }
    }
}
