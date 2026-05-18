<?php

use App\Mail\ContractorApproved;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

test('admin approval sends contractor approval email', function () {
    Mail::fake();

    $admin = User::factory()->create([
        'user_type' => 'admin',
        'status' => 'approved',
        'email' => 'admin@example.com',
        'password' => Hash::make('secret123'),
    ]);

    $contractor = User::factory()->create([
        'user_type' => 'contractor',
        'status' => 'pending',
        'email' => 'contractor@example.com',
        'password' => Hash::make('temp123'),
    ]);

    $response = $this
        ->actingAs($admin)
        ->post(route('admin.contractors.approve', $contractor->id));

    $response->assertRedirect(route('admin.verification'));

    Mail::assertSent(ContractorApproved::class, function (ContractorApproved $mail) use ($contractor) {
        return $mail->hasTo($contractor->email);
    });
});

test('contractor is not approved before admin approval endpoint is called', function () {
    $contractor = User::factory()->create([
        'user_type' => 'contractor',
        'status' => 'pending',
        'email' => 'contractor2@example.com',
        'password' => Hash::make('temp123'),
    ]);

    $contractor->refresh();

    expect($contractor->status)->toBe('pending');
});
