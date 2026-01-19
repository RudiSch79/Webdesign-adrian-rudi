<?php

declare(strict_types=1);

function login_user(array $userRow): void
{
    $_SESSION['user'] = [
        'id'       => (int)$userRow['id'],
        'username' => $userRow['username'],
        'is_admin' => (int)$userRow['is_admin'],
    ];
}

function logout_user(): void
{
    unset($_SESSION['user']);
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!current_user()) {
        redirect('login.php');
    }
}