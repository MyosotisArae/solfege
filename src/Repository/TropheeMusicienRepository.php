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

    /**
     * Vérifie si le musicien id possède le trophée t.
     * Si oui, t est mis à jour avec l'id du musicien.
     */
    public function completerListeTrophee(Trophee $t, int $id)
    {
      $qb = $this->createQueryBuilder('tm')
                 ->andwhere('tm.musicien = :mus')
                 ->andwhere('tm.trophee = :t')
                 ->setParameter('mus', $id)
                 ->setParameter('t', $t->getId())
                 ->orderBy('tm.trophee');

      $listeTropheesMusicien = $qb->getQuery()->getResult();
      if (count($listeTropheesMusicien) == 1) { $t->setPossede(); }
      return $t;
    }

    /**
     * Met à jour l'indicateur "trophée déjà possédé par le musicien actuellement connecté"
     * dans tous les éléments de la liste.
     * liste : liste de tous les trophées possibles, triée par id croissants
     * id : musicien actuellement connecté
     */
    public function completerListeTrophees(array $liste, int $id)
    {
      $qb = $this->createQueryBuilder('tm')
                 ->andwhere('tm.musicien = :mus')
                 ->setParameter('mus', $id)
                 ->orderBy('tm.trophee');

      // Ceci est la liste des trophées déjà possédés par le musicien id.
      $listeTropheesMusicien = $qb->getQuery()->getResult();

      // Les 2 listes sont triées selon l'identifiant trophee (qui est id dans liste et trophee dans listeTropheesMusicien).
      // On va parcourir chacune des listes et mettre à jour le champ "trophée possédé" quand il est dans listeTropheesMusicien.
      $indexTrophees = 0; // Indice pour parcourir la liste complète des trophées
      $indexMusicien = 0; // Indice pour parcourir uniquement la liste du musicien actuel
      $idTrophee = 0;
      $continuerAchercher = true;
      // Méthode de traitement par rupture :
      // Comme les deux listes sont triées selon le même champ (l'id du trophée), et que liste est forcément plus fournie
      // que listeTropheesMusicien, on n'avance d'un cran dans listeTropheesMusicien que lorsqu'on a trouvé l'élément dans
      // liste. Et dans liste, on avance d'un cran à chaque itération.
      while ($continuerAchercher)
      {
        $idTrophee = $liste[$indexTrophees]->getId();
        if ($idTrophee == $listeTropheesMusicien[$indexMusicien]->getTrophee())
        {
            $liste[$indexTrophees]->setPossede();
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