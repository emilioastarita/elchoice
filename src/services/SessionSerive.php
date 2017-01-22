<?php
namespace App\Services;


class SessionService
{
    protected $key;


    public function __construct($key = 'el_choice_trainer')
    {
        $this->key = $key;
    }

    public function get($key)
    {
        if (isset($_SESSION[$this->key($key)])) {
            return $_SESSION[$this->key($key)];
        }
        return null;
    }

    public function put($key, $value)
    {
        $_SESSION[$this->key($key)] = $value;
    }

    public function key($key)
    {
        return $this->key . '.' . $key;
    }

    public function getUserRole()
    {
        $user = $this->getUser();
        if (!is_array(($user)) || !$user['role']) {
            return 'guest';
        }
        return $user['role'];
    }

    public function getUser()
    {
        $userStr = $this->get('user');
        if (!$userStr) {
            return null;
        }
        $user = unserialize($userStr);
        return $user;
    }

    public function setUser($user)
    {
        $this->put('user', serialize($user));
        return $user;
    }

}