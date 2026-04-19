<?php

use App\Models\Organization;
use Illuminate\Support\Carbon;

// Mocking an organization
$org = new Organization();
$org->subscription_status = 'active';
$org->subscription_expires_at = Carbon::now()->addDays(3);

echo "--- Test 1: Active and 3 days left ---\n";
echo "Active: " . ($org->isSubscriptionActive() ? 'YES' : 'NO') . "\n";
echo "Suspended: " . ($org->isSuspended() ? 'YES' : 'NO') . "\n";
echo "Days remaining: " . $org->daysRemaining() . "\n";

echo "\n--- Test 2: Expired ---\n";
$org->subscription_expires_at = Carbon::now()->subDay();
echo "Active: " . ($org->isSubscriptionActive() ? 'YES' : 'NO') . "\n";
echo "Days remaining: " . $org->daysRemaining() . "\n";

echo "\n--- Test 3: Suspended ---\n";
$org->subscription_status = 'suspended';
$org->subscription_expires_at = Carbon::now()->addMonth();
echo "Active: " . ($org->isSubscriptionActive() ? 'YES' : 'NO') . "\n";
echo "Suspended: " . ($org->isSuspended() ? 'YES' : 'NO') . "\n";
