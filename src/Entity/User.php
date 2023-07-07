<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


#[ORM\MappedSuperclass]
abstract class User
{
  #[ORM\Id]
  #[ORM\Column(type: 'integer')]
  #[ORM\GeneratedValue]
  private $id;

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

  public function getFirstname(): string
  {
    return $this->firstname;
  }

  public function setFirstname(string $firstname): self
  {
    $this->firstname = $firstname;

    return $this;
  }
}
