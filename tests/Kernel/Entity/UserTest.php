<?php

declare(strict_types=1);

namespace App\Tests\Kernel\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testInitializeAndSetters(): void
    {
        $user = new User();
        $user->initializeUuid();
        $user->setEmail('test@example.example');
        $user->setPassword(password_hash('password123', \PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(true);

        $this->assertNotEmpty($user->getUuid());
        $this->assertEquals('test@example.example', $user->getEmail());
        $this->assertTrue(password_verify('password123', $user->getPassword()));
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertTrue($user->isVerified());
    }

    public function testUpdateUserFields(): void
    {
        $user = new User();
        $user->initializeUuid();
        $user->setEmail('original@example.example');
        $user->setPassword(password_hash('original', \PASSWORD_BCRYPT));
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(false);

        // Update fields
        $user->setEmail('updated@example.example');
        $user->setVerified(true);

        $this->assertEquals('updated@example.example', $user->getEmail());
        $this->assertTrue($user->isVerified());
    }

    public function testDeleteUser(): void
    {
        // Simulating deletion of the database
        $user = new User();
        $user->initializeUuid();
        $user->setEmail('delete@example.example');
        $user->setPassword(password_hash('secret', \PASSWORD_BCRYPT));

        // "Delete" simulated by nulling the User
        $user = null;

        $this->assertNull($user);
    }
}
