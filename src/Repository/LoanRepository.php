<?php

namespace App\Repository;

use App\Entity\Loan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Loan>
 *
 * @method Loan|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loan|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loan[]    findAll()
 * @method Loan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loan::class);
    }

    public function findMaxId()
    {
        return $this->createQueryBuilder('l')
            ->select('MAX(l.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function loanExistsForBookAndUser($bookId, $userId): bool
    {
        $count = $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->where('l.book = :bookId')
            ->andWhere('l.user = :userId')
            ->setParameter('bookId', $bookId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }
}
