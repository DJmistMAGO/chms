<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$request = Illuminate\Http\Request::create('/password/forgot-password', 'POST', [
    'email' => 'careehotel5@gmail.com',
]);

$request->setLaravelSession(app('session.store'));
$request->setSession(app('session.store'));

$controller = app(App\Http\Controllers\Auth\ForgotPasswordController::class);

try {
    $response = $controller->sendResetLinkEmail($request);
    echo get_class($response), PHP_EOL;
    echo 'status: ', $response->getSession()->get('status') ?? 'none', PHP_EOL;
} catch (Throwable $e) {
    echo get_class($e), PHP_EOL;
    echo $e->getMessage(), PHP_EOL;
    echo $e->getTraceAsString(), PHP_EOL;
}
