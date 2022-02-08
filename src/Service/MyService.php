<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;

class MyService
{
  private $manager;
  private $entityType;

  public function __construct(string $entityType, EntityManagerInterface $manager)
  {
    $this->entityType = $entityType;
    $this->manager = $manager;
  }

  public function getPerson($id = 1)
  {
    $query = $this->manager->createQuery(
      'SELECT p FROM ' . $this->entityType . " p WHERE p.id = {$id}"
    );
    try {
      return $query->getSingleResult();
    } catch (NoResultException $e) {
      return null;
    }
  }

  public function getMessage()
  {
    $msgs = [
      'これは、オリジナルのメッセージサービスです',
      'これは、新しいメッセージです',
      'おなかがすいた',
      '今日の晩御飯は、グラタンにしようかな'
    ];
    $res = array_rand($msgs);
    return $msgs[$res];
  }
}