<?php
namespace App\Service;

class Role 
{
    const ADMIN = "ROLE_ADMIN";
    const USER = "ROLE_USER";
    public static function getRoles(): array
    {
        return [
            "ADMIN" => self::ADMIN,
            "USER" =>   self::USER
        ];
    }
}