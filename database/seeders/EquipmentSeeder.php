<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Seed a catalogue of waste-management equipment for every contractor.
     * Prices are indicative (TZS) — the contractor edits price + uploads a
     * photo per item from the Equipment page.
     */
    public function run(): void
    {
        $catalogue = [
            // Bins & containers
            ['Wheelie Bin 120L', 'Standard household wheeled bin with hinged lid, ideal for residential waste.', 85000, 'each', 'Bins & Containers', 'Capacity: 120L; Material: HDPE; Wheels: 2'],
            ['Wheelie Bin 240L', 'Large wheeled bin for families or small businesses.', 130000, 'each', 'Bins & Containers', 'Capacity: 240L; Material: HDPE; Wheels: 2'],
            ['Wheelie Bin 1100L', 'Heavy-duty 4-wheel commercial container with flat lid.', 780000, 'each', 'Bins & Containers', 'Capacity: 1100L; Material: HDPE/steel; Wheels: 4'],
            ['Metal Skip Bin 6 Yard', 'Open steel skip for construction and bulk waste.', 2500000, 'each', 'Bins & Containers', 'Capacity: 6yd³; Material: Steel; Type: Open top'],
            ['Recycling Bin Set (3-Bin)', 'Colour-coded 3-bin set for paper, plastic and glass separation.', 210000, 'set', 'Bins & Containers', 'Bins: 3 x 60L; Colours: Blue/Green/Yellow'],
            ['Kitchen Caddy 7L', 'Small ventilated food-waste caddy for households.', 15000, 'each', 'Bins & Containers', 'Capacity: 7L; Ventilated: Yes'],
            ['Street Litter Bin 80L', 'Public litter bin with post-mount and lockable lid.', 175000, 'each', 'Bins & Containers', 'Capacity: 80L; Mount: Post/wall'],

            // Collection & transport
            ['Pushcart / Handcart', 'Manual two-wheel cart for narrow-street door-to-door collection.', 320000, 'each', 'Collection & Transport', 'Wheels: 2; Frame: Galvanised steel'],
            ['Tricycle Waste Collector', 'Motorised three-wheeler with tipping bucket for local collection.', 6500000, 'each', 'Collection & Transport', 'Engine: 150cc; Load: ~500kg'],
            ['Roll-off Container 20 Yard', 'Large roll-off container for skip-loader trucks.', 4200000, 'each', 'Collection & Transport', 'Capacity: 20yd³; Material: Steel'],
            ['240L Bin Trolley', 'Wheeled trolley to move multiple bins around a site.', 240000, 'each', 'Collection & Transport', 'Load: up to 4 bins'],

            // Safety / PPE
            ['Heavy-Duty Work Gloves', 'Cut-resistant reusable gloves for waste handlers.', 12000, 'pair', 'Safety & PPE', 'Material: Nitrile-coated; Size: L/XL'],
            ['Reflective Safety Vest', 'High-visibility vest for roadside collection crews.', 18000, 'each', 'Safety & PPE', 'Colour: Orange; Class: 2'],
            ['Safety Boots (Steel Toe)', 'Waterproof steel-toe boots for field crews.', 65000, 'pair', 'Safety & PPE', 'Toe: Steel; Waterproof: Yes'],
            ['Dust / N95 Face Mask (Box)', 'Box of disposable respirator masks.', 25000, 'box', 'Safety & PPE', 'Qty: 20/box; Rating: N95'],
            ['Face Shield', 'Reusable protective face shield.', 9000, 'each', 'Safety & PPE', 'Material: Polycarbonate'],
            ['First Aid Kit', 'Workplace first-aid kit for collection vehicles.', 45000, 'each', 'Safety & PPE', 'Type: Vehicle/site'],

            // Cleaning & tools
            ['Industrial Broom', 'Heavy-duty stiff-bristle broom for yards and streets.', 14000, 'each', 'Cleaning & Tools', 'Width: 60cm; Bristle: Stiff'],
            ['Litter Picker / Grabber', 'Long-reach grabber to collect litter without bending.', 11000, 'each', 'Cleaning & Tools', 'Length: 80cm'],
            ['Refuse Bag Roll (Heavy Duty)', 'Roll of thick black bin liners for general waste.', 20000, 'roll', 'Cleaning & Tools', 'Qty: 50/roll; Size: 90x110cm'],
            ['Compostable Bags (Roll)', 'Biodegradable liners for food/organic waste.', 28000, 'roll', 'Cleaning & Tools', 'Qty: 50/roll; Compostable: Yes'],
            ['Pressure Washer', 'Petrol pressure washer for cleaning bins and vehicles.', 850000, 'each', 'Cleaning & Tools', 'Pressure: 150bar; Fuel: Petrol'],
            ['Weighing Scale (Platform)', 'Digital platform scale to weigh collected waste.', 480000, 'each', 'Cleaning & Tools', 'Capacity: 300kg; Display: Digital'],
            ['Wheelbarrow', 'Heavy-duty wheelbarrow for moving loose waste on site.', 95000, 'each', 'Cleaning & Tools', 'Capacity: 90L; Wheel: Pneumatic'],
        ];

        $contractors = User::where('user_type', 'contractor')->get();

        if ($contractors->isEmpty()) {
            $this->command?->warn('No contractors found — skipping EquipmentSeeder.');
            return;
        }

        foreach ($contractors as $contractor) {
            foreach ($catalogue as [$name, $description, $price, $unit, $category, $specs]) {
                Product::updateOrCreate(
                    ['contractor_id' => $contractor->id, 'name' => $name],
                    [
                        'description'    => $description,
                        'price'          => $price,
                        'unit'           => $unit,
                        'category'       => $category,
                        'specifications' => $specs,
                        'is_available'   => true,
                    ]
                );
            }
        }

        $this->command?->info('Seeded ' . count($catalogue) . ' equipment items for ' . $contractors->count() . ' contractor(s).');
    }
}
