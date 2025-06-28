<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEntityTraits(): void
    {
        $user = new User();

        // UUID trait via lifecycle callback
        $user->initializeUuid();

        $this->assertNotNull($user->getUuid());

        // EmailTrait, trying to simulate what I imagine to be it being hashed before storing in the db
        $email = 'test@example.com';
        $hashedEmail = hash('sha3-256', $email);
        $user->setEmail($hashedEmail);

        $this->assertSame(hash('sha3-256', $email), $hashedEmail);

        // PasswordTrait
        $password = 'supersecret';

        $user->setPassword(password_hash($password, \PASSWORD_BCRYPT));
        $this->assertTrue(password_verify($password, $user->getPassword()));

        // IsVerifiedTrait
        $user->setVerified(true);
        $this->assertTrue($user->isVerified());

        // RolesTrait
        $roles = ['ROLE_ADMIN', 'ROLE_USER'];
        $user->setRoles($roles);
        $this->assertContains('ROLE_ADMIN', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());

        // TimestampableTrait
        $now = new \DateTimeImmutable();
        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);

        $this->assertEquals($now, $user->getCreatedAt());
        $this->assertEquals($now, $user->getUpdatedAt());

        $this->assertSame($user->getUserIdentifier(), $user->getEmail());
    }
}
