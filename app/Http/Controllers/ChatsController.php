<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetMessagesRequest;
use App\Models\Message;
use App\Models\User;
use App\Repository\API\MessageRepo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ChatsController extends BaseController
{
    public function __construct(MessageRepo $repo)
    {
        ///$this->middleware('auth');
        $this->service = $repo;
    }

    /**
     * Show chats
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Message/Messages', [
            'messages' => Message::with('user')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'to_user' => 'required|exists:users,id',
            'replied_to' => 'sometimes|exists:messages,id',
        ]);
        $uuid =  Str::uuid();
        $validated = array_merge($validated, ['id' => $uuid ,'from_user' => auth()->user()->id]);

        // Save message to database
        $message = Message::create([
            'id' => $validated['id'],
            'message' => $validated['message'],
            'to_user' => $validated['to_user'],
            'from_user' => $validated['from_user'],
            'replied_to' => $validated['replied_to'] ?? null,
        ]);

        // Broadcast the message
        broadcast(new MessageSent(User::all()->find($validated['from_user']), $message))->toOthers();

        return response()->json($message->with(['toUser', 'fromUser']));
    }

    /**
     * Fetch all messages
     *
     * @return \App\Http\Resources\BaseCollection
     */
    public function fetchMessages(GetMessagesRequest $request): \App\Http\Resources\BaseCollection
    {


        return parent::_index($request);
    }

    public function fetchConversations(Request $request)
    {
        $perPage = $request->get('per_page', 10); // Default to 10 conversations per page

        // Use MAX to get the most recent 'created_at' for each user
        $conversations = $this->service->model
            ->select('from_user', DB::raw('MAX(created_at) as latest_created_at'))
            ->groupBy('from_user')
            ->orderBy('latest_created_at', 'desc') // Order by the most recent created_at
            ->with(['fromUser:id,fname,mname,lname,suffix']) // Eager load related user data
            ->get();

        return $this->sendResponse($conversations);
    }



    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return string[]
     */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $message = $user->messages()->create([
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSent($user, $message))->toOthers();

        return ['status' => 'Message Sent!'];
    }

}
