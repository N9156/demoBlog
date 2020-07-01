<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *        fields = {"email"},
 *        message ="Un compte est déjà existant à cette adresse Email !"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *          message = "Cette adresse Email '{{ value }}' n'est pas valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8",minMessage="Vootre mot de passe doit contenir 8 caractères minimum")
     * @Assert\EqualTo(propertyPath="confirm_password", message="Les mots de passe ne correspondent pas")
     */
    private $password;
    
    /**
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passe ne correspondent pas")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    /*
    Pour pouvoir encoder le mdp, il faut que l'entité User implemente l'interface UserInterface
    Cette interface contient des méthodes que nous sommes obligé de déclarer :
    getPassword(), getUsername(),getRoles(), getSalt(), et eraseCredentials()
    */

    //cette methode est uniquement destinée à nettoyer les mdp en texte brut eventuellement stocké
    public function eraseCredentials()
    {

    }
    //renvoie la chaine de caractere non encodée que l'utilisateur a saisi, qui a été utlilisé à l'origine pour encoder le mdp
    public function getSalt()
    {

    }
    //cette methode renvoi un tableau de chaine de caractères où sont stockés les roles accordés à l'utilisateur
    public function getRoles()
    {
        //return ['ROLE_USER'];//retourne que le role user
        return $this->roles;
        //retourne les roles de la bdd
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    
}
