<?php

declare(strict_types=1);

namespace App\Entity\Traits;

trait EmailTrait
{
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        if (method_exists($this, 'setEmailHashFromEmail')) {
            $this->setEmailHashFromEmail($email);
        }

        return $this;
    }
}
