<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$response = Illuminate\Support\Facades\Http::withoutVerifying()->withHeaders(['Authorization' => 'Bearer biteship_test.eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiSW52ZW50YXJpcyIsInVzZXJJZCI6IjZhMTJhNWEyZDIwNWFhZTJiYWNmYmRiNyIsImlhdCI6MTc3OTYwNzI1N30.W7j50ZcrtMHJ5Ui0AJlSgoF0Kfi76lnD7XdCJ_HshVg', 'Content-Type' => 'application/json'])->post('https://api.biteship.com/v1/rates/couriers', ['origin_area_id' => 'IDNP6IDNC148IDND838IDZ12110', 'destination_area_id' => 'IDNP6IDNC148IDND838IDZ12110', 'couriers' => 'jnt', 'items' => [['name' => 'Barang', 'value' => 50000, 'weight' => 25000, 'quantity' => 1]]]);
dump($response->json());
