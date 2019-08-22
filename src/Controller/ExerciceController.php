<?php
namespace App\Controller;
 
use App\Entity\Vocabulaire;
use App\Objet\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
 
class ExerciceController extends ParentController
{
  /******************************************************************************
  *                           Types de questions :                              *
  * 1. choix multiple avec reponse unique                                       *
  * 2. choix multiple avec possibilité de donner plusieurs réponses ou aucune   *
  * 3. remettre dans l'ordre                                                    *
  * 4. taper une reponse                                                        *
  * 5. construction d'une réponse en utilisant un clavier spécial               *
  ******************************************************************************/
  protected $typeDeQuestion;

  public function question(string $exercice)
  {
    $this->setSss('exercice', $exercice);
    $this->initNiveau();
    $this->poserQuestion();
    return $this->render('/'.$this->getSss('modele').'.html.twig');
  }
  
  public function correction(string $exercice, int $numRep)
  {
    $this->setSss('exercice', $exercice);
    $this->corriger($numRep);
    return $this->render('/'.$this->getSss('modele').'.html.twig');
  }
  
  /**
   * apprentissage est appelée pour ouvrir une page leçon. Afin d'éviter que
   * la leçon soit consultée entre chaque question, le niveau est réinitialisé.
   * De plus, l'ouverture d'une page Leçon est récompensée par un trophée.
   */
  public function apprentissage(string $exercice, $parametres=array())
  {
    $this->reinitNiveau();
    $this->obtentionTrophee(103); // Trophée 103 : Aller sur une page de leçon.
    $this->setSss('exercice', $exercice);
    $this->reinitNiveau();
    return $this->render('/apprendre_'.$exercice.'.html.twig', $parametres);
  }
  
  protected function corriger(int $numRep)
  {
    $question = $this->getSss('question');
    $bonneReponse = $question->getReponse();
    $reponseFournie = $question->getProposition($numRep);
    $msg = "";
    if ($bonneReponse->getId() == $reponseFournie->getId())
    {
      $msg = 'Oui, la bonne réponse est bien "';

      // Si on a cliqué sur Refresh, on valide cette page pour la deuxième fois.
      // Le score ne doit pas être incrémenté. Vérifier que la question de ce
      // niveau n'a pas déjà été comptabilisée.
      if (!$this->dejaValidee())
      {
        $this->setSss('nbBonnesRep', 1 + $this->getSss('nbBonnesRep'));
      }
    }
    else
    {
      $msg = 'Non. La bonne réponse était "';
    }
    $this->setSss('correction', $msg);
    $this->complementCorrection($bonneReponse);

    if ($this->getSss('numQuestion') >= 6)
    {
      $this->saveScore($this->getSss('niveau'),
                       $this->getSss('nbBonnesRep')
                      );
    }
  }

  protected function poserQuestion()
  {
    $this->setSss('correction', '');
    
    $this->setSss('numQuestion', $this->getSss('numQuestion') + 1);

    $this->getQuestion();
  }

  /* Fonction à surcharger
  */
  protected function complementCorrection(Vocabulaire $bonneReponse) { return array(); }

  /* Cette fonction est appelée avant chaque question.
   * Elle calcule le niveau (seulement avant la 1ère question) et,
   * dans les classes qui héritent de celle-ci, trouve le template
   * à utiliser.
   */
  protected function initNiveau()
  {
    if ($this->getSss('numQuestion') == 0)
    {
      // On ne calcule le niveau qu'au moment de la première question.
      $this->setSss('niveau', $this->getNiveau()+1);
    }
  }
  
