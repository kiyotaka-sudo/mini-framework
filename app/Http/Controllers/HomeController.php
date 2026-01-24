<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\Response;
use Database\Models\User;

class HomeController
{
    public function index(Request $request): Response
    {
        $name = $request->query('name', 'apprenant');

        $model = User::make();
        $user = $model->find(1);
        if (!$user) {
            $user = $model->create([
                'name' => 'Apprenant',
                'email' => 'apprenant@example.com',
            ]);
        }

        return view('home', [
            'appName' => $_ENV['APP_NAME'] ?? 'MiniFramework',
            'name' => ucfirst($name),
            'user' => $user,
        ]);
    }

    public function users(Request $request): Response
    {
        return view('users', [
            'appName' => $_ENV['APP_NAME'] ?? 'MiniFramework',
        ]);
    }

    public function tasks(Request $request): Response
    {
        return view('tasks', [
            'appName' => $_ENV['APP_NAME'] ?? 'MiniFramework',
        ]);
    }

    public function dashboard(Request $request): Response
    {
        return view('dashboard', [
            'appName' => $_ENV['APP_NAME'] ?? 'MiniFramework',
        ]);
    }
}
