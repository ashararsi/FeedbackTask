<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\FeedbackServices;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(FeedbackServices $FeedbackServices)
    {
        $this->FeedbackServices = $FeedbackServices;

    }

    public function index()
    {
        $data = $this->FeedbackServices->getdata();
        return view('feedback.index', compact('data'));
    }

    public function getdata()
    {
        $data = $this->FeedbackServices->getdata();
        return $data;
    }  public function get_data_home()
    {
        $data['data']= $this->FeedbackServices->getdata();
        return json_encode($data);
    }

    public function like($id)
    {
        $data = $this->FeedbackServices->like($id);
        return $data;
    }

    public function dislike($id)
    {
        $data = $this->FeedbackServices->dislike($id);

    }    public function comment(Request  $request, $id)
    {
        $data = $this->FeedbackServices->comment($id,$request);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('feedback.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $this->FeedbackServices->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $feedback = $this->FeedbackServices->edit($id);
        return view('feedback.edit', compact('feedback'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        return $feedback = $this->FeedbackServices->update($request, $id);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->FeedbackServices->destroy($id);
    }
}
