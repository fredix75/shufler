<?php
namespace SHUFLER\ShuflerBundle\Entity;

/**
 * FluxRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FluxRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Get One Flux
     * 
     * @param unknown $id
     * @return mixed|\Doctrine\DBAL\Driver\Statement|array|NULL
     */
    function getFlux($id)
    {
        return $this->_em->createQueryBuilder()
            ->select('a')
            ->where('a.id= :id')
            ->setParameter('id', $id)
            ->from('SHUFLERShuflerBundle:flux', 'a')
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * Get RSS
     * 
     * @return array
     */
    function getRSS()
    {
        return $this->_em->createQueryBuilder()
            ->select('a')
            ->where('a.type= :type')
            ->setParameter('type', 1)
            ->from('SHUFLERShuflerBundle:Flux', 'a')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get Podcasts
     * 
     * @return array
     */
    function getPodcast()
    {
        return $this->_em->createQueryBuilder()
            ->select('a')
            ->where('a.type= :type')
            ->setParameter('type', 2)
            ->from('SHUFLERShuflerBundle:Flux', 'a')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get Radios
     * 
     * @return array
     */
    function getRadios()
    {
        return $this->_em->createQueryBuilder()
            ->select('a')
            ->where('a.type= :type')
            ->setParameter('type', 3)
            ->from('SHUFLERShuflerBundle:Flux', 'a')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
