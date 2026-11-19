<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function show(User $user): View
    {
        $user->load(['reviews.eatery', 'reviews.user']);

        return view('reviewers.show', ['reviewer' => $user]);
    }
}
