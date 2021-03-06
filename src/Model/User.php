<?php

namespace CommerceKitty\Model;

/**
 * @TODO Plain Password
 */
class User implements UserInterface
{
    /**
     */
    protected $id;

    /**
     */
    protected $email;

    /**
     */
    protected $roles = [];

    /**
     * @var string The hashed password
     */
    protected $password;

    /**
     * @var string
     */
    protected $currentPassword;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     */
    public function __toString(): string
    {
        // Cast to string incase email has not yet been set
        return (string) $this->email;
    }

    /**
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     */
    public function setId(string $id): self
    {
        if ($this->id) {
            throw new \Exception('ID has already been set and cannot be modified');
        }

        $this->id = $id;

        return $this;
    }

    /**
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     */
    public function setEmail(string $email): self
    {
        $this->email = strtolower($email);

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it
        // here
        $this->plainPassword   = null;
        $this->currentPassword = null;
    }

    /**
     */
    public function getCurrentPassword(): ?string
    {
        return $this->currentPassword;
    }

    /**
     */
    public function setCurrentPassword(string $password): self
    {
        $this->currentPassword = $password;

        return $this;
    }

    /**
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     */
    public function setPlainPassword(string $password): self
    {
        $this->plainPassword = $password;

        return $this;
    }
}
