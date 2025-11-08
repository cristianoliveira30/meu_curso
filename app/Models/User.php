<?php

namespace App\Models;

class User
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $role;
    public $address;
    public $city;
    public $state;
    public $zip_code;
    public $created_at;
    public $updated_at;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
