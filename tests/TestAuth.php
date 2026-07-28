<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\User;
use App\Auth\UserRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;

class TestAuth extends TestCase
{
    private $authService;
    private $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->authService = new AuthService($this->userRepository);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, $password));

        $this->authService->login($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'wrongpassword';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authService->login($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }

    public function testRegisterSuccess()
    {
        $username = 'newuser';
        $password = 'newpassword';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->userRepository->expects($this->once())
            ->method('saveUser')
            ->with(new User($username, $password));

        $this->authService->register($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testRegisterFailure()
    {
        $username = 'newuser';
        $password = 'newpassword';

        $this->userRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(new User($username, 'existingpassword'));

        $this->authService->register($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the `login` method succeeds when the provided credentials match a user in the database.
- `testLoginFailure`: Tests that the `login` method fails when the provided credentials do not match a user in the database.
- `testRegisterSuccess`: Tests that the `register` method succeeds when a new user is created in the database.
- `testRegisterFailure`: Tests that the `register` method fails when a user with the same username already exists in the database.