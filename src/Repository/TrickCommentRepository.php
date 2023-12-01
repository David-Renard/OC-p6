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

    private const LIMIT = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrickComment::class);
    }


    public function findCommentsPaginated(string $slug, int $page): array
    {
        $comments = [];

        $query = $this->createQueryBuilder('c')
            ->join('c.trick', 't')
            ->andWhere("t.slug = :slug")
            ->setParameter('slug', $slug)
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($page * self::LIMIT);

        $paginator = new Paginator($query);
        $data      = $paginator->getQuery()->getResult();

        if (empty($data)) {
            return $comments;
        }

        // Set nbPages
        $pages = ceil($paginator->count() / self::LIMIT);

        // Set comments array
        $comments['data']  = $data;
        $comments['pages'] = $pages;
        $comments['page']  = $page;

        return $comments;
    }
}
