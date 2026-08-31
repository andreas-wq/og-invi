<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class InviteController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/invite', [
            // Ganti dengan route halaman undangan utamamu kalau bukan root '/'
            // contoh: route('undangan.show')
            'invitationUrl' => url('/'),
        ]);
    }
}