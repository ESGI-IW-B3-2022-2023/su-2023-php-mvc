<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Twig\Environment;

abstract class AbstractController
{
  public function __construct(
    protected Environment $twig,
    protected EntityManager $em
  ) {
  }
}
