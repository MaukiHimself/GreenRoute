<?php

/**
 * Presentation seed #2 — "lived-in" usage history for DENIS MAUKI.
 *
 * Requires seed_denis_presentation.php to have been run first (contractor +
 * 5 routes + 20 clients). This script then backfills ~3 months of activity
 * so the system looks like it has been in real use between the contractor
 * and his clients:
 *
 *  - Completed pickups (May–July 2026) with weights/categories for reports,
 *    plus one upcoming scheduled pickup per client.
 *  - Monthly invoices per client (May, June, July).
 *  - Payment submissions in every state — approved (with receipt number),
 *    pending approval, and rejected (with reason) — and invoice balances
 *    updated to match (paid / partially_paid / sent / overdue).
 *  - Contractor mobile-money Lipa numbers so the client payment page works.
 *  - SMS: two broadcasts to all 20 clients + real two-way conversations.
 *  - Client feedback, most of it already responded to.
 *
 * Idempotent — wipes and rebuilds only this contractor's activity data
 * (clients/routes are left untouched).
 *
 * Run:  php artisan tinker --execute="require base_path('database/seed_denis_usage_history.php');"
 */

use App\Models\User;
use App\Models\Client;
use App\Models\Contractor;
use App\Models\Schedule;
use App\Models\Invoice;
use App\Models\PaymentSubmission;
use App\Models\Message;
use App\Models\Feedback;
use App\Models\BillingRate;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$CONTRACTOR_EMAIL = 'denismauki@greenroute.co.tz';

$user = User::where('email', $CONTRACTOR_EMAIL)->first();
if (!$user) {
    echo "Contractor not found — run seed_denis_presentation.php first.\n";
    return;
}
$contractor = Contractor::where('user_id', $user->id)->first();
$clients = Client::where('contractor_id', $user->id)->orderBy('id')->get();
if ($clients->count() === 0) {
    echo "No clients found for contractor — run seed_denis_presentation.php first.\n";
    return;
}

