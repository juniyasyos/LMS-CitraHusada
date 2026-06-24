<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$payload = [
    "sub" => 1,
    "nip" => "0000.00000",
    "name" => "admin dahlan",
];

$fields = config('iam.user_fields', []);
echo "Fields mapping from config:\n";
print_r($fields);

$attributes = [];
foreach ($fields as $column => $claim) {
    $value = data_get($payload, $claim);
    if ($value === null) {
        $value = data_get($payload, "token_info.{$claim}");
    }
    if ($value !== null) {
        $attributes[$column] = $value;
    }
}
echo "Attributes generated:\n";
print_r($attributes);

try {
    $user = \App\Models\User::query()->updateOrCreate(
        ['nip' => '0000.00000'],
        $attributes
    );
    echo "User created successfully!\n";
    print_r($user->toArray());
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
