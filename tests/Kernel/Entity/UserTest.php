<?php

declare(strict_types=1);

namespace App\Tests\Kernel\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEntityTraits(): void
    {
        $user = new User();

        // UUID trait via lifecycle callback
        $user->initializeUuid();
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $user->getUuid()
        );

        // EmailTrait & HashedEmailTrait
        $email = 'test@example.com';
        $user->setEmail($email);
        $user->setEmailHashFromEmail($email);
        $this->assertSame($email, $user->getEmail());
        $this->assertSame(hash('sha3-256', $email), $user->getEmailHash());

        $password = 'supersecret';

        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));
        $this->assertTrue(password_verify($password, $user->getPassword()));

        // IsVerifiedTrait
        $user->setIsVerified(true);
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

        $this->assertSame($user->getUserIdentifier(), $user->getEmailHash());
    }
}
