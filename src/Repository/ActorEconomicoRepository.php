<?php

namespace App\Repository;

use App\Entity\ActorEconomico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActorEconomico|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActorEconomico|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActorEconomico[]    findAll()
 * @method ActorEconomico[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActorEconomicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActorEconomico::class);
    }

    public function getFormasOrganizativas(){
        $qb=$this->createQueryBuilder('u');
        $qb->select('u.forma')
            ->orderBy('u.id', 'DESC')
        ->distinct();
        return $qb->getQuery()->getResult();
    }
    public function find_por_forma_roganizativa($forma,$texto){
        $qb=$this->createQueryBuilder('u');
        if($forma == 'all'){
            if (is_null($texto))
                $qb->select()
                    ->orderBy('u.id', 'DESC')
                    ->distinct();
            else
            $qb->select()
                ->where("u.nombre like :val")->setParameter('val', '%'.$texto.'%')
                ->orderBy('u.id', 'DESC')
                ->distinct();
        }
        else  {
            if (is_null($texto))
                $qb->select()
                    ->where('u.forma in(:forma)')->setParameter('forma',$forma)
                    ->orderBy('u.id', 'DESC')
                    ->distinct();
            else
            $qb->select()
                ->where('u.forma in(:forma)')->setParameter('forma',$forma)
                ->andWhere("u.nombre like :val")->setParameter('val', '%'.$texto.'%')
                ->orderBy('u.id', 'DESC')
                ->distinct();
        }
        return $qb->getQuery()->getResult();
    }
    // /**
    //  * @return ActorEconomico[] Returns an array of ActorEconomico objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActorEconomico
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
