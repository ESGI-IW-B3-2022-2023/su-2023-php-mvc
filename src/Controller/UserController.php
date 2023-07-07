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

    try {
      foreach ($userNames as $name) {
          // Vérification de la validité du nom
          
          if(empty($name)) {
            // Message si le nom est vide
            echo 'Le nom ne peut pas être vide';
          }

          if(strlen($name) > 50) {
            // Message si le nom depasse 50 caractères
            echo 'Le nom est trop long';
          }

          // Si le nom passe les contrôles de validation, alors création de l'utilisateur
          $user = new User();
          $user->setName($name);
          $em->persist($user);
      }
      $em->flush();
    }
    catch (\Exception $e) {
      echo 'Une erreur est survenue : ' . $e->getMessage();
    }

    return $this->twig->render(
      'user/create_confirm.html.twig',
      ['user' => $user]
    );
  }
}
