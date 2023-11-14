<?php

namespace App\Repository;

use App\Entity\TrickComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrickComment>
 *
 * @method TrickComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrickComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrickComment[]    findAll()
 * @method TrickComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrickComment::class);
    }

//    /**
//     * @return TrickComment[] Returns an array of TrickComment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


    public function findCommentsPaginated(string $slug, int $page, int $limit = 5): array
    {
        if ($limit < 1) {
            $limit = 5;
        }

        $comments = [];

        $query = $this->createQueryBuilder('c')
            ->join('c.trick', 't')
            ->andWhere("t.slug = :slug")
            ->setParameter('slug', $slug)
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult($limit * ($page - 1));

        $paginator = new Paginator($query);
        $data      = $paginator->getQuery()->getResult();

        if (empty($data)) {
            return $comments;
        }

        // set nbPages
        $pages = ceil($paginator->count() / $limit);

        // set comments array
        $comments['data']  = $data;
        $comments['pages'] = $pages;
        $comments['page']  = $page;
        $comments['limit'] = $limit;

        return $comments;
    }

//    public function findOneBySomeField($value): ?TrickComment
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
