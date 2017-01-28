<?php
namespace App\Services;

use App\Entities\User;
use InvalidArgumentException;
use Slim\Container;
use Slim\Exception\NotFoundException;

class UserService
{
    const MIN_PASSWORD_LEN = 5;
    const VALID_ROLES = ['user', 'manager', 'admin'];

    /*
     * @var EntityManager
     */
    protected $em;

    /*
     * @var UserRepository
     */
    protected $userRepo;

    public function __construct(Container $c)
    {
        $this->em = $c->get('em');
        $this->userRepo = $c->get('userRepository');
    }

    /**
     * @param $data
     * @return User
     * @throws \Exception
     */
    public function create($data)
    {
        if (!empty($data['id'])) {
            throw new \InvalidArgumentException('Invalid id param.');
        }
        $this->validData($data);

        $user = $this->userRepo->findOneBy(['username' => $data['username']]);
        if ($user) {
            throw new \Exception('Sorry your username is already in use.');
        }


        $user = new User();
        $user
            ->setUsername($data['username'])
            ->setPassword($this->encodePassword($data['password']))
            ->setRole($data['role']);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function update($data)
    {
        if (empty($data['id'])) {
            throw new InvalidArgumentException('Missing id param.');
        }
        $user = $this->find($data['id']);
        if (empty($user)) {
            throw new NotFoundException();
        }

        if (in_array($user->getUsername(), ['admin', 'manager'], true)) {
            throw new \Exception('For demo purposes your are not allowed to modify this user.');
        }

        if (isset($data['username'])) {
            $this->validateUsername($data['username']);
            $alreadyTaken = $this->userRepo->findOneBy(['username' => $data['username']]);
            if ($alreadyTaken && $alreadyTaken->getId() !== $user->getId()) {
                throw new \Exception('Sorry your username is already in use.');
            }
            $user->setUsername($data['username']);
        }

        if (isset($data['role'])) {
            if ($this->isValidRole($data['role']) !== true) {
                throw new InvalidArgumentException('Invalid role param');
            }
            $user->setRole($data['role']);
        }
        if (isset($data['password'])) {
            if ($this->isValidPassword($data['password']) !== true) {
                throw new InvalidArgumentException('Password must have at least ' . UserService::MIN_PASSWORD_LEN . ' characters.');
            }
            $user->setPassword($this->encodePassword($data['password']));
        }
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function delete($id)
    {
        $user = $this->find($id);
        if (empty($user)) {
            throw new NotFoundException('No user.');
        }
        if (in_array($user->getUsername(), ['admin', 'manager'], true)) {
            throw new \Exception('For demo purposes your are not allowed to delete this user.');
        }

        $this->em->remove($user);
        $this->em->flush($user);
    }

    public function find($id)
    {
        $user = $this->userRepo->find($id);
        return $user;
    }

    public function findWithCredentials($username, $password)
    {
        $user = $this->userRepo->findOneBy(['username' => $username]);
        if (!$user) {
            return null;
        }
        if (password_verify($password, $user->getPassword()) !== true) {
            return null;
        }
        return $user->toArray();
    }

    public function findAll($array = true)
    {
        $all = $this->userRepo->findAll();
        if ($array) {
            return array_map(function($user){
                return $user->toArray();
            }, $all);
        }
        return $all;
    }

    protected function validData(&$data)
    {
        if (empty($data['username'])) {
            throw new InvalidArgumentException('Missing username.');
        }
        $data['username'] = $data['username'];
        $this->validateUsername($data['username']);
        if (empty($data['password'])) {
            throw new InvalidArgumentException('Missing password.');
        }
        if ($this->isValidPassword($data['password']) !== true) {
            throw new InvalidArgumentException('Password must have at least ' . UserService::MIN_PASSWORD_LEN . ' characters.');
        }
        if (empty($data['role'])) {
            throw new InvalidArgumentException('Missing role param.');
        }
        if ($this->isValidRole($data['role']) !== true) {
            throw new InvalidArgumentException('Invalid role param.');
        }
    }

    protected function validateUsername($username)
    {
        if (!preg_match('/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/', $username)) {
            $message = 'Invalid username: valid usernames 
                        starts with an alphabetic character and use only 
                        alphanumeric characters after the first character.';
            throw new InvalidArgumentException($message);
        }
    }

    protected function encodePassword($plain)
    {
        return password_hash($plain, PASSWORD_DEFAULT);
    }

    protected function isValidPassword($password)
    {
        return strlen($password) >= UserService::MIN_PASSWORD_LEN;
    }

    protected function isValidRole($role)
    {
        return in_array($role, UserService::VALID_ROLES, true);
    }

    public function deleteAll()
    {
        if (PHP_SAPI !== 'cli') {
            throw new Exception('Command isn\'t allowed to run without CLI');
        }

    }


}