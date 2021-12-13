<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="clients")
 */
class Client extends User
{

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $client_num;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_premium;

    public function getClientNum(): ?int
    {
        return $this->client_num;
    }

    public function setClientNum(int $client_num): self
    {
        $this->client_num = $client_num;

        return $this;
    }

    public function getIsPremium(): ?bool
    {
        return $this->is_premium;
    }

    public function setIsPremium(bool $is_premium): self
    {
        $this->is_premium = $is_premium;

        return $this;
    }
}
