<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait IsVerifiedTrait
{
    #[ORM\Column(
        name: 'is_verified',
        type: 'boolean',
        nullable: false
    )]
    private bool $isVerified = false;

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
