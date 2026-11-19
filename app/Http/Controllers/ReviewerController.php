<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class ReviewerController extends Controller
{
    public function show(User $user): View
    {
        $reviews = $user->reviews()
            ->with('eatery')
            ->latest()
            ->latest('id')
            ->get();

        return view('reviewers.show', ['reviewer' => $user, 'reviews' => $reviews]);
    }
}