  /* Pour les termes italiens, la tonalité et le rythme, les premiers niveaux
   * sont des QCM à une seule réponse, et ces réponses proviennent directement
   * de la table Vocabulaire.
   * A partir du niveau 7, la réponse doit être écrite par l'utilisateur, donc
   * plus besoin de récupérer une liste de fausses réponses.
   */
  protected function getQuestion()
  {
    // Récupérer les réponses possibles pour ce niveau.
    $possibilites = $this->getVocalulaire();
    shuffle($possibilites);
    // Les questions ci-dessous ont déjà été utilisées dans ce niveau. Elles
    // ne doivent plus être posées pour le niveau en cours.
    // Ce tableau est une liste de Vocabulaire.id
    $dejaDemande = $this->getSss('dejaDemande');
    if ($dejaDemande == null) $dejaDemande = array();
    // Sélectionner une réponse qui n'a pas déjà été demandée pour le niveau en cours.
    $chercher = true;
    $bonneReponse = null;
    $bonId = null;
    while ($chercher)
    {
      // Tirer une de ces propositions au hazard.
      $i = random_int(0, count($possibilites)-1);
      // A-t-elle déjà été proposée comme question ?
      if (!(in_array($possibilites[$i]->getId(), $dejaDemande)))
      {
        // Non. Donc on la choisit comme question.
        $chercher = false;
        $bonneReponse = $possibilites[$i];
        unset($possibilites[$i]);
        // Mémoriser son id afin de ne plus l'utiliser pendant ce niveau.
        $dejaDemande[] = $bonneReponse->getId();
      }
    }
    $this->setSss('dejaDemande', $dejaDemande);
    
    $question = new Question();
    $question->addReponse($bonneReponse);

    // Niveau 7 et plus : plus besoin de liste de fausses réponses.
    if ($this->getSss('niveau') < 7)
    {
      $this->addFaussesReponses($question, $bonneReponse, $possibilites);
    }
    
    $this->setSss('question',$question);
  }
  
  /**
   * Cette fonction doit être surchargée.
   * Elle récupère la catégorie du niveau en cours dans la table Vocabulaire.
   * $question La question, qui a déjà sa bonne réponse, mais pas ses propositions
   * $bonneReponse La bonne réponse, à ajouter aux propositions
   * $tableau contient toutes les réponses sauf la bonne. On ne l'utilise que
   *          si l'objet $bonneReponse n'a pas ses propres propositions.
   *
   * @return array d'objets Vocabulaire
   */
  protected function getVocalulaire() { return array(); }

  protected function addFaussesReponses(Question $question, Vocabulaire $bonneReponse, array $tableau = null)
  {
    // Aller chercher les réponses fausses qui ont été choisies pour cette réponse.
    // Utiliser le tableau s'il n'y en a pas assez (moins de 3).
    $mr = $bonneReponse->getMauvaisesReponses();
    if ($mr == null || count($mr) < 3) $question->setPropositions($tableau);
    else $question->setPropositions($mr);
    // Ajouter la bonne réponse à cette liste,
    $question->addProposition($bonneReponse);
    // et mélanger le tout.
    $question->melangerPropositions();
    // DEBUG
    // Il doit y avoir 6 propositions
    if (count($question->getPropositions()) != 6)
    {
      throw new Exception("Erreur. ".count($question->getPropositions())." propositions au lieu de 6.");
    }
  }

  protected function getNiveau()
  {
    return $this->getDoctrine()
                ->getManager()
                ->getRepository('App:Score')
                ->getNiveau($this->getSss('exercice'));
  }

  protected function saveScore(int $niveau, int $scoreReel)
  {
      $this->obtentionTrophee(101);

      // Pour marquer l'effort qui a été fait, même si aucune bonne réponse n'a
      // été donnée, on accorde un score de 1 au joueur.
      $score = $scoreReel;
      if ($score == 0) $score = 1;

      $em = $this->getDoctrine()
                 ->getManager();
      // Comparaison avec le meilleur score enregistré.
      $maxScore = $em->getRepository('App:Score')
                     ->getScoreDuNiveau($niveau, $this->getSss('exercice'));
      if ($maxScore->getScore() >= $score) return false;

      $this->verificationTropheesOnScore($this->getSss('exercice'),$niveau,$score,$maxScore->getScore());

      $maxScore->setScore($score);
      $em->persist($maxScore);
      $em->flush();

      return true;
  }
}
?>