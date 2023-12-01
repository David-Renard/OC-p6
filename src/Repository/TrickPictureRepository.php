<?php

namespace App\Repository;

use App\Entity\TrickPicture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrickPicture>
 *
 * @method TrickPicture|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrickPicture|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrickPicture[]    findAll()
 * @method TrickPicture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickPictureRepository extends ServiceEntityRepository
{


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrickPicture::class);
    }
}
