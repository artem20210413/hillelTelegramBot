<?php


namespace App\Http\Services\User;


class UserDto
{
    private int $id;
    private bool $isBot;
    private string $firstName;
    private string $lastName;
    private string $username;
    private string $isPremium;

    public function __construct(string $jsonFrom)
    {
        $this->setUser($jsonFrom);
    }

    private function setUser(string $jsonFrom): self
    {
        $from = json_decode($jsonFrom);

        $this->id = $from->id;
        $this->isBot = $from->is_bot;
        $this->firstName = $from->first_name;
        $this->lastName = $from->last_name;
        $this->username = $from->username;
        $this->isPremium = $from->is_premium;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isBot(): bool
    {
        return $this->isBot;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }


    /**
     * @return string
     */
    public function getIsPremium(): string
    {
        return $this->isPremium;
    }

}
