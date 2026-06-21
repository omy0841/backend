c<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'admin@example.com')->first();
if ($user) {
    echo "admin exists\n";
    echo "is_admin=" . ($user->is_admin ? '1' : '0') . "\n";
} else {
    echo "admin missing\n";
}
