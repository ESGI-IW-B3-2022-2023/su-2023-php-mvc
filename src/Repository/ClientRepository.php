<?php

namespace App\Repository;
use Doctrine\ORM\EntityRepository;

class ClientRepository extends EntityRepository{

  public function findByNameInOrder($name){
    return $this->createQueryBuilder('c')
    ->where('c.name LIKE :name')
    ->orderBy('c.firstname', 'ASC')
    ->setParameter('name', '%'.$name.'%')
    ->getQuery()
    ->getResult();
  }
}
