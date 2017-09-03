<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function showByCode($code)
    {
        $presentation = Presentation::with(['slides', 'questions.options'])
            ->where('access_code', strtoupper($code))
            ->firstOrFail();

        return view('audience.mobile', compact('presentation'));
    }

    public function venueDisplay($code)
    {
        $presentation = Presentation::with('slides')
            ->where('access_code', strtoupper($code))
            ->firstOrFail();

        return view('hall.display', compact('presentation'));
    }
}
