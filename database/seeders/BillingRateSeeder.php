<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BillingRate;

class BillingRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Pricing structure based on GreenRoute ORBIT by-laws
     * All prices in Tanzanian Shillings (TZS)
     */
    public function run(): void
    {
        $billingRates = [
            // RESIDENTIAL CATEGORIES
            [
                'category' => 'Residential (Unplanned)',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Unplanned residential properties',
                'is_active' => true
            ],
            [
                'category' => 'Residential (Planned/Modern)',
                'location' => 'General',
                'collection_fee' => 20000,
                'frequency' => 'monthly',
                'description' => 'Planned or modern residential properties',
                'is_active' => true
            ],

            // COMMERCIAL RESIDENTIAL
            [
                'category' => 'Commercial Residential (Apartment)',
                'location' => 'General',
                'collection_fee' => 30000,
                'frequency' => 'monthly',
                'description' => 'Commercial residential apartments',
                'is_active' => true
            ],
            [
                'category' => 'Commercial Residential Storey',
                'location' => 'General',
                'collection_fee' => 80000,
                'frequency' => 'monthly',
                'description' => 'Commercial residential storey buildings',
                'is_active' => true
            ],
            [
                'category' => 'Commercial Residential above 2 storey',
                'location' => 'General',
                'collection_fee' => 100000,
                'frequency' => 'monthly',
                'description' => 'Commercial residential buildings above 2 storeys',
                'is_active' => true
            ],
            [
                'category' => 'Commercial Industrial & Institutions',
                'location' => 'General',
                'collection_fee' => 150000,
                'frequency' => 'monthly',
                'description' => 'Commercial industrial and institutional properties',
                'is_active' => true
            ],

            // FOOD & BEVERAGE
            [
                'category' => 'Tea Room',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Tea room establishments',
                'is_active' => true
            ],
            [
                'category' => 'Café',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Café establishments',
                'is_active' => true
            ],
            [
                'category' => 'Ice Par Lour',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Ice parlour establishments',
                'is_active' => true
            ],
            [
                'category' => 'Restaurant',
                'location' => 'General',
                'collection_fee' => 15000,
                'frequency' => 'monthly',
                'description' => 'Restaurant establishments',
                'is_active' => true
            ],
            [
                'category' => 'Bar',
                'location' => 'General',
                'collection_fee' => 15000,
                'frequency' => 'monthly',
                'description' => 'Bar establishments',
                'is_active' => true
            ],
            [
                'category' => 'Butcher',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Butcher shops',
                'is_active' => true
            ],

            // ACCOMMODATION
            [
                'category' => 'Guest House',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Guest house facilities',
                'is_active' => true
            ],
            [
                'category' => 'Hotels',
                'location' => 'General',
                'collection_fee' => 150000,
                'frequency' => 'monthly',
                'description' => 'Hotel establishments',
                'is_active' => true
            ],

            // HEALTHCARE
            [
                'category' => 'Dispensary (domestic waste)',
                'location' => 'General',
                'collection_fee' => 15000,
                'frequency' => 'monthly',
                'description' => 'Dispensary facilities - domestic waste only',
                'is_active' => true
            ],
            [
                'category' => 'Health Centre (Domestic waste)',
                'location' => 'General',
                'collection_fee' => 20000,
                'frequency' => 'monthly',
                'description' => 'Health centre facilities - domestic waste only',
                'is_active' => true
            ],
            [
                'category' => 'Hospital (Domestic waste)',
                'location' => 'General',
                'collection_fee' => 35000,
                'frequency' => 'monthly',
                'description' => 'Hospital facilities - domestic waste only',
                'is_active' => true
            ],

            // MANUFACTURING & WORKSHOPS
            [
                'category' => 'Sawing mills',
                'location' => 'General',
                'collection_fee' => 35000,
                'frequency' => 'monthly',
                'description' => 'Sawing mill operations',
                'is_active' => true
            ],
            [
                'category' => 'Furniture making',
                'location' => 'General',
                'collection_fee' => 22000,
                'frequency' => 'monthly',
                'description' => 'Furniture manufacturing businesses',
                'is_active' => true
            ],
            [
                'category' => 'Metal workshops',
                'location' => 'General',
                'collection_fee' => 22000,
                'frequency' => 'monthly',
                'description' => 'Metal workshop operations',
                'is_active' => true
            ],

            // INDUSTRIES
            [
                'category' => 'Industries (Light waste)',
                'location' => 'General',
                'collection_fee' => 35000,
                'frequency' => 'monthly',
                'description' => 'Light industrial operations',
                'is_active' => true
            ],
            [
                'category' => 'Industries (Heavy Industries)',
                'location' => 'General',
                'collection_fee' => 40000,
                'frequency' => 'monthly',
                'description' => 'Heavy industrial operations',
                'is_active' => true
            ],

            // RETAIL & SHOPS
            [
                'category' => 'Wholesale shops (general)',
                'location' => 'General',
                'collection_fee' => 15000,
                'frequency' => 'monthly',
                'description' => 'General wholesale shops',
                'is_active' => true
            ],
            [
                'category' => 'Retail shops (food and other items)',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Retail shops selling food and other items',
                'is_active' => true
            ],
            [
                'category' => 'Retail shops (other commodities)',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Retail shops selling other commodities',
                'is_active' => true
            ],
            [
                'category' => 'Groceries',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Grocery stores',
                'is_active' => true
            ],
            [
                'category' => 'Pharmacy',
                'location' => 'General',
                'collection_fee' => 15000,
                'frequency' => 'monthly',
                'description' => 'Pharmacy establishments',
                'is_active' => true
            ],

            // EDUCATION
            [
                'category' => 'Private Day Primary School',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Private day primary schools',
                'is_active' => true
            ],
            [
                'category' => 'Private Boarding Secondary schools (Standard)',
                'location' => 'General',
                'collection_fee' => 15000,
                'frequency' => 'monthly',
                'description' => 'Private boarding secondary schools - standard service',
                'is_active' => true
            ],
            [
                'category' => 'Private Day Secondary schools',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Private day secondary schools',
                'is_active' => true
            ],
            [
                'category' => 'Private Boarding Secondary schools (Full Service)',
                'location' => 'General',
                'collection_fee' => 25000,
                'frequency' => 'monthly',
                'description' => 'Private boarding secondary schools - full service package',
                'is_active' => true
            ],
            [
                'category' => 'Institution per month',
                'location' => 'General',
                'collection_fee' => 25000,
                'frequency' => 'monthly',
                'description' => 'General institutional facilities',
                'is_active' => true
            ],

            // MARKETS & VENDORS
            [
                'category' => 'Markets',
                'location' => 'General',
                'collection_fee' => 50000,
                'frequency' => 'monthly',
                'description' => 'Market facilities',
                'is_active' => true
            ],
            [
                'category' => 'Street Market (Magenge) per table',
                'location' => 'General',
                'collection_fee' => 2000,
                'frequency' => 'monthly',
                'description' => 'Street market vendors per table',
                'is_active' => true
            ],
            [
                'category' => 'Food vendors (Mama ntilie)',
                'location' => 'General',
                'collection_fee' => 5000,
                'frequency' => 'monthly',
                'description' => 'Food vendors (Mama ntilie)',
                'is_active' => true
            ],

            // TRANSPORT
            [
                'category' => 'Bus stations (per bus per day)',
                'location' => 'General',
                'collection_fee' => 5000,
                'frequency' => 'daily',
                'description' => 'Bus stations - per bus per day',
                'is_active' => true
            ],
            [
                'category' => 'Petrol stations',
                'location' => 'General',
                'collection_fee' => 30000,
                'frequency' => 'monthly',
                'description' => 'Petrol station facilities',
                'is_active' => true
            ],
            [
                'category' => 'Garage',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Garage facilities',
                'is_active' => true
            ],

            // RELIGIOUS & PUBLIC
            [
                'category' => 'Mosque/church',
                'location' => 'General',
                'collection_fee' => 20000,
                'frequency' => 'monthly',
                'description' => 'Mosque and church facilities',
                'is_active' => true
            ],
            [
                'category' => 'Offices',
                'location' => 'General',
                'collection_fee' => 100000,
                'frequency' => 'monthly',
                'description' => 'Office buildings',
                'is_active' => true
            ],

            // INFORMAL SECTOR
            [
                'category' => 'Informal dry cleaners, tailors',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Informal dry cleaners and tailors',
                'is_active' => true
            ],
            [
                'category' => 'Informal Carpenter',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Informal carpenter workshops',
                'is_active' => true
            ],
            [
                'category' => 'Shoe makers',
                'location' => 'General',
                'collection_fee' => 5000,
                'frequency' => 'monthly',
                'description' => 'Shoe maker businesses',
                'is_active' => true
            ],
            [
                'category' => 'Electronic gadgets repair',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Electronic gadgets repair shops',
                'is_active' => true
            ],
            [
                'category' => 'Street Barbers',
                'location' => 'General',
                'collection_fee' => 10000,
                'frequency' => 'monthly',
                'description' => 'Street barber businesses',
                'is_active' => true
            ],
            [
                'category' => 'Female Saloons',
                'location' => 'General',
                'collection_fee' => 15000,
                'frequency' => 'monthly',
                'description' => 'Female saloon businesses',
                'is_active' => true
            ],

            // STORAGE & CONSTRUCTION
            [
                'category' => 'Warehouses',
                'location' => 'General',
                'collection_fee' => 30000,
                'frequency' => 'monthly',
                'description' => 'Warehouse facilities',
                'is_active' => true
            ],
            [
                'category' => 'Construction waste per trip',
                'location' => 'General',
                'collection_fee' => 25000,
                'frequency' => 'per-trip',
                'description' => 'Construction waste disposal per trip',
                'is_active' => true
            ],
        ];

        // Delete existing rates to avoid duplicates
        BillingRate::truncate();

        // Insert all billing rates
        foreach ($billingRates as $rate) {
            BillingRate::create($rate);
        }

        $this->command->info('✅ Successfully seeded ' . count($billingRates) . ' billing rate categories');
    }
}
