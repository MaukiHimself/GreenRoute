<?php

/**
 * Presentation seed #4 — service price list for DENIS MAUKI.
 *
 * Fills the contractor's Service Pricing page (and the client-facing price
 * list) with a realistic Dar es Salaam price card, and makes the price list
 * look *used* by adding two one-off invoices booked against it:
 *
 *  - Bulk collection (Juma Hassan) — paid, with an approved payment.
 *  - Construction debris (Said Bakari) — sent, matching his SMS enquiry
 *    about debris collection in Kimara.
 *
 * Idempotent — wipes and rebuilds only this contractor's service prices and
 * the two one-off invoices (regular monthly invoices are left untouched).
 *
 * Run:  php artisan tinker --execute="require base_path('database/seed_denis_pricing.php');"
 */

use App\Models\User;
use App\Models\Client;
use App\Models\Contractor;
use App\Models\ServicePrice;
use App\Models\Invoice;
use App\Models\PaymentSubmission;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$CONTRACTOR_EMAIL = 'denismauki@greenroute.co.tz';

$user = User::where('email', $CONTRACTOR_EMAIL)->first();
if (!$user) {
    echo "Contractor not found — run seed_denis_presentation.php first.\n";
    return;
}
$contractor = Contractor::where('user_id', $user->id)->first();

