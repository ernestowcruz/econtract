<?php

namespace App\Repository;

use App\Entity\Empresa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Empresa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Empresa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Empresa[]    findAll()
 * @method Empresa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpresaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Empresa::class);
    }

    public function getListado()
    {
        return $this->getEntityManager()
            ->createQuery(
                "
                select a.id as empresaid, t.id as trabajadorid
                from App:Empresa a, App:Trabajador t ")
            ;
    }
    public function getEmpresaFromlink($link){
        $qb=$this->createQueryBuilder('u');
        $qb->select()
            ->where("u.link = :plink")->setParameter('plnk', $link);
        return $qb->getQuery()->getResult();
    }
    public function getDivisiones($link)
    {
        return $this->createQueryBuilder('e')
            ->where("e.link = :link and e.tipo='division'" )
            ->setParameter('link', $link)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Empresa[] Returns an array of Empresa objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Empresa
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
