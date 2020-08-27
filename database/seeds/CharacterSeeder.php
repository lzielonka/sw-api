<?php

use App\Models\Character;
use App\Repositories\CharacterRepository;
use Illuminate\Database\Seeder;

class CharacterSeeder extends Seeder
{
    /**
     * @var CharacterRepository
     */
    private $characterRepository;
    private $data = [
        'Luke Skywalker',
        'Darth Vader',
        'Han Solo',
        'Leia Organa',
        'Wilhuff Tarkin',
        'C-3PO',
        'R2-D2',
    ];

    public function __construct(CharacterRepository $characterRepository)
    {
        $this->characterRepository = $characterRepository;
    }

    public function run()
    {
        foreach ($this->data as $character) {
            $character = new Character([
                'name' => $character,
            ]);
            $this->characterRepository->save($character);
        }
    }
}
