<?php
declare(strict_types=1);

namespace App\Controllers;

class HomeController
{
    public function index(): string
    {
        return "Welcome to the home page!";
    }

    public function about(): string
    {
        return "This is the about page.";
    }
}
