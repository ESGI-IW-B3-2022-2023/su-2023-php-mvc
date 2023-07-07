<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client extends User
{

  #[ORM\Column(type: 'string', length: 255)]
  private string $accountNumber;

  public function getAccountNumber(): string
  {
    return $this->accountNumber;
  }

  public function setAccountNumber(string $accountNumber): self
  {
    $this->accountNumber = $accountNumber;

    return $this;
  }
}
