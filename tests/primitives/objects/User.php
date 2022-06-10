<?php

declare(strict_types=1);

namespace primitives\objects;

use luverelle\pson\attributes\JsonProperty;

class User{

    #[JsonProperty("id")]
    private int $id = 1;

    #[JsonProperty("name")]
    private string $name;

    #[JsonProperty("rating")]
    private float $rating;

    /**
     * @var string[]
     */
    #[JsonProperty("friends")]
    private array $friends;

    #[JsonProperty("admin")]
    private bool $admin;

    /**
     * User constructor.
     * @param string   $name
     * @param float    $rating
     * @param string[] $friends
     * @param bool     $admin
     */
    public function __construct(string $name, float $rating, array $friends = [], bool $admin = false){
        $this->name = $name;
        $this->rating = $rating;
        $this->friends = $friends;
        $this->admin = $admin;
    }

    /**
     * @return int
     */
    public function getId() : int{
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName() : string{
        return $this->name;
    }

    /**
     * @return float
     */
    public function getRating() : float{
        return $this->rating;
    }

    /**
     * @return string[]
     */
    public function getFriends() : array{
        return $this->friends;
    }

    /**
     * @return bool
     */
    public function isAdmin() : bool{
        return $this->admin;
    }

    public function equals(User $user) : bool{
        return $this->id === $user->id &&
            $this->name === $user->name &&
            $this->rating === $user->rating &&
            $this->friends === $user->friends &&
            $this->admin === $user->admin;
    }
}