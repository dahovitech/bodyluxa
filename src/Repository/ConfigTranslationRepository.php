<?php

namespace App\Repository;

use App\Entity\ConfigTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConfigTranslation>
 *
 * @method ConfigTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigTranslation[]    findAll()
 * @method ConfigTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigTranslation::class);
    }
}
