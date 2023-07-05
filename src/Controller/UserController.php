<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserController extends AbstractController
{
  public function create(EntityManager $em): string
  {
    $user = new User();
    $user->setName('Ora Morton');
    $em->persist($user);
    $em->flush();

    return $this->twig->render(
      'user/create_confirm.html.twig',
      ['user' => $user]
    );
  }
}
