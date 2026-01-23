<?php

namespace Database\Models;

class User extends Model
{
    protected string $table = 'users';
    protected array $fillable = ['name', 'email'];

    public function findByEmail(string $email): ?array
    {
        return $this->database->fetch(
            sprintf('SELECT * FROM %s WHERE email = :email LIMIT 1', $this->getTable()),
            ['email' => $email]
        );
    }
}
