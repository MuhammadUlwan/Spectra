<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PengaturanController extends Controller
{
    public function index()
    {
        return Inertia::render('pengaturan'); // karena filenya di resources/js/Pages/Pengaturan.vue
    }
}
