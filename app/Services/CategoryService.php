<?php

namespace App\Services;

use App\Models\Categorie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CategoryService{
    public function create(array $data){
        return DB::transaction(function () use ($data){
            // Générer un slug si il n'as pas été fourni
            if(!isset($data['slug'])){
                $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
            }

            return Categorie::create($data);
        });
    }


    public function update(array $data, Categorie $categorie){
        return DB::transaction(function () use ($data, $categorie){
            // Générer un slug si il n'as pas été fourni
            if(isset($data['name']) && !isset($data['slug'])){
                $data['slug'] = Str::slug($data['name']) . '-' . uniqid();
            }
            $categorie->update($data);
            return $categorie;
        });
    }

    public function delete(Categorie $categorie){
        return DB::transaction(function() use ($categorie){
            return $categorie->delete();
        });
    }
}
