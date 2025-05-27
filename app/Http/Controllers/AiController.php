<?php

namespace App\Http\Controllers;

use App\Models\AI\AiConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use OpenAI;

class AiController extends Controller
{
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = Auth::user();
        $message = $request->input('message');

        $openaiApiKey = env('OPENAI_API_KEY');

        if (!$openaiApiKey) {
            $aiConversation = AiConversation::create([
                'user_id' => $user->id,
                'message' => $message,
                'is_user' => true,
                'response' => 'The OpenAI API key is not configured. Please set the OPENAI_API_KEY environment variable.',
            ]);

            return response()->json($aiConversation, 201);
        }

        $client = OpenAI::client($openaiApiKey);

        try {
            $response = $client->completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $message,
                'max_tokens' => 150,
            ]);

            $aiResponse = $response['choices'][0]['text'];
        } catch (\Exception $e) {
            $aiResponse = 'Error communicating with OpenAI API: ' . $e->getMessage();
        }

        $aiConversation = AiConversation::create([
            'user_id' => $user->id,
            'message' => $message,
            'response' => $aiResponse,
            'is_user' => true,
        ]);

        return response()->json($aiConversation, 201);
    }
}
