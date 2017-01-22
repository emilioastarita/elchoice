<?php
// Routes
use Slim\Http\Request;
use Slim\Http\Response;

$guard = $app->getContainer()->get('guard');

$guest = $guard->protect(['guest']);
$user = $guard->protect(['user']);
$adminUsers = $guard->protect(['admin', 'manager']);

$angularRoutes  = [
    '/', '/users', '/users/new','/users/{id}',
    '/travel-plan',
    '/travel-plan/{month}',
    '/requirements',
    '/exams-edit/{examId}/questions/new', '/exams-edit/{examId}/questions/{id}',
    '/exams', '/exams-edit/new', '/exams-resolve/{id}', '/exams-edit/{id}',
    '/members', '/members/register', '/members/login'
];
foreach ($angularRoutes as $route) {
    $app->get($route, function (Request $req, Response $res) {
        $this->logger->info("Index '/' route");
        return $this->renderer->render($res, 'index.phtml');
    })->add($guest);
}


$app->group('/api/users', function () use ($app) {
    $controller = '\App\Controllers\UserController:';
    $this->get('/',        $controller . 'collection');
    $this->get('/{id}',    $controller . 'view');
    $this->post('/',       $controller . 'create');
    $this->put('/{id}',    $controller . 'modify');
    $this->delete('/{id}', $controller . 'delete');
})->add($adminUsers);

$app->group('/api/exams', function () use ($app) {
    $controller = '\App\Controllers\ExamController:';
    $this->get('/',        $controller . 'collection');
    $this->get('/{id}',    $controller . 'view');
    $this->post('/',       $controller . 'create');
    $this->put('/{id}',    $controller . 'modify');
    $this->delete('/{id}', $controller . 'delete');
})->add($user);

$app->group('/api/questions', function () use ($app) {
    $controller = '\App\Controllers\QuestionController:';
    $this->get('/',        $controller . 'collection');
    $this->get('/{id}',    $controller . 'view');
    $this->post('/',       $controller . 'create');
    $this->put('/{id}',    $controller . 'modify');
    $this->delete('/{id}', $controller . 'delete');
})->add($user);

$app->group('/api/members', function () use ($app) {
    $controller = '\App\Controllers\MemberController:';
    $this->get('/login-status',     $controller . 'loginStatus');
    $this->post('/login',     $controller . 'login');
    $this->get('/logout',    $controller . 'logout');
    $this->post('/register', $controller . 'register');
})->add($guest);
