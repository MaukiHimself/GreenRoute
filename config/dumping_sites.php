<?php

/*
|--------------------------------------------------------------------------
| Waste Dumping / Disposal Sites (Dar es Salaam)
|--------------------------------------------------------------------------
|
| Destinations used at the END of an optimised collection route:
|     contractor base  ->  clients (optimised order)  ->  dumping site
|
| 'is_open' controls both the map (green = open, grey = closed) and which
| sites a contractor may pick for routing (only open sites are selectable).
| Currently only Pugu Kinyamwezi is operational; the rest are closed.
|
| Edit coordinates / status here — nothing else needs to change.
|
*/

return [

    'sites' => [
        [
            'name'      => 'Pugu Kinyamwezi Dumpsite',
            'latitude'  => -6.9333,
            'longitude' => 39.1333,
            'is_open'   => true,
            'description' => "Dar es Salaam's main official solid-waste site, Kinyamwezi, Pugu ward, Ilala (~30 km west).",
        ],
        [
            'name'      => 'Mtoni Dumpsite',
            'latitude'  => -6.8728,
            'longitude' => 39.3010,
            'is_open'   => false,
            'description' => 'Closed.',
        ],
        [
            'name'      => 'Vingunguti Dumpsite',
            'latitude'  => -6.8476,
            'longitude' => 39.2294,
            'is_open'   => false,
            'description' => 'Closed.',
        ],
        [
            'name'      => 'Tabata Dumpsite',
            'latitude'  => -6.8167,
            'longitude' => 39.2167,
            'is_open'   => false,
            'description' => 'Closed.',
        ],
        [
            'name'      => 'Kigogo Dumpsite',
            'latitude'  => -6.7920,
            'longitude' => 39.2550,
            'is_open'   => false,
            'description' => 'Closed.',
        ],
        [
            'name'      => 'Kunduchi Dumpsite',
            'latitude'  => -6.6700,
            'longitude' => 39.2200,
            'is_open'   => false,
            'description' => 'Closed.',
        ],
        [
            'name'      => 'Magomeni Dumpsite',
            'latitude'  => -6.8000,
            'longitude' => 39.2550,
            'is_open'   => false,
            'description' => 'Closed.',
        ],
        [
            'name'      => 'Mchikichini Dumpsite',
            'latitude'  => -6.8210,
            'longitude' => 39.2690,
            'is_open'   => false,
            'description' => 'Closed.',
        ],
    ],

];