DB::transaction(function () use ($user, $contractor) {

    // ---- Clean previous pricing + one-off invoices -----------------------
    $oneOffIds = Invoice::where('contractor_id', $user->id)
        ->whereNotNull('service_price_id')->pluck('id');
    PaymentSubmission::whereIn('invoice_id', $oneOffIds)->delete();
    Invoice::whereIn('id', $oneOffIds)->delete();
    ServicePrice::where('contractor_id', $user->id)->delete();

    // ---- Price list -------------------------------------------------------
    // [service_type, waste_type, category, volume_tier, price, description, includes]
    $prices = [
        // Regular pickup — residential, by volume
        ['regular_pickup', 'general', 'residential', 'small', 10000,
            'Weekly kerbside pickup for small households.',
            'Up to 5 bags per pickup; SMS reminder before collection day.'],
        ['regular_pickup', 'general', 'residential', 'medium', 20000,
            'Weekly pickup for larger households and compounds.',
            'Up to 15 bags per pickup; SMS reminder; missed-pickup redo within 24h.'],
        ['regular_pickup', 'general', 'commercial', 'large', 50000,
            'Scheduled pickup for shops, restaurants and offices.',
            'Up to 30 bags; flexible collection time; monthly invoice.'],

        // Bulk & specialised services
        ['bulk_collection', 'general', null, 'extra_large', 80000,
            'One-off clearance of accumulated waste (house moves, events, clean-up days).',
            'Crew of 3, loading included; same-week booking.'],
        ['bulk_collection', 'general', null, 'container', 150000,
            'Full truckload clearance with weighbridge receipt.',
            'Dedicated trip to Pugu Kinyamwezi; weighbridge slip shared with client.'],
        ['organic_waste', 'organic', 'residential', 'small', 8000,
            'Separate collection of food/garden waste for composting.',
            'Sorted-bag collection; compost drop-off discount for regulars.'],
        ['recycling', 'recyclable', null, 'medium', 5000,
            'Collection of sorted plastics, paper and metals.',
            'Free collection bags; weight credited against next invoice.'],
        ['construction_debris', 'industrial', null, 'container', 200000,
            'Rubble and debris removal from construction/renovation sites.',
            'Truck + loading crew; disposal fees at licensed site included.'],
    ];

    $created = 0;
    foreach ($prices as [$type, $waste, $category, $tier, $price, $desc, $includes]) {
        $p = ServicePrice::create([
            'contractor_id' => $user->id,
            'service_type' => $type,
            'waste_type' => $waste,
            'category' => $category,
            'volume_tier' => $tier,
            'price' => $price,
            'currency' => 'TZS',
            'description' => $desc,
            'includes' => $includes,
            'is_active' => true,
        ]);
        // Backdate: price card set up mid-May, reviewed since.
        $p->created_at = Carbon::create(2026, 5, 15, 10, $created * 3);
        $p->updated_at = Carbon::create(2026, 6, 20, 9, $created * 2);
        $p->save();
        $created++;
    }

    // One legacy price kept inactive — shows the toggle feature in the demo.
    $old = ServicePrice::create([
        'contractor_id' => $user->id,
        'service_type' => 'hazardous_waste',
        'waste_type' => 'medical',
        'category' => 'commercial',
        'volume_tier' => 'small',
        'price' => 120000,
        'currency' => 'TZS',
        'description' => 'Clinic sharps and medical waste (suspended — licence renewal pending).',
        'includes' => 'Sealed-container handling; incineration at licensed facility.',
        'is_active' => false,
    ]);
    $old->created_at = Carbon::create(2026, 5, 15, 11, 0);
    $old->updated_at = Carbon::create(2026, 7, 2, 14, 30);
    $old->save();
    $created++;

    // ---- Two one-off invoices booked against the price list ---------------
    $bulk   = ServicePrice::where('contractor_id', $user->id)
        ->where('service_type', 'bulk_collection')->where('volume_tier', 'extra_large')->first();
    $debris = ServicePrice::where('contractor_id', $user->id)
        ->where('service_type', 'construction_debris')->first();

    $juma = Client::where('contractor_id', $user->id)->where('name', 'Juma Hassan')->first();
    $said = Client::where('contractor_id', $user->id)->where('name', 'Said Bakari')->first();

    // Bulk clearance for Juma Hassan — booked late June, paid.
    if ($juma && $bulk) {
        $inv = Invoice::create([
            'invoice_number' => 'INV-202606-0201',
            'contractor_id' => $user->id,
            'client_id' => $juma->id,
            'contractor_registration_number' => $contractor->registration_number,
            'client_registration_number' => $juma->registration_number,
            'service_price_id' => $bulk->id,
            'invoice_date' => '2026-06-27',
            'due_date' => '2026-07-04',
            'status' => 'paid',
            'subtotal' => 80000,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'total_amount' => 80000,
            'amount_paid' => 80000,
            'remaining_balance' => 0,
            'paid_at' => Carbon::create(2026, 6, 28, 16, 40),
            'payment_method' => 'vodacom_mpesa',
            'service_type' => 'bulk_collection',
            'description' => 'Bulk Collection — Extra Large (30+ bags): compound clean-up, Masaki.',
        ]);
        $inv->created_at = Carbon::create(2026, 6, 27, 11, 10);
        $inv->updated_at = $inv->paid_at;
        $inv->save();

        $sub = PaymentSubmission::create([
            'invoice_id' => $inv->id,
            'client_id' => $juma->id,
            'contractor_id' => $contractor->id,
            'payer_name' => $juma->name,
            'amount_submitted' => 80000,
            'payment_method' => 'vodacom_mpesa',
            'status' => 'approved',
            'submitted_at' => Carbon::create(2026, 6, 28, 11, 20),
            'verified_at' => Carbon::create(2026, 6, 28, 16, 40),
            'receipt_number' => 'RCP202606281640' . str_pad((string) $inv->id, 4, '0', STR_PAD_LEFT),
            'receipt_issued_at' => Carbon::create(2026, 6, 28, 16, 40),
        ]);
        $sub->created_at = $sub->submitted_at;
        $sub->updated_at = $sub->verified_at;
        $sub->save();
    }

    // Construction debris quote for Said Bakari — follows his SMS enquiry
    // from 14 July; invoice sent, not yet paid.
    if ($said && $debris) {
        $inv = Invoice::create([
            'invoice_number' => 'INV-202607-0201',
            'contractor_id' => $user->id,
            'client_id' => $said->id,
            'contractor_registration_number' => $contractor->registration_number,
            'client_registration_number' => $said->registration_number,
            'service_price_id' => $debris->id,
            'invoice_date' => '2026-07-15',
            'due_date' => '2026-07-22',
            'status' => 'sent',
            'subtotal' => 200000,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'total_amount' => 200000,
            'amount_paid' => 0,
            'remaining_balance' => 200000,
            'service_type' => 'construction_debris',
            'description' => 'Construction Debris — Full Container: renovation rubble removal, Kimara.',
        ]);
        $inv->created_at = Carbon::create(2026, 7, 15, 8, 45);
        $inv->updated_at = $inv->created_at;
        $inv->save();
    }

    echo "Service prices: {$created} (1 inactive)\n";
    echo "One-off invoices: bulk clearance (paid) + construction debris (sent)\n";
});

echo "DONE\n";
