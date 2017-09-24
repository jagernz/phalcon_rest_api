<?php

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;

class Users extends Model
{
    protected $id;
    protected $password;
    protected $token;
    public $phone;
    public $user_status;

    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            "name",
            new Uniqueness(
                [
                    "message" => "Name must be unique"
                ]
            )
        );

        $validator->add(
            "phone",
            new Uniqueness(
                [
                    "message" => "Phone must be unique"
                ]
            )
        );

        return $this->validate($validator);
    }

    public function setPassword($password)
    {
        if ( strlen($password) < 6 ) {
            throw new InvalidArgumentException(
                'Password must be more then 6 signs'
            );
        };

        $this->password = md5($password);
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getUserStatus()
    {
        return $this->user_status;
    }

}