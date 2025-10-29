<?php

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

test('user can create api token', function () {
    $user = User::factory()->create();
    
    $tokenName = 'Test Token';
    
    $response = $this
        ->actingAs($user)
        ->post('/profile/tokens', [
            'token_name' => $tokenName,
        ]);
        
    $response->assertSessionHas('status', 'token-created');
    $response->assertSessionHas('token');
    
    // Check that the token was created in the database
    $this->assertCount(1, $user->fresh()->tokens);
    $this->assertEquals($tokenName, $user->fresh()->tokens->first()->name);
});

test('user can delete api token', function () {
    $user = User::factory()->create();
    $token = $user->createToken('Test Token');
    
    $response = $this
        ->actingAs($user)
        ->delete("/profile/tokens/{$token->accessToken->id}");
        
    $response->assertSessionHas('status', 'token-deleted');
    
    // Check that the token was deleted from the database
    $this->assertCount(0, $user->fresh()->tokens);
});

test('token name is required', function () {
    $user = User::factory()->create();
    
    $response = $this
        ->actingAs($user)
        ->post('/profile/tokens', [
            'token_name' => '',
        ]);
        
    $response->assertSessionHasErrors('token_name');
});