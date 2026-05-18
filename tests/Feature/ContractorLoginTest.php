<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('pending contractor can log in and is redirected to pending page', function () {
    $contractor = User::factory()->create([
        'user_type' => 'contractor',
        'status' => 'pending',
        'email' => 'pending-contractor@example.com',
        'password' => 'SecurePass1!',
    ]);

    $response = $this->post(route('login.contractor.authenticate'), [
        'email' => 'pending-contractor@example.com',
        'password' => 'SecurePass1!',
    ]);

    $response->assertRedirect(route('contractor.pending'));
    $this->assertGuest();
});

test('approved contractor can log in to dashboard', function () {
    User::factory()->create([
        'user_type' => 'contractor',
        'status' => 'approved',
        'email' => 'approved-contractor@example.com',
        'password' => 'SecurePass1!',
    ]);

    $response = $this->post(route('login.contractor.authenticate'), [
        'email' => 'approved-contractor@example.com',
        'password' => 'SecurePass1!',
    ]);

    $response->assertRedirect(route('dashboard.contractor'));
    $this->assertAuthenticated();
});

test('contractor login rejects wrong password with a clear message', function () {
    User::factory()->create([
        'user_type' => 'contractor',
        'status' => 'approved',
        'email' => 'contractor-wrong-pass@example.com',
        'password' => Hash::make('CorrectPass1!'),
    ]);

    $response = $this->post(route('login.contractor.authenticate'), [
        'email' => 'contractor-wrong-pass@example.com',
        'password' => 'WrongPass1!',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('admin approval does not change contractor password', function () {
    $admin = User::factory()->create([
        'user_type' => 'admin',
        'status' => 'approved',
    ]);

    $contractor = User::factory()->create([
        'user_type' => 'contractor',
        'status' => 'pending',
        'email' => 'approve-keep-pass@example.com',
        'password' => 'KeepMyPass1!',
    ]);

    $hashBefore = $contractor->fresh()->password;

    $this->actingAs($admin)->post(route('admin.contractors.approve', $contractor->id));

    $contractor->refresh();

    expect($contractor->status)->toBe('approved')
        ->and($contractor->password)->toBe($hashBefore);

    $this->post(route('login.contractor.authenticate'), [
        'email' => 'approve-keep-pass@example.com',
        'password' => 'KeepMyPass1!',
    ])->assertRedirect(route('dashboard.contractor'));
});
