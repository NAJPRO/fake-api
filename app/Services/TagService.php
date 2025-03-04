<?php

namespace App\Services;

use App\Models\Tags;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TagService{
    public function create(array $data){
        return DB::transaction(function () use ($data){
            // Générer un slug si il n'as pas été fourni
            if(!isset($data['slug'])){
                $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
            }

            return Tags::create($data);
        });
    }


    public function update(array $data, Tags $tag){
        return DB::transaction(function () use ($data, $tag){
            // Générer un slug si il n'as pas été fourni
            if(isset($data['name']) && !isset($data['slug'])){
                $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
            }
            $tag->update($data);
            return $tag;
        });
    }

    public function delete(Tags $tag){
        return DB::transaction(function() use ($tag){
            return $tag->delete();
        });
    }
}
