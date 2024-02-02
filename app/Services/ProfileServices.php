<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;


class ProfileServices
{
    public function update($request)
    {
        $data = $request->all();  unset($data['confirm_password']);
        $id = auth()->id();
        $user = User::find($id);
        if($request->password){
            $data['password'] = Hash::make($request->password);
        }else{
            unset($data['password']);
        }
        $user->update($data);
    }

}
