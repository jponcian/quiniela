<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cedula',
        'name',
        'email',
        'password',
        'whatsapp',
        'is_admin',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function championBets()
    {
        return $this->hasMany(ChampionBet::class);
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getBalanceAttribute()
    {
        $baseInscription = $this->predictions()->count() > 0 ? 10 : 0;
        $cost = $baseInscription + ($this->championBets()->count() * 5);
        return $cost - $this->total_paid;
    }

    public function getIsFullyPaidAttribute()
    {
        $baseInscription = $this->predictions()->count() > 0 ? 10 : 0;
        $cost = $baseInscription + ($this->championBets()->count() * 5);
        return $this->total_paid >= $cost;
    }
}
