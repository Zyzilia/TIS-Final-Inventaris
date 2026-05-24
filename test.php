<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$response = Illuminate\Support\Facades\Http::withoutVerifying()->withHeaders(['Authorization' => 'Bearer biteship_test.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSW52ZW50YXJpcyIsInVzZXJJZCI6IjZhMTJhNWEyZDIwNWFhZTJiYWNmYmRiNyIsImlhdCI6MTc3OTYwNzI1N30.W7j50ZcrtMHJ5Ui0AJlSgoF0Kfi76lnD7XdCJ_HshVg'])->get('https://api.biteship.com/v1/maps/areas', ['countries' => 'ID', 'input' => 'Lowokwaru', 'type' => 'single']);
dump($response->json());
