<?php

namespace App\Http\Controllers;

use App\Models\Presentation;
use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function index()
    {
        $presentations = Presentation::with('slides')->orderBy('created_at', 'desc')->get();
        return view('presenter.list', compact('presentations'));
    }

    public function show($id)
    {
        $presentation = Presentation::with('slides')->findOrFail($id);
        return view('presenter.dashboard', compact('presentation'));
    }

    public function store(Request $request)
    {
        $presentation = Presentation::create([
            'title' => $request->input('title'),
            'speaker_name' => $request->input('speaker_name', 'Speaker'),
            'access_code' => strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6)),
            'status' => 'draft'
        ]);

        return redirect()->route('presentation.show', $presentation->id);
    }
}
