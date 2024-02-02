<?php

namespace App\Http\Controllers;

use App\Services\ProfileServices;
use App\Services\FeedbackServices;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\RealTimeNotification;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProfileServices $ProfileServices, FeedbackServices $FeedbackServices)
    {
        $this->middleware('auth');
        $this->ProfileServices = $ProfileServices;
        $this->FeedbackServices = $FeedbackServices;
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $user = auth()->user();

//        $user->notify(new RealTimeNotification('Hello World'));
        $feedback = $this->FeedbackServices->feedback();

        $users= $this->FeedbackServices->users();
        return view('home', compact('feedback','users'));
    }

    public function profile()
    {
        return view('profile');
    }

    public function profile_update(Request $request)
    {


        return $this->ProfileServices->update($request);

    }

    public function saveToken(Request $request)
    {
        $user = User::find(auth()->id());
        if (!$user->device_token) {
            auth()->user()->update(['device_token' => $request->token]);
            return response()->json(['token saved successfully.']);
        }
        return 0;


    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification(Request $request)
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();
        $SERVER_API_KEY = 'AAAAq-8nW7c:APA91bH5NVO-CZxytUeX1f8MWpJfot8Wr8Iuh6yJkNVrnXlKbR0WWw5T1qzvyOosn2etQbnbPnB3Vu8_cY5bPV5XMSqdvR800wawrwNoZPbDVeFnpyc5SSHLJeYeBafNAQC4mQCVOx5J';

        $data = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => 'test title',
                "body" => 'test body',
            ]
        ];
        $dataString = json_encode($data);

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);
    }


}
