<?php

namespace App\Services;

use App\Models\Feedback;
use App\Models\User;
use App\Models\Voting;
use App\Models\Comment;
use Config;

use DataTables;

class FeedbackServices
{


    public function getdata()
    {

        return Feedback::with('like', 'dislike', 'comment_data.user')->paginate(10);
//        return Feedback::with('like', 'dislike')->orderBy('id', 'desc')->paginate(10);
    }

    public function store($request)
    {
        $data = $request->all();
        if ($request->hasfile('attachments')) {
            $image = null;
            $files = $request->file('attachments');
            foreach ($files as $file) {
                $filenameWithExt = $file->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
                $filename = preg_replace("/\s+/", '-', $filename);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $destinationPath = 'dist/attachments';
                $file->move($destinationPath, $fileNameToStore);
                $image[] = 'dist/attachments/' . $fileNameToStore;
            }
            $data['attachments'] = json_encode($image);
        }
        $data['user_id'] = auth()->id();
        $feedback = Feedback::create($data);
        $data = [
            "title" => 'New Feedback',
            "body" => auth()->user()->name . ' create Feedback !!!.'
        ];

        event(new \App\Events\NewFeedback($data));
        return $feedback;
    }

    public function edit($id)
    {
        return Feedback::findOrFail($id)->toArray();
    }

    public function update($request, $id)
    {
        $all = Feedback::find($id);
        $data = $request->all();
        if ($request->hasfile('attachments')) {
            $file = $request->file('attachments');
            $filenameWithExt = $file->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            $extension = $file->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $destinationPath = 'dist/attachments';
            $file->move($destinationPath, $fileNameToStore);
            $data['attachments'] = 'dist/attachments/' . $fileNameToStore;
        }
        $data['user_id'] = auth()->id();
        return $all->update($data);
    }

    public function destroy($id)
    {
        $all = Feedback::findOrFail($id);
        if ($all)
            $all->delete();
    }

    public function feedback()
    {
        return Feedback::with('like', 'dislike', 'comment_data.user')->paginate(10);


    }

    public function users()
    {
        $items = [];
        $users = User::all();
        foreach ($users as $item) {
            $items[] =
                [
                    'value' => $item->name,
                    'email' => $item->email,
                    'name' => $item->name
                ];
        }
        return $items;

    }

    public function like($id)
    {
        $vote = Voting::where('feedback_id', $id)->where('user_id', auth()->id())->first();
        if (!$vote) {
            $voting = new Voting();
            $voting->feedback_id = $id;
            $voting->vote = 1;
            $voting->user_id = auth()->id();
            $voting->save();
            $data = [
                "title" => 'New Like',
                "body" => auth()->user()->name . ' Like Feedback !!!.'
            ];

            event(new \App\Events\NewFeedback($data));
            return $voting;
        }
        return $vote;

    }

    public function dislike($id)
    {
        $vote = Voting::where('feedback_id', $id)->where('user_id', auth()->id())->first();
        if (!$vote) {
            $voting = new Voting();
            $voting->feedback_id = $id;
            $voting->vote = 2;
            $voting->user_id = auth()->id();
            $data = [
                "title" => 'New Dislike',
                "body" => auth()->user()->name . ' Dislike Feedback !!!.'
            ];

            event(new \App\Events\NewFeedback($data));
            $voting->save();
            return $voting;
        }

        return $vote;

    }

    public function comment($id, $request)
    {

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->feedback_id = $id;
        $comment->content = $request->content;
        $comment->date = date('Y-m-d H:i:s');
        $comment->save();
        $data = [
            "title" => 'New Comment',
            "body" => auth()->user()->name . ' Comment the Feedback !!!.'
        ];
        event(new \App\Events\NewFeedback($data));
        return $comment;
    }

}
