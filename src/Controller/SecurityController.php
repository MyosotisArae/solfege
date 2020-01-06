<?php

namespace App\Controller;

use App\Entity\Musicien;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends ParentController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isSetUser())
        {
          return $this->redirectToRoute('main');
        }

        return $this->render('/security/login.html.twig', ['last_username' => '', 'error' =>'']);
    }
    
    /**
     * L'utilisateur tente de se logguer sur son compte.
     * @Route("/check", name="check")
     */
    public function checkPassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
      if ($this->isSetUser()) {
        $this->redirectToRoute('main');
      }
      $msgErreur = "";
      $nom = "";
      $pwd = "";
      $musicien = new Musicien;

      $this->util = $this->get("utilitaires");
      if ($this->util->estRenseignePOST('inputUsername')) 
      {
        $nom = $_POST['inputUsername']; 
        $musicien = $this->getDoctrine()
                         ->getManager()
                         ->getRepository('App:Musicien')
                         ->getMusicien($nom);
      }
      else
      {
        $msgErreur = "Le nom du musicien doit être mentionné.";
      }
      if ($this->util->estRenseignePOST('inputPassword')) { $pwd = $_POST['inputPassword']; }

      if ($musicien->getId() == 0)
      {
        if (strlen($nom) > 0) $msgErreur = "Aucun de nos élèves n'a pour nom ".$nom.". Désolé.";
      }
      else
      {
        // Ce nom existe en base.
        // Vérifier le mot de passe.
        if ($encoder->isPasswordValid($musicien, $pwd))
        {
          $this->setUser($musicien);
          return $this->render('main.html.twig', ["session" => $_SESSION, "msgErreur" => $msgErreur]);
        }
        else $msgErreur = "Ce n'est pas le bon mot de passe.";
      }
      return $this->render('security/login.html.twig', ["session" => $_SESSION, "msgErreur" => $msgErreur]);
    }
    
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
      $this->resetUser();
      return $this->render('/security/login.html.twig', ["session" => $_SESSION, 'msgErreur' => '']);
    }
}
