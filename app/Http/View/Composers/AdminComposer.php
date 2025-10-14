<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Profile;

class AdminComposer
{
    public function compose(View $view)
    {
        $profile = Profile::getMain();
        $view->with('profile', $profile);
    }
}
