<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserService{
    public function create(array $data){
        return DB::transaction(function () use ($data){
            $data['password'] = Hash::make($data['password']);
            return user::create($data);
        });
    }

    public function update(array $data, User $user){
        return DB::transaction(function () use ($data, $user){
        
            return $user->update($data);
        });
    }

    public function delete(User $user){
        return DB::transaction(function() use ($user){
            return $user->delete();
        });
    }
}
