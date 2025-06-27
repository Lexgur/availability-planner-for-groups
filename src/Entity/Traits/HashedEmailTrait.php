<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait HashedEmailTrait
{
    #[ORM\Column(length: 64, unique: true)]
    private ?string $emailHash = null;

    public function getEmailHash(): ?string
    {
        return $this->emailHash;
    }

    public function setEmailHashFromEmail(string $email): void
    {
        $this->emailHash = hash('sha3-256', strtolower(trim($email)));
    }
}
