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

  #[Route('/users/page/{idPage}', name: 'user_index')]
  public function index($idPage = 1): string
  {
    $clients = $this->em->getRepository(Client::class)->findAllPaginated();


    $adapter = new ArrayAdapter($clients);
    $pagerfanta = new PagerFanta($adapter);

    $pagerfanta->setMaxPerPage(2);

    $pagerfanta->setCurrentPage($idPage);

    $url = $_SERVER['REQUEST_URI'];

    $previousPage = $idPage - 1 > 0 ? $idPage - 1 : 1;
    $explodeUrl = explode('/', $url);
    $explodeUrl[count($explodeUrl) - 1] = $previousPage;
    
    $urlPreviousPage = implode('/', $explodeUrl);

    $nextPage = $idPage + 1 < $pagerfanta->getNbPages() ? $idPage + 1 : $pagerfanta->getNbPages();
    $explodeUrl[count($explodeUrl) - 1] = $nextPage;
    $urlNextPage = implode('/', $explodeUrl);

    return $this->twig->render(
      'user/index.html.twig',
      [
        'clients' => $pagerfanta,
        'urlPreviousPage' => $urlPreviousPage,
        'urlNextPage' => $urlNextPage,
        ]
    );
  }

  #[Route('/user/{idUser}', name: 'user_show')]
  public function show($idUser): string
  {
    $client = $this->em->getRepository(Client::class)->find(1);

    var_dump($client);

    return $this->twig->render(
      'user/show.html.twig',
      ['client' => $client]
    );
  }
}
