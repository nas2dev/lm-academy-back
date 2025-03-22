<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Course;
use App\Models\UserInfo;
use App\Models\UserList;
use App\Models\Scoreboard;
use App\Models\CourseMaterial;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'email',
        'password',
        'image',
        'date_of_birth',
        'academic_year',
        'acc_status',
        'profile_completed'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

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

    public function scopeSelectSomeUserData($query) {
        return $query->select('users.id', 'first_name', 'last_name', 'gender', 'email', 'image');
    }

    public function scopeSelectUserName($query) {
        return $query->select('users.id', 'first_name', 'last_name');
    }

    public function UserInfo() {
        return $this->hasOne(UserInfo::class, 'user_id', 'id');
    }

    public function lists() {
        return $this->belongsToMany(UserList::class, 'user_list_items', 'user_id', 'list_id')->withTimestamps();
    }

    public function scoreOnScoreboard() {
        return $this->hasOne(Scoreboard::class, 'user_id');
    }

    //--------------------------Courses part -------------------------//

    public function createdCourses() {
        return $this->hasMany(Course::class, 'created_by');
    }

    public function updatedCourses() {
        return $this->hasMany(Course::class, 'updated_by');
    }

    public function createdCourseMaterials() {
        return $this->hasMany(CourseMaterial::class, 'created_by');
    }

    public function updatedCourseMaterials() {
        return $this->hasMany(CourseMaterial::class, 'updated_by');
    }
}
