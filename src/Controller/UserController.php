<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Routing\Attribute\Route;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Doctrine\Collections\CollectionAdapter;
use Pagerfanta\Pagerfanta;

class UserController extends AbstractController
{
  #[Route('/user/create', name: 'user_create')]
  public function create(): string
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
          $client = new Client();
          $client->setName($name);
          $client->setFirstname($name);
          $client->setAccountNumber('0006344');
          $this->em->persist($client);
      }
      $this->em->flush();
    }
    catch (\Exception $e) {
      echo 'Une erreur est survenue : ' . $e->getMessage();
    }

    return $this->twig->render(
      'user/create_confirm.html.twig',
      ['client' => $client]
    );
  }

  #[Route('/user/list', name: 'user_index')]
  public function index(): string
  {
    $clients = $this->em->getRepository(Client::class)->findAllPaginated();

    var_dump($clients);

    $adapter = new ArrayAdapter($clients);
    $pagerfanta = new PagerFanta($adapter);

    $pagerfanta->setMaxPerPage(1);

    return $this->twig->render(
      'user/index.html.twig',
      ['clients' => $pagerfanta]
    );
  }
}
