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
}
