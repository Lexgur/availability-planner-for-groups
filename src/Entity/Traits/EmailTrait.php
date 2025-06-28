<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait EmailTrait
{
    #[ORM\Column(
        name: 'email',
        type: 'string',
        length: 255,
        unique: true,
        nullable: false
    )]
    #[Assert\Email]
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
