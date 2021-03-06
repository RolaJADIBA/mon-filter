<?php

namespace App\Repository;

use App\Entity\Annonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonces>
 *
 * @method Annonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonces[]    findAll()
 * @method Annonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonces::class);
    }

    /**
     * Recherche les produits en fonction du formulaire
     * @return void 
     */
    public function search($mots){

        $query = $this->createQueryBuilder('a');
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(a.titre, a.description) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }

        return $query->getQuery()->getResult();
    }

    /**
     * Return number of "annonces" par jour
     * @return void
     */
    public function countByDate()
    {
        // $query = $this->createQueryBuilder('a');
        // $query->select('count(*), SUBSTRING(created_at,1,10) FROM `annonces` GROUP BY created_at');
        $query = $this->getEntityManager()->createQuery("
            SELECT SUBSTRING(a.created_at, 1, 10) as dateAnnonces, COUNT(a) as count
            FROM App\Entity\Annonces a GROUP BY dateAnnonces
        ");
        return $query->getResult();
    }

    public function setInterval($from, $to, $cat = null)
    {
        // $query = $this->getEntityManager()->createQuery("
        // SELECT a FROM App\Entity\Annonces a WHERE a.created_at > :from AND a.created_at < :to 
        // ");
        // $query->setParameter(':from', $from);
        // $query->setParameter(':to', $to);
        // return $query->getResult();
        $query = $this->createQueryBuilder('a')
            ->where('a.created_at > :from')
            ->andWhere('a.created_at < :to')
            ->setParameter(':from', $from)
            ->setParameter(':to', $to);
            if($cat != null){
                $query->leftJoin('a.categorie','c')
                      ->andWhere('c.id = :cat')
                      ->setParameter(':cat', $cat);
            }
        return $query->getQuery()->getResult();    
    }


    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Annonces $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Annonces $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Annonces[] Returns an array of Annonces objects
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
    public function findOneBySomeField($value): ?Annonces
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
