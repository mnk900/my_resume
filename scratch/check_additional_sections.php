<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$user = User::where('name', 'like', '%Fazal%')->first();
if (!$user) {
    echo "User not found\n";
    exit(1);
}

$p = $user->portfolio;
echo "User: " . $user->name . "\n";
echo "Services count: " . ($p->services ? $p->services->count() : 0) . "\n";
echo "Achievements count: " . ($p->achievements ? $p->achievements->count() : 0) . "\n";
echo "Contributions count: " . ($p->contributions ? $p->contributions->count() : 0) . "\n";
echo "Testimonials count: " . ($p->testimonials ? $p->testimonials->count() : 0) . "\n";

if ($p->services && $p->services->count() > 0) {
    foreach ($p->services as $s) echo "- Service: " . $s->title . "\n";
}
if ($p->achievements && $p->achievements->count() > 0) {
    foreach ($p->achievements as $a) echo "- Achievement: " . $a->title . "\n";
}
if ($p->contributions && $p->contributions->count() > 0) {
    foreach ($p->contributions as $c) echo "- Contribution: " . $c->title . "\n";
}
if ($p->testimonials && $p->testimonials->count() > 0) {
    foreach ($p->testimonials as $t) echo "- Testimonial by: " . $t->client_name . "\n";
}
