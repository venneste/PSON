<?php

declare(strict_types=1);

namespace primitives;

require_once 'objects/User.php';

use primitives\objects\User;
use luverelle\pson\PSON;
use PHPUnit\Framework\TestCase;

class PrimitiveTest extends TestCase{

    private const JSON_ARRAY = [
        "id" => 1,
        "name" => "David",
        "rating" => 3.9,
        "friends" => ["Kelly"],
        "admin" => false
    ];

    private User $user;

    protected function setUp() : void{
        $this->user = new User("David", 3.9, ["Kelly"]);
    }

    public function testPrimitiveFromJson(){
        //from json as class
        $this->assertObjectEquals($this->user, PSON::fromJsonArrayAsClass(self::JSON_ARRAY, User::class));

        //from json as object
        $user = new User("dummy name", 1.7);
        PSON::fromJsonArray(self::JSON_ARRAY, $user);
        $this->assertObjectEquals($this->user, $user);
    }

    public function testPrimitiveToJson(){
        $this->assertSame(self::JSON_ARRAY, PSON::toJsonArray($this->user));
    }
}