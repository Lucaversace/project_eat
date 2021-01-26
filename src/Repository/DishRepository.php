<?php

namespace App\Repository;

use App\Entity\Dish;
use App\Entity\LineArticle;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dish|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dish|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dish[]    findAll()
 * @method Dish[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishRepository extends ServiceEntityRepository
{
    protected $em;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($registry, Dish::class);
    }

    // /**
    //  * @return Dish[] Returns an array of Dish objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


 /*    public function findDishByStatusAndUser($id): ?Dish
    {
        return $this->createQueryBuilder('d')
            ->innerJoin(LineArticle::class, 'l', 'd.id = l.dish_id')
            ->where(" id_order_id IN ( SELECT id FROM order AS o WHERE status = 'LIVRÉE' AND user_id = :id) ")
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    } */

}

