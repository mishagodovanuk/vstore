<?php

namespace Model;

/**
 *
 */
class Validator
{
    /**
     * @param $email
     * @return bool
     */
    public function isValidEmail($email): bool
    {
        // Use PHP built-in filter_var function with FILTER_VALIDATE_EMAIL flag
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @param $username
     * @return false|int
     */
    public function isValidUsername($username): bool|int
    {
        // Allows alphanumeric characters and underscores, with a length between 3 and 20 characters
        return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username);
    }

    /**
     * @param $password
     * @return false|int
     */
    public function isValidPassword($password): bool|int
    {
        // Requires at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password);
    }
}