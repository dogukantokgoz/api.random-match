<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level',
        'experience_points',
        'likes',
        'avatar_icon',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function levelUp()
    {
        if ($this->experience_points >= 100) {
            $this->level += 1;
            $this->experience_points = $this->experience_points - 100;
            $this->save();
        }
    }

    public function addExperiencePoint()
    {
        $this->experience_points += 1;
        $this->save();
        $this->levelUp();
    }

    public function addLike()
    {
        $this->likes += 1;
        $this->save();
    }
}
