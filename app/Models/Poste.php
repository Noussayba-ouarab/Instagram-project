<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    use HasFactory;
    protected $table = '_postes';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function commentaires()
    {
        return $this->hasMany(Commentaire::class,'post_id','id');
    }
    public function like()
    {
        return $this->hasMany(Like::class,'post_id','id');
    }
    public function isLikedByUser($userId)
{
    return $this->like()->where('user_id', $userId)->exists();
}
public function usersWhoLiked()
    {
        return $this->belongsToMany(User::class, 'like');
    }
}