DB::transaction(function () use ($user, $contractor, $clients) {

    // ---- Clean previous activity for this contractor -------------------
    $invoiceIds = Invoice::where('contractor_id', $user->id)->pluck('id');
    PaymentSubmission::whereIn('invoice_id', $invoiceIds)->delete();
    Invoice::where('contractor_id', $user->id)->delete();
    Schedule::where('contractor_id', $user->id)->delete();
    Message::where('contractor_id', $user->id)->delete();
    Feedback::where('contractor_id', $user->id)->delete();
    DB::table('contractor_billing_rate_changes')->where('contractor_id', $user->id)->delete();
    $clientUserIds = $clients->pluck('user_id')->filter()->all();
    DB::table('notifications')->whereIn('notifiable_id', array_merge($clientUserIds, [$user->id]))->delete();

    // ---- Contractor payment methods (Lipa numbers) ----------------------
    $contractor->update([
        'vodacom_mpesa_lipa_no'   => '5123456',
        'vodacom_mpesa_lipa_name' => 'DENIS MAUKI WASTE SERVICES',
        'airtel_money_lipa_no'    => '7891234',
        'airtel_money_lipa_name'  => 'DENIS MAUKI WASTE SERVICES',
        'halopesa_lipa_no'        => '9812345',
        'halopesa_lipa_name'      => 'DENIS MAUKI WASTE SERVICES',
    ]);

    // ---- Billing rates ---------------------------------------------------
    $rateUnplanned = BillingRate::find(1); // Residential (Unplanned)  10,000 monthly
    $ratePlanned   = BillingRate::find(2); // Residential (Planned)    20,000 monthly

    $wasteCategories = ['general', 'organic', 'recyclable', 'mixed'];
    $payMethods      = ['vodacom_mpesa', 'airtel_money', 'halopesa'];

    // Completed pickup dates (twice a month) + one upcoming pickup.
    $completedDates = [
        Carbon::create(2026, 5, 4),  Carbon::create(2026, 5, 18),
        Carbon::create(2026, 6, 1),  Carbon::create(2026, 6, 15),
        Carbon::create(2026, 7, 6),
    ];
    $upcomingDate = Carbon::create(2026, 7, 20);

    $scheduleByClientMonth = []; // [client_id][Y-m] => schedule id (for invoice link)
    $scheduleCount = 0;

    foreach ($clients as $i => $client) {
        $rate = ($i % 3 === 0) ? $ratePlanned : $rateUnplanned;

        $makeSchedule = function (Carbon $date, string $status) use ($user, $contractor, $client, $rate, $wasteCategories, $i, &$scheduleCount) {
            $time = sprintf('%02d:%02d:00', 8 + (($i + $date->day) % 8), (($i * 13) % 4) * 15);
            $s = Schedule::create([
                'contractor_id' => $user->id,
                'client_id' => $client->id,
                'contractor_registration_number' => $contractor->registration_number,
                'client_registration_number' => $client->registration_number,
                'route' => $client->route ?? 'Not Assigned',
                'billing_rate_id' => $rate?->id,
                'billing_rate_category' => $rate?->category,
                'billing_rate_location' => $rate?->location,
                'billing_rate_frequency' => $rate?->frequency,
                'base_collection_fee' => $rate?->collection_fee,
                'schedule_price' => $rate?->collection_fee,
                'pickup_date' => $date->toDateString(),
                'pickup_time' => $time,
                'scheduled_date' => $date->toDateString(),
                'scheduled_time' => $time,
                'pickup_location' => $client->ward ?? $client->address,
                'pickup_address' => $client->address ?? 'Not Provided',
                'city' => $client->city ?? 'Dar es Salaam',
                'state' => $client->state ?? 'Dar es Salaam',
                'zip_code' => $client->zip_code ?? '00000',
                'service_type' => 'collection',
                'frequency' => 'monthly',
                'status' => $status,
                'notes' => $status === 'completed' ? 'Collected on time.' : 'Regular residential pickup.',
                'weight_kg' => $status === 'completed' ? 18 + (($i * 7 + $date->day * 3) % 27) : null,
                'waste_category' => $status === 'completed' ? $wasteCategories[($i + $date->day) % 4] : null,
                'disposal_site' => $status === 'completed' ? 'Pugu Kinyamwezi Dumpsite' : null,
            ]);
            // Backdate so lists/reports look historical.
            $created = $date->copy()->subDays(3)->setTime(9, ($i * 11) % 60);
            $s->created_at = $created;
            $s->updated_at = $status === 'completed' ? $date->copy()->setTime(16, ($i * 7) % 60) : $created;
            $s->save();
            $scheduleCount++;
            return $s;
        };

        foreach ($completedDates as $d) {
            $s = $makeSchedule($d, 'completed');
            $scheduleByClientMonth[$client->id][$d->format('Y-m')] = $s->id; // last completed of the month wins
        }
        $makeSchedule($upcomingDate, 'scheduled');
    }

    // ---- Invoices + payments --------------------------------------------
    // Continue numbering well above anything already in the table.
    $invSeq = ['2026-05' => 100, '2026-06' => 100, '2026-07' => 100];

    $makeInvoice = function (Client $client, string $month, int $i) use ($user, $contractor, $rateUnplanned, $ratePlanned, &$invSeq, $scheduleByClientMonth) {
        $rate = ($i % 3 === 0) ? $ratePlanned : $rateUnplanned;
        $amount = (float) $rate->collection_fee;
        [$y, $m] = explode('-', $month);
        $invoiceDate = Carbon::create((int) $y, (int) $m, 1)->addDays($i % 3);
        $seq = ++$invSeq[$month];

        $inv = Invoice::create([
            'invoice_number' => sprintf('INV-%s%s-%04d', $y, $m, $seq),
            'contractor_id' => $user->id,
            'client_id' => $client->id,
            'contractor_registration_number' => $contractor->registration_number,
            'client_registration_number' => $client->registration_number,
            'schedule_id' => $scheduleByClientMonth[$client->id][$month] ?? null,
            'invoice_date' => $invoiceDate->toDateString(),
            'due_date' => $invoiceDate->copy()->endOfMonth()->toDateString(),
            'status' => 'sent',
            'subtotal' => $amount,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'total_amount' => $amount,
            'amount_paid' => 0,
            'remaining_balance' => $amount,
            'service_type' => 'collection',
            'description' => $rate->category . ' waste collection — ' . $invoiceDate->format('F Y'),
        ]);
        $inv->created_at = $invoiceDate->copy()->setTime(10, ($i * 9) % 60);
        $inv->updated_at = $inv->created_at;
        $inv->save();
        return $inv;
    };

    $paySeq = 0;
    $makePayment = function (Invoice $inv, Client $client, string $status, Carbon $when, ?float $amount = null, ?string $reason = null) use ($contractor, $payMethods, &$paySeq) {
        $paySeq++;
        $amount = $amount ?? (float) $inv->total_amount;
        $method = $payMethods[$paySeq % 3];

        $sub = PaymentSubmission::create([
            'invoice_id' => $inv->id,
            'client_id' => $client->id,
            'contractor_id' => $contractor->id,
            'payer_name' => $client->name,
            'amount_submitted' => $amount,
            'payment_method' => $method,
            'status' => $status === 'pending' ? 'pending_approval' : $status,
            'submitted_at' => $when,
            'verified_at' => $status === 'approved' ? $when->copy()->addHours(5) : null,
            'rejected_at' => $status === 'rejected' ? $when->copy()->addHours(7) : null,
            'rejection_reason' => $reason,
            'receipt_number' => $status === 'approved' ? sprintf('RCP%s%04d', $when->format('YmdHis'), $paySeq) : null,
            'receipt_issued_at' => $status === 'approved' ? $when->copy()->addHours(5) : null,
        ]);
        $sub->created_at = $when;
        $sub->updated_at = $status === 'approved' ? $when->copy()->addHours(5) : ($status === 'rejected' ? $when->copy()->addHours(7) : $when);
        $sub->save();

        if ($status === 'approved') {
            $newPaid = min((float) $inv->total_amount, (float) $inv->amount_paid + $amount);
            $inv->amount_paid = $newPaid;
            $inv->remaining_balance = max(0, (float) $inv->total_amount - $newPaid);
            if ($newPaid >= (float) $inv->total_amount) {
                $inv->status = 'paid';
                $inv->paid_at = $when->copy()->addHours(5);
            } else {
                $inv->status = 'partially_paid';
            }
            $inv->payment_method = $method;
            $inv->save();
        }
        return $sub;
    };

    $invoiceCount = 0;
    $payCounts = ['approved' => 0, 'pending' => 0, 'rejected' => 0];

    foreach ($clients as $i => $client) {
        // --- May: everyone paid on time ---------------------------------
        $inv = $makeInvoice($client, '2026-05', $i);
        $makePayment($inv, $client, 'approved', Carbon::create(2026, 5, 8 + ($i % 14), 11 + ($i % 6)));
        $payCounts['approved']++;
        $invoiceCount++;

        // --- June: mostly paid, a couple of stragglers -------------------
        $inv = $makeInvoice($client, '2026-06', $i);
        $invoiceCount++;
        if ($i === 7) {
            // Partially paid — approved half, balance outstanding.
            $makePayment($inv, $client, 'approved', Carbon::create(2026, 6, 20, 14, 5), (float) $inv->total_amount / 2);
            $payCounts['approved']++;
        } elseif ($i === 13) {
            // Never paid — now overdue.
            $inv->status = 'overdue';
            $inv->save();
        } else {
            $makePayment($inv, $client, 'approved', Carbon::create(2026, 6, 6 + ($i % 18), 9 + ($i % 8)));
            $payCounts['approved']++;
        }

        // --- July (current month): the full mix --------------------------
        $inv = $makeInvoice($client, '2026-07', $i);
        $invoiceCount++;
        if ($i < 8) {
            // Paid already.
            $makePayment($inv, $client, 'approved', Carbon::create(2026, 7, 3 + $i, 10 + ($i % 7)));
            $payCounts['approved']++;
        } elseif ($i < 12) {
            // Submitted, waiting for contractor approval.
            $makePayment($inv, $client, 'pending', Carbon::create(2026, 7, 12 + ($i % 3), 8 + $i % 10));
            $payCounts['pending']++;
        } elseif ($i < 15) {
            // Submitted but rejected — client must resubmit.
            $reasons = [
                'Transaction ID not found on M-Pesa statement. Please confirm and resubmit.',
                'Amount received does not match the invoice total.',
                'Payment was sent to the wrong Lipa number. Please use 5123456.',
            ];
            $makePayment($inv, $client, 'rejected', Carbon::create(2026, 7, 9 + ($i % 3), 13 + $i % 5), null, $reasons[$i % 3]);
            $payCounts['rejected']++;
        }
        // $i >= 15: invoice sent, no payment yet (still within due date).
    }

    // ---- SMS: broadcasts to all 20 clients + real conversations ----------
    $msgCount = 0;
    $say = function (Client $client, string $senderType, string $text, Carbon $when, string $type = 'custom', string $status = 'read') use ($user, &$msgCount) {
        $m = Message::create([
            'contractor_id' => $user->id,
            'client_id' => $client->id,
            'sender_type' => $senderType,
            'message' => $text,
            'message_type' => $type,
            'status' => $status,
            'read_at' => $status === 'read' ? $when->copy()->addMinutes(30) : null,
        ]);
        $m->created_at = $when;
        $m->updated_at = $when;
        $m->save();
        $msgCount++;
    };

    // Broadcast 1 — pickup schedule reminder (5 July).
    foreach ($clients as $i => $client) {
        $say($client, 'contractor',
            "Habari {$client->name}, hii ni GreenRoute. Gari la taka litapita eneo lako ({$client->ward}) Jumatatu tarehe 6 Julai kuanzia saa 2 asubuhi. Tafadhali weka taka nje mapema. Asante!",
            Carbon::create(2026, 7, 5, 17, 5 + ($i % 20)), 'pickup_schedule', $i % 4 === 0 ? 'delivered' : 'read');
    }

    // Broadcast 2 — payment reminder (10 July).
    foreach ($clients as $i => $client) {
        $say($client, 'contractor',
            "Ndugu {$client->name}, ankara yako ya Julai ipo tayari. Tafadhali lipa kupitia M-Pesa Lipa Namba 5123456 (DENIS MAUKI WASTE SERVICES) kabla ya mwisho wa mwezi. Asante kwa kutumia GreenRoute.",
            Carbon::create(2026, 7, 10, 9, 10 + ($i % 20)), 'payment_reminder', $i % 5 === 0 ? 'delivered' : 'read');
    }

    // Two-way conversations with a handful of clients.
    $c = $clients[0]; // Juma Hassan
    $say($c, 'client', 'Habari, gari halijafika leo Masaki. Bado mnakuja?', Carbon::create(2026, 7, 6, 10, 12));
    $say($c, 'contractor', 'Samahani Juma, gari lilichelewa kidogo kutokana na foleni Ali Hassan Mwinyi. Litafika kabla ya saa 8 mchana.', Carbon::create(2026, 7, 6, 10, 25));
    $say($c, 'client', 'Sawa, asante kwa mrejesho wa haraka.', Carbon::create(2026, 7, 6, 10, 31));

    $c = $clients[4]; // Amina Said
    $say($c, 'client', 'Naomba kubadilisha siku ya ukusanyaji kuwa Jumamosi badala ya Jumatatu.', Carbon::create(2026, 6, 25, 8, 40));
    $say($c, 'contractor', 'Habari Amina, tumepokea ombi lako. Ratiba mpya itaanza wiki ijayo — Jumamosi saa 3 asubuhi.', Carbon::create(2026, 6, 25, 9, 15));
    $say($c, 'client', 'Asante sana!', Carbon::create(2026, 6, 25, 9, 20));

    $c = $clients[8]; // Rehema Omary
    $say($c, 'client', 'Nimeshalipa ankara ya Julai kupitia Airtel Money, naomba mthibitishe.', Carbon::create(2026, 7, 13, 14, 2));
    $say($c, 'contractor', 'Tumepokea malipo yako Rehema, tunayahakiki. Utapokea risiti ndani ya saa 24.', Carbon::create(2026, 7, 13, 14, 30));

    $c = $clients[13]; // Joseph Massawe (rejected payment)
    $say($c, 'contractor', 'Ndugu Joseph, malipo yako hayakupatikana kwenye taarifa ya M-Pesa. Tafadhali hakiki muamala wako na utume tena.', Carbon::create(2026, 7, 9, 16, 45));
    $say($c, 'client', 'Pole, nadhani niliweka namba ya muamala vibaya. Nitatuma tena kesho.', Carbon::create(2026, 7, 9, 17, 10), 'custom', 'delivered');

    $c = $clients[17]; // Said Bakari — unread incoming (shows in inbox as new)
    $say($c, 'client', 'Habari, je mnakusanya taka za ujenzi (debris)? Nina mifuko kadhaa Kimara.', Carbon::create(2026, 7, 14, 18, 22), 'custom', 'sent');

    // ---- Feedback ---------------------------------------------------------
    $fbCount = 0;
    $fb = function (Client $client, string $subject, string $message, Carbon $when, ?string $response = null, ?Carbon $respondedAt = null) use ($user, &$fbCount) {
        $f = Feedback::create([
            'client_id' => $client->id,
            'contractor_id' => $user->id,
            'subject' => $subject,
            'message' => $message,
            'response' => $response,
            'status' => $response ? 'responded' : 'open',
            'responded_at' => $respondedAt,
        ]);
        $f->created_at = $when;
        $f->updated_at = $respondedAt ?? $when;
        $f->save();
        $fbCount++;
    };

    $fb($clients[1], 'Great service', 'Collection has been consistent every week. Keep it up!',
        Carbon::create(2026, 6, 16, 12, 0),
        'Thank you Neema! We appreciate your feedback and continued trust.', Carbon::create(2026, 6, 16, 15, 30));
    $fb($clients[5], 'Missed pickup on 1 June', 'The truck skipped our street in Kijitonyama on Monday.',
        Carbon::create(2026, 6, 2, 9, 15),
        'Apologies Frank — the truck had a breakdown. We collected on Tuesday and added a free extra pickup that month.', Carbon::create(2026, 6, 2, 11, 0));
    $fb($clients[10], 'Request for recycling bins', 'Can we get separate bins for plastic and organic waste?',
        Carbon::create(2026, 7, 1, 10, 45),
        'Great idea Fatuma. We are piloting sorted collection in Ilala this month — your street is on the list.', Carbon::create(2026, 7, 2, 8, 20));
    $fb($clients[15], 'Collection time too early', 'The 7am pickup is hard for us; could it move to 9am?',
        Carbon::create(2026, 7, 11, 19, 5));
    $fb($clients[19], 'Billing question', 'Why did my invoice increase this month compared to May?',
        Carbon::create(2026, 7, 13, 8, 50));

    echo "Schedules: {$scheduleCount}\n";
    echo "Invoices:  {$invoiceCount}\n";
    echo "Payments:  approved={$payCounts['approved']} pending={$payCounts['pending']} rejected={$payCounts['rejected']}\n";
    echo "Messages:  {$msgCount}\n";
    echo "Feedback:  {$fbCount}\n";
});

echo "DONE\n";
