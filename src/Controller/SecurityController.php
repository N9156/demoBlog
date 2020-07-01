<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription",name="security_registration")
     */
    //1
    public function registration(Request $request, EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder)
    {
        $user = new User;

        $form =$this->createForm(RegistrationType::class, $user);
        dump($request);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            //on affecte un ROLE_USER pour chaque nouvelle inscription sur le blog
            //ROLE_USER : l'internaute a acces à tout le contenu du site, publier modifier mais il n'a pas acces à la partie backoffice
            $user->setRoles(["ROLE_USER"]);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success','Félicitations !! Vous êtes maintenant inscrit, vous pouvez maintenant vous connecter.');

            return $this->redirectToRoute('security_login');//pour rediriger apres inscription vers la page connexion

        }

        return $this->render('security/registration.html.twig',[
            'form' => $form->createView()
            ]);
    }
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //renvoie le message d'erreur en cas de mauvaise connexion, si l'internaute a saisi des identifiants incorrects au moment de la connexion
        $error = $authenticationUtils->getLastAuthenticationError();

        //permet de récuperer le dernier username (email) que l'internaute a saisie dans le formulaire de connexion en cas d'erreur de connexion
        $lastUsername = $authenticationUtils->getLastUsername();
        
        
        return $this->render('security/login.html.twig',[
                'last_username' => $lastUsername, //on envoie le message d'erreur et le dernier email saisie sur le template
                'error' => $error
                ]);
    }
    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        //cette methode ne retourne rien, il nous suffit d'avoir une route pour la deconnexion
    }
}
