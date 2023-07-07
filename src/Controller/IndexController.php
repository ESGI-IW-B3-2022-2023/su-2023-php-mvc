<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;

class IndexController extends AbstractController
{
  #[Route("/", name: "homepage")]
  public function home(): string
  {
<<<<<<< Updated upstream
    var_dump($this->em->getRepository(User::class)->findAll());
=======
    // $user = new User();
    // $user->setName('Jean');
    // $this->em->persist($user);
    // $this->em->flush();
    // var_dump($this->em->getRepository(User::class)->findAll());
>>>>>>> Stashed changes
    return $this->twig->render('index.html.twig');
  }
}
