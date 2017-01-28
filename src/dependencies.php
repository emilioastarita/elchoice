<?php
// DIC configuration
use App\Middlewares\Guard;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Interop\Container\ContainerInterface;

$container = $app->getContainer();

$container['renderer'] = function (ContainerInterface $c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['logger'] = function (ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::ERROR));

    return $logger;
};

$container['em'] = function (ContainerInterface $c) {
    $settings = $c->get('settings')['doctrine'];
    $paths = [$settings['entities']];
    $config = Setup::createAnnotationMetadataConfiguration($paths, $settings['dev']);
    $entityManager = EntityManager::create($settings['params'], $config);
    return $entityManager;
};

$container['errorHandler'] = function ($c) {
    $settings = $c->get('settings')['doctrine'];
    return function ($request, $response, $exception) use ($c, $settings) {

        $error = [
            'error' => $exception->getMessage()
        ];

        if ($settings['dev']) {
            $error['stack'] = $exception->getTrace();
        }

        return $c['response']->withStatus(500)
            ->withJson($error);
    };
};

$repositories = [
    'answer',
    'exam',
    'question',
    'user',
];

foreach ($repositories as $repo) {
    $entity = 'App\Entities\\' . ucfirst($repo);
    $repository = $repo . 'Repository';
    $container[$repository] = function (ContainerInterface $c) use ($entity) {
        return $c->get('em')->getRepository($entity);
    };
}

$container['userService'] = function (ContainerInterface $c) {
    return new \App\Services\UserService($c);
};

$container['questionService'] = function (ContainerInterface $c) {
    return new \App\Services\QuestionService($c);
};

$container['examService'] = function (ContainerInterface $c) {
    return new \App\Services\ExamService($c);
};

$container['userSession'] = function (ContainerInterface $c) {
    return new \App\Services\SessionService();
};

$container['guard'] = function (ContainerInterface $c) {
    return new Guard($c);
};