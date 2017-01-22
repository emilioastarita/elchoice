<?php
namespace App\Tests;

use App\Services\UserService;
use Exception;
use InvalidArgumentException;


class UserServiceTest extends Base
{

    public function testUserCreateNothing()
    {
        $this->expectException(InvalidArgumentException::class);
        $service = new UserService($this->em);
        $data = [];
        $service->create($data);
    }

    public function testUserCreateNoPass()
    {
        $this->expectException(InvalidArgumentException::class);
        $service = new UserService($this->em);
        $data = ['username' => 'emilio'];
        $service->create($data);
    }

    public function testUserCreateNoRole()
    {
        $this->expectException(InvalidArgumentException::class);
        $service = new UserService($this->em);
        $data = ['username' => 'emilio', 'password' => 'mi-password-ok'];
        $service->create($data);
    }

    public function testUserCreateWithId()
    {
        $this->expectException(InvalidArgumentException::class);
        $service = new UserService($this->em);
        $data = ['id' => 1, 'username' => 'emilio', 'password' => 'mi-password-ok', 'role' => 'user'];
        $user = $service->create($data);
        $this->assertEquals('user', $user->getRole());
        $this->assertEquals('emilio', $user->getUsername());
    }

    public function testUserCreateInvalidRole()
    {
        $this->expectException(InvalidArgumentException::class);
        $service = new UserService($this->em);
        $data = ['username' => 'emilio', 'password' => 'mi-password-ok', 'role' => 'pepino'];
        $user = $service->create($data);
        $this->assertEquals('user', $user->getRole());
        $this->assertEquals('emilio', $user->getUsername());
    }

    public function testUserCreateInvalidPassword()
    {
        $this->expectException(InvalidArgumentException::class);
        $service = new UserService($this->em);
        $data = ['username' => 'emilio', 'password' => '1243', 'role' => 'user'];
        $user = $service->create($data);
        $this->assertEquals('user', $user->getRole());
        $this->assertEquals('emilio', $user->getUsername());
    }


    public function testUserCreateOk()
    {
        $service = new UserService($this->em);
        $data = ['username' => 'emilio', 'password' => 'mi-password-ok', 'role' => 'user'];
        $user = $service->create($data);
        $this->assertEquals('user', $user->getRole());
        $this->assertEquals('emilio', $user->getUsername());
    }

    public function testUserCreateOk2()
    {
        $service = new UserService($this->em);
        $data = ['username' => 'emilio', 'password' => 'mi-password-ok', 'role' => 'admin'];
        $user = $service->create($data);
        $this->assertEquals('admin', $user->getRole());
        $this->assertEquals('emilio', $user->getUsername());
    }

    public function testUserCreateOk3()
    {
        $service = new UserService($this->em);
        $data = ['username' => 'emilio', 'password' => 'mi-password-ok', 'role' => 'manager'];
        $user = $service->create($data);
        $this->assertEquals('manager', $user->getRole());
        $this->assertEquals('emilio', $user->getUsername());
    }


    public function testUserCreateUnique()
    {
        $this->expectException(Exception::class);
        $service = new UserService($this->em);
        $data = ['username' => 'emilio', 'password' => 'mi-password-ok', 'role' => 'user'];
        $user1 = $service->create($data);
        $user2 = $service->create($data);
    }

    public function testUserCreateOkUpdate()
    {
        $service = new UserService($this->em);
        $data = ['username' => 'emilio', 'password' => 'mi-password-ok', 'role' => 'user'];
        $user = $service->create($data);
        $createdPassword = $user->getPassword();
        $dataUpdate = [
            'id' => $user->getId(),
            'username' => 'joaquin',
            'role' => 'admin',
            'password' => 'new-password-test'
        ];
        $service->update($dataUpdate);
        $this->assertEquals($user->getUsername(), 'joaquin');
        $this->assertEquals($user->getRole(), 'admin');
        $this->assertNotEquals($user->getPassword(), $createdPassword);
    }

    public function deleteUser()
    {
        $service = new UserService($this->em);
        $data = ['username' => 'emilio', 'password' => 'mi-password-ok', 'role' => 'user'];
        $user = $service->create($data);
        $this->assertNotEmpty($service->find($user->getId()));
        $service->delete($user->getId());
        $this->assertEmpty($service->find($user->getId()));
    }

    
}
