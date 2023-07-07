<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;

class IndexController extends AbstractController
{
  #[Route("/", name: "homepage")]
  public function home(): string
  {
    var_dump($this->em->getRepository(User::class)->findAll());
    return $this->twig->render('index.html.twig');
  }
}
