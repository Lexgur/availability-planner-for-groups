<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\EmailTrait;
use App\Entity\Traits\HashedEmailTrait;
use App\Entity\Traits\IsVerifiedTrait;
use App\Entity\Traits\PasswordTrait;
use App\Entity\Traits\RolesTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements PasswordAuthenticatedUserInterface
{
    use PasswordTrait;
    use EmailTrait;
    use IsVerifiedTrait;
    use HashedEmailTrait;
    use RolesTrait;
    use TimestampableTrait;
    use UuidTrait;

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->emailHash;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
