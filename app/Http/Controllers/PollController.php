<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Option;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class PollController extends Controller
{
    public function launch(Request $request, $presentationId, $questionId)
    {
        $question = Question::with('options')->where('presentation_id', $presentationId)->findOrFail($questionId);
        $question->is_active = true;
        $question->save();

        Redis::publish('presentation-events', json_encode([
            'presentation_id' => $presentationId,
            'event' => 'poll-activated',
            'data' => [
                'questionId' => $question->id,
                'question' => $question->question_text,
                'options' => $question->options->map(function($o) {
                    return ['id' => $o->id, 'text' => $o->option_text];
                })
            ]
        ]));

        return response()->json(['success' => true]);
    }

    public function vote(Request $request, $presentationId)
    {
        $questionId = $request->input('question_id');
        $optionId = $request->input('option_id');
        $deviceId = $request->input('device_id');

        // prevent duplicate voting per device
        $existing = Vote::where('question_id', $questionId)->where('voter_device_id', $deviceId)->first();
        if ($existing) {
            return response()->json(['error' => 'Already voted'], 400);
        }

        Vote::create([
            'question_id' => $questionId,
            'option_id' => $optionId,
            'voter_device_id' => $deviceId
        ]);

        Option::where('id', $optionId)->increment('vote_count');

        // broadcast updated poll stats to presenter & projector screen
        $options = Option::where('question_id', $questionId)->get();
        Redis::publish('presentation-events', json_encode([
            'presentation_id' => $presentationId,
            'event' => 'poll-updated',
            'data' => [
                'questionId' => $questionId,
                'tally' => $options->pluck('vote_count', 'id')
            ]
        ]));

        return response()->json(['success' => true]);
    }
}
