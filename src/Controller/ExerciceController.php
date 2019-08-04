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
  
  public function apprentissage(string $exercice, $parametres=array())
  {
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
    if ($bonneReponse->getNom() == $reponseFournie->getNom())
    {
      $msg = 'Oui, la bonne réponse est bien "';
      $this->setSss('nbBonnesRep', 1 + $this->getSss('nbBonnesRep'));
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
    // Les réponses ci-dessous ont déjà été utilisées dans ce niveau. Elles
    // ne doivent plus être posées pour le niveau en cours.
    // Ce tableau est une liste de Vocabulaire.id
    $dejaDemande = $this->getSss('dejaDemande');
    // Sélectionner une réponse qui n'a pas déjà été demandée pour le niveau en cours.
    $chercher = true;
    $bonneReponse = null;
    while ($chercher)
    {
      $bonneReponse = array_shift($possibilites);
      if (!(in_array($bonneReponse->getId(), $dejaDemande)))
      {
        $chercher = false;
        $dejaDemande[] = $bonneReponse->getId();
      }
    }
    $this->setSss('dejaDemande', $dejaDemande);
    
    $question = new Question();
    $question->addReponse($bonneReponse);

    // Niveau 7 et plus : plus besoin de liste de fausses réponses.
    if ($this->getSss('niveau') < 7) $this->addFaussesReponses($question, $bonneReponse);
    
    $this->setSss('question',$question);
  }
  
  /**
   * Cette fonction doit être surchargée.
   * Elle récupère la catégorie du niveau en cours dans la table Vocabulaire.
   * @return array d'objets Vocabulaire
   */
  protected function getVocalulaire() { return array(); }

  protected function addFaussesReponses(Question $question, Vocabulaire $bonneReponse)
  {
    // Aller chercher les réponses fausses qui ont été choisies pour cette réponse.
    $question->setPropositions($bonneReponse->getMauvaisesReponses());
    // Ajouter la bonne réponse à cette liste,
    $question->addProposition($bonneReponse);
    // et mélanger le tout.
    $question->melangerPropositions();
  }

  protected function getNiveau()
  {
    return $this->getDoctrine()
                ->getManager()
                ->getRepository('App:Score')
                ->getNiveau($this->getSss('exercice'));
  }

  protected function saveScore(int $niveau, int $score)
  {
      $this->obtentionTrophee(2);

      $em = $this->getDoctrine()
                 ->getManager();
      // Comparaison avec le meilleur score enregistré.
      $maxScore = $em->getRepository('App:Score')
                     ->getScoreDuNiveau($niveau, $this->getSss('exercice'));
      if ($maxScore->getScore() >= $score) return false;

      $maxScore->setScore($score);
      $em->persist($maxScore);
      $em->flush();

      return true;
  }
}
?>