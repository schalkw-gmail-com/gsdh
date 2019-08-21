<?php

    namespace App\Http\Controllers;

    use App\Message;
    use App\MessageReplies;
    use Illuminate\Http\Request;
    use Auth;

    class MessageRepliesController extends Controller
    {
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            $replies = MessageReplies::all();
            return view('replies.index')->with('replies', $replies);
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function create()
        {
            return view('replies.create');
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            $data = $request->validate([
                'content' => 'required|min:1|max:255',
                'message_id' => 'required|integer'
            ]);
            $reply = new MessageReplies();
            $reply->content = $request->content;
            $reply->user()->associate(Auth::id());

            $message = Message::findOrFail($request->message_id);
            $message->replies()->save($reply);
            return redirect()->route('messages.show', $message->id);
        }

        /**
         * Display the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function show(MessageReplies $reply)
        {
            return view('replies.show')->with('reply', $reply);
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function edit(MessageReplies $reply)
        {
            return view('replies.edit')->with('reply', $reply);
        }

        /**
         * Update the specified resource in storage.
         *
         * @param  \Illuminate\Http\Request $request
         * @param  int                      $id
         * @return \Illuminate\Http\Response
         */
        public function update(Request $request, MessageReplies $reply)
        {
            $reply->update($request->only('reply'));
            return view('replies.show')->with('reply', $reply);
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param  int $id
         * @return \Illuminate\Http\Response
         */
        public function destroy(Request $request, MessageReplies $reply)
        { 
            $reply->delete();
            return redirect()->route('replies');
        }
    }
