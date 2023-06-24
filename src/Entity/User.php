<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\MappedSuperclass]
class User
{

  #[ORM\Column(type: 'string', length: 255)]
  private string $name;

  #[ORM\Column(type: 'string', length: 255)]
  private string $firstname;

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getFirstame(): string
  {
    return $this->firstname;
  }

  public function setFirstname(string $firstname): self
  {
    $this->firstname = $firstname;

    return $this;
  }
}
