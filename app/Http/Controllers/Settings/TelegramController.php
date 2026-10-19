<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\Telegram\TelegramNotificationService;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    /**
     * Send instant test message to Telegram.
     */
    public function test(Request $request, TelegramNotificationService $telegram)
    {
        $customToken = $request->input('bot_token');
        $customChatId = $request->input('chat_id');

        $result = $telegram->sendTestMessage($customToken, $customChatId);

        return response()->json($result);
    }

    /**
     * Broadcast announcement to Telegram channel/group.
     */
    public function broadcast(Request $request, TelegramNotificationService $telegram)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:4000',
        ]);

        $title = $request->input('title');
        $message = $request->input('message');
        $sender = auth()->user()?->name ?? 'រដ្ឋបាលសាលា';

        $result = $telegram->sendBroadcast($title, $message, $sender);

        if ($result['success']) {
            return back()->with('success', 'សេចក្តីជូនដំណឹងត្រូវបានផ្សាយទៅកាន់ Telegram ដោយជោគជ័យ!');
        }

        return back()->with('error', $result['message']);
    }
}
