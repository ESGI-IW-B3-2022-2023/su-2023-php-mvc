<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;

class ClientRepository extends EntityRepository{

  public function findAllPaginated(){
    return $this->createQueryBuilder('c')
    ->getQuery()
    ->getResult();
  }
}
