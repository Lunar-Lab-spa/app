<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ODM\Document]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ODM\Id]
    private $id;

    #[ODM\Field("email", "string")]
    #[ODM\Index(unique: true)]
    #[Assert\Email()]
    #[Assert\NotBlank()]
    private $email;

    #[ODM\Field("password", "string")]
    #[Assert\NotBlank()]
    private $password;

    #[ODM\Field("roles", "collection")]
    private $roles;

    public function getId()
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles ?: [];
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function rolesAsString(): string
    {
        return implode(", ", $this->roles);
    }
}