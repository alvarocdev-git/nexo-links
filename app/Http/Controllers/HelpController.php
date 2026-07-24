<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

// Help center. FAQ items are translatable: each locale defines its own list in
// lang/<locale>/help.php as `faqs => [['q' => ..., 'a' => ...], ...]`. The contact
// target comes from config (support URL wins, else a mailto: to the support email).
class HelpController extends Controller
{
    public function __invoke(): View
    {
        return view('help.index', [
            'faqs' => (array) __('help.faqs'),
            'contactUrl' => config('nexo.support_url') ?: 'mailto:'.config('nexo.support_email', ''),
        ]);
    }
}
