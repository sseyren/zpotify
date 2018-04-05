<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = ["artist", "album", "name", "coverExist"];

    public function scopeSearchByKeyword($query, $keyword)
    {
        if ($keyword!='') {
            $query->where(function ($query) use ($keyword) {
                $query->where("artist", "LIKE","%$keyword%")
                    ->orWhere("album", "LIKE", "%$keyword%")
                    ->orWhere("name", "LIKE", "%$keyword%");
            });
        }
        return $query;
    }

    public function scopeSearchByUser($query, $user){
        if ($user!='') {
            $query->where(function ($query) use ($user) {
                $query->where("uploader", $user);
            });
        }
        return $query;
    }
}
