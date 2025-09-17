<?php

namespace App\Repository;

use App\Entity\Config;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Config>
 *
 * @method Config|null find($id, $lockMode = null, $lockVersion = null)
 * @method Config|null findOneBy(array $criteria, array $orderBy = null)
 * @method Config[]    findAll()
 * @method Config[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Config::class);
    }

    public function findActive(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.isActive = :active')
            ->setParameter('active', true)
            ->orderBy('c.sortOrder', 'ASC')
            ->addOrderBy('c.configKey', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByKey(string $key): ?Config
    {
        return $this->findOneBy(['configKey' => $key, 'isActive' => true]);
    }

    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.configType = :type')
            ->andWhere('c.isActive = :active')
            ->setParameter('type', $type)
            ->setParameter('active', true)
            ->orderBy('c.sortOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
