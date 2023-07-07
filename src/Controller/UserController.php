<?php

namespace App\Controller;

use App\Entity\User;
use App\Routing\Attribute\Route;
use Doctrine\ORM\EntityManager;

class UserController extends AbstractController
{
  #[Route("/user/create", name: "add_user")]
  public function create(EntityManager $em): string
  {
    $userNames = ['Lisa', 'Kevin', 'Emma', 'David', 'Louis', 'Etienne', 'Jerome', 'Louise', 'Didier'];

    foreach ($userNames as $name) {
        $user = new User();
        $user->setName($name);
        $em->persist($user);
    }
    $em->flush();    

    return $this->twig->render(
      'user/create_confirm.html.twig',
      ['user' => $user]
    );
  }
}
