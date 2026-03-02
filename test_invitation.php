<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$invitations = \App\Models\Invitation::all();

echo "=== INVITATIONS DEBUG ===\n";
echo "Total invitations: " . $invitations->count() . "\n\n";

foreach ($invitations as $inv) {
    echo "Token: " . $inv->token . "\n";
    echo "Email: " . $inv->email . "\n";
    echo "Colocation ID: " . $inv->colocation_id . "\n";
    echo "Expires: " . $inv->expires_at . "\n";
    echo "Accepted: " . ($inv->accepted_at ? 'YES' : 'NO') . "\n";
    echo "Declined: " . ($inv->declined_at ? 'YES' : 'NO') . "\n";
    echo "Expired: " . ($inv->isExpired() ? 'YES' : 'NO') . "\n";
    echo "------------------------\n";
}
