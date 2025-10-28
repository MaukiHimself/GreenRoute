<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleLocationSeeder extends Seeder
{
    /**
     * Seed sample Tanzanian locations for testing
     */
    public function run(): void
    {
        $this->command->info('Seeding sample Tanzanian locations...');
        
        $locations = [
            // Dar es Salaam Region
            ['region' => 'DAR ES SALAAM', 'district' => 'ILALA', 'ward' => 'KIVUKONI', 'street' => 'SAMORA AVENUE'],
            ['region' => 'DAR ES SALAAM', 'district' => 'ILALA', 'ward' => 'KIVUKONI', 'street' => 'AZIKIWE STREET'],
            ['region' => 'DAR ES SALAAM', 'district' => 'ILALA', 'ward' => 'KARIAKOO', 'street' => 'MKUNGUNI STREET'],
            ['region' => 'DAR ES SALAAM', 'district' => 'ILALA', 'ward' => 'KARIAKOO', 'street' => 'TANDAMUTI STREET'],
            ['region' => 'DAR ES SALAAM', 'district' => 'KINONDONI', 'ward' => 'SINZA', 'street' => 'MOROGORO ROAD'],
            ['region' => 'DAR ES SALAAM', 'district' => 'KINONDONI', 'ward' => 'SINZA', 'street' => 'SAM NUJOMA ROAD'],
            ['region' => 'DAR ES SALAAM', 'district' => 'KINONDONI', 'ward' => 'MIKOCHENI', 'street' => 'MWAI KIBAKI ROAD'],
            ['region' => 'DAR ES SALAAM', 'district' => 'KINONDONI', 'ward' => 'MIKOCHENI', 'street' => 'HAILE SELASSIE ROAD'],
            ['region' => 'DAR ES SALAAM', 'district' => 'TEMEKE', 'ward' => 'MBAGALA', 'street' => 'MBAGALA RANGI TATU'],
            ['region' => 'DAR ES SALAAM', 'district' => 'TEMEKE', 'ward' => 'TEMEKE', 'street' => 'TEMEKE MWISHO'],
            
            // Mwanza Region
            ['region' => 'MWANZA', 'district' => 'ILEMELA', 'ward' => 'NYAMAGANA', 'street' => 'STATION ROAD'],
            ['region' => 'MWANZA', 'district' => 'ILEMELA', 'ward' => 'NYAMAGANA', 'street' => 'KENYATTA ROAD'],
            ['region' => 'MWANZA', 'district' => 'ILEMELA', 'ward' => 'BUTIMBA', 'street' => 'BUTIMBA MJINI'],
            ['region' => 'MWANZA', 'district' => 'NYAMAGANA', 'ward' => 'PAMBA', 'street' => 'PAMBA A'],
            ['region' => 'MWANZA', 'district' => 'NYAMAGANA', 'ward' => 'PAMBA', 'street' => 'PAMBA B'],
            ['region' => 'MWANZA', 'district' => 'KWIMBA', 'ward' => 'FUKALO', 'street' => 'FUKALO MJINI'],
            ['region' => 'MWANZA', 'district' => 'KWIMBA', 'ward' => 'ILULA', 'street' => 'MANAYI'],
            ['region' => 'MWANZA', 'district' => 'KWIMBA', 'ward' => 'BUGANDO', 'street' => 'BUGANDO HOSPITAL'],
            
            // Arusha Region
            ['region' => 'ARUSHA', 'district' => 'ARUSHA CITY', 'ward' => 'KALOLENI', 'street' => 'SOKOINE ROAD'],
            ['region' => 'ARUSHA', 'district' => 'ARUSHA CITY', 'ward' => 'KALOLENI', 'street' => 'MAKONGORO ROAD'],
            ['region' => 'ARUSHA', 'district' => 'ARUSHA CITY', 'ward' => 'SOMBETINI', 'street' => 'SOMBETINI MJINI'],
            ['region' => 'ARUSHA', 'district' => 'ARUMERU', 'ward' => 'MERU', 'street' => 'USA RIVER'],
            ['region' => 'ARUSHA', 'district' => 'ARUMERU', 'ward' => 'MERU', 'street' => 'TENGERU'],
            
            // Dodoma Region
            ['region' => 'DODOMA', 'district' => 'DODOMA CITY', 'ward' => 'KIKUYU KUSINI', 'street' => 'KIKUYU'],
            ['region' => 'DODOMA', 'district' => 'DODOMA CITY', 'ward' => 'HAZINA', 'street' => 'MTUMBA'],
            ['region' => 'DODOMA', 'district' => 'DODOMA CITY', 'ward' => 'NKUHUNGU', 'street' => 'NKUHUNGU MJINI'],
            ['region' => 'DODOMA', 'district' => 'BAHI', 'ward' => 'CHALI', 'street' => 'CHALI MAKULU'],
            ['region' => 'DODOMA', 'district' => 'BAHI', 'ward' => 'ILINDI', 'street' => 'MINDOLA'],
            
            // Kilimanjaro Region
            ['region' => 'KILIMANJARO', 'district' => 'MOSHI MUNICIPAL', 'ward' => 'KIBORILONI', 'street' => 'MOSHI TOWN'],
            ['region' => 'KILIMANJARO', 'district' => 'MOSHI MUNICIPAL', 'ward' => 'KIBORILONI', 'street' => 'KIBORILONI'],
            ['region' => 'KILIMANJARO', 'district' => 'HAI', 'ward' => 'MACHAME', 'street' => 'MACHAME MJINI'],
            ['region' => 'KILIMANJARO', 'district' => 'ROMBO', 'ward' => 'MENGWE', 'street' => 'MENGWE MJINI'],
            
            // Mbeya Region
            ['region' => 'MBEYA', 'district' => 'MBEYA CITY', 'ward' => 'ILOMBA', 'street' => 'ILOMBA MJINI'],
            ['region' => 'MBEYA', 'district' => 'MBEYA CITY', 'ward' => 'IYUNGA', 'street' => 'IYUNGA MJINI'],
            ['region' => 'MBEYA', 'district' => 'MBEYA CITY', 'ward' => 'MWANJELWA', 'street' => 'MWANJELWA MJINI'],
            
            // Morogoro Region
            ['region' => 'MOROGORO', 'district' => 'MOROGORO MUNICIPAL', 'ward' => 'KINGOLWIRA', 'street' => 'KINGOLWIRA'],
            ['region' => 'MOROGORO', 'district' => 'MOROGORO MUNICIPAL', 'ward' => 'MAZIMBU', 'street' => 'MAZIMBU'],
            ['region' => 'MOROGORO', 'district' => 'MOROGORO MUNICIPAL', 'ward' => 'KIHONDA', 'street' => 'KIHONDA MJINI'],
            
            // Tanga Region
            ['region' => 'TANGA', 'district' => 'TANGA CITY', 'ward' => 'USAGARA', 'street' => 'USAGARA MJINI'],
            ['region' => 'TANGA', 'district' => 'TANGA CITY', 'ward' => 'CHUMBAGENI', 'street' => 'CHUMBAGENI'],
            ['region' => 'TANGA', 'district' => 'TANGA CITY', 'ward' => 'NGAMIANI KASKAZINI', 'street' => 'NGAMIANI'],
        ];
        
        foreach ($locations as $location) {
            DB::table('tbl_locations')->insert($location);
        }
        
        $count = DB::table('tbl_locations')->count();
        $this->command->info("✓ Seeded {$count} sample locations");
        
        // Show summary
        $regions = DB::table('tbl_locations')->distinct('region')->count('region');
        $districts = DB::table('tbl_locations')->distinct('district')->count('district');
        
        $this->command->info("  Regions: {$regions}");
        $this->command->info("  Districts: {$districts}");
    }
}
