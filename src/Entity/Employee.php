<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
class Employee extends User
{

  #[ORM\Column(type: 'string', length: 255)]
  private string $matricule;

  public function getMatricule(): string
  {
    return $this->matricule;
  }

  public function setMatricule(string $matricule): self
  {
    $this->matricule = $matricule;

    return $this;
  }
}
