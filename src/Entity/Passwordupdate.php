<?php

namespace App\Entity;

use App\Repository\PasswordupdateRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class Passwordupdate
{   
    private $oldPassword;
    /**
     * @Assert\Length(
     * min=8,
     * minMessage="votre mot de pass doit avoir au mois 8 caracthères"
     * )
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword",
     * message="vous n'avez pas bien confirmé votre mot de pass" )
     */
    private $confirmPassword;
  
    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
