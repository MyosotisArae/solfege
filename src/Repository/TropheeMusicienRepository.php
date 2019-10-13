<?php
namespace App\Repository;

use App\Entity\TropheeMusicien;
use App\Entity\Trophee;
use App\Objet\P_constantes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TropheeMusicienRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TropheeMusicien::class);
        $this->cst = new P_constantes();
    }

    public function completerListeTrophee(Trophee $t)
    {
      $qb = $this->createQueryBuilder('tm')
                 ->andwhere('tm.musicien = :mus')
                 ->andwhere('tm.trophee = :t')
                 ->setParameter('mus', $this->cst->getIdMusicien())
                 ->setParameter('t', $t->getId())
                 ->orderBy('tm.trophee');

      $listeTropheesMusicien = $qb->getQuery()->getResult();
      if (count($listeTropheesMusicien) == 1) { $t->setMusicien($this->cst->getIdMusicien()); }
      return $t;
    }

    public function completerListeTrophees(array $liste)
    {
      $qb = $this->createQueryBuilder('tm')
                 ->andwhere('tm.musicien = :mus')
                 ->setParameter('mus', $this->cst->getIdMusicien())
                 ->orderBy('tm.trophee');

      $listeTropheesMusicien = $qb->getQuery()->getResult();

      // Les 2 listes sont triées selon l'identifiant trophee (qui est id dans liste et trophee dans listeTropheesMusicien).
      // On va parcourir chacune des listes et mettre à jour le champ musicien quand il est dans listeTropheesMusicien.
      $indexTrophees = 0;
      $indexMusicien = 0;
      $idTrophee = 0;
      $continuerAchercher = true;
      while ($continuerAchercher)
      {
        $idTrophee = $liste[$indexTrophees]->getId();
        if ($idTrophee == $listeTropheesMusicien[$indexMusicien]->getTrophee())
        {
            $liste[$indexTrophees]->setMusicien($this->cst->getIdMusicien());
            // La correspondance avec un élément de Trophee_Musicien a été trouvée.
            // On peut maintenant rechercher le prochain.
            $indexMusicien += 1;
            if ($indexMusicien >= count($listeTropheesMusicien)) $continuerAchercher = false;
        }
        $indexTrophees += 1;
      }
      return $liste;
    }

}