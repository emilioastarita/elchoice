<?php
namespace App\Middlewares;


use App\Services\SessionService;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Container;
use Slim\Views\PhpRenderer;


class Guard
{
    /*
     * @var SessionService;
     */
    protected $session;

    /*
     * @var Slim\Views\PhpRenderer
     */
    protected $renderer;

    /**
     * Guard constructor.
     * @param ContainerInterface $c
     */
    public function __construct(ContainerInterface $c)
    {
        $this->session = $c->get('userSession');
        $this->renderer = $c->get('renderer');
    }

    /*
     * Middleware callable function
     * @return callable
     */
    public function protect($roles = ['guest'])
    {
        $self = $this;

        return function (ServerRequestInterface $request, ResponseInterface $response, $next) use ($self, $roles) {
            $userRole = $self->session->getUserRole();
            if ($self->allowed($userRole, $roles) !== true) {
                // userRole: ' . $userRole . ' allowedRoles: ' . implode(',', $roles)
                $message = 'Require login!';
                return $response->withJson(['error' => $message, 'code' => 401], 401);
            }
            $self->renderer->addAttribute('user', $self->session->getUser());
            $response = $next($request, $response);
            return $response;
        };
    }

    protected function allowed($userRole, $roles)
    {
        $allowed = false;
        foreach($roles as $role) {
            if (in_array($userRole, $this->permsFor($role), true)) {
                $allowed = true;
            }
        }
        return $allowed;
    }

    protected function permsFor($role)
    {
        $perms = [
            'guest'   => ['guest', 'user', 'admin', 'manager'],
            'user'    => ['user', 'manager', 'admin'],
            'manager' => ['manager', 'admin'],
            'admin'   => ['admin'],
        ];
        return $perms[$role];
    }

}