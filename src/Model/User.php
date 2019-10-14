<?php

namespace App\Model;

class User
{
    private $email;
    private $password;

    /**
     * @return mixed
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

}