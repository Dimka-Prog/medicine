<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\HasOne};
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\{DB, Hash};

class UsersModel extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;

    protected $table = 'Users';
    protected $connection = 'mysql';
    public $timestamps = true;
    protected $primaryKey = 'PassportNum';

    public function createUsers($passport, $email, $password): void
    {
        $this->getTable()->insert([
            'PassportNum' => intval($passport),
            'Email' => strval($email),
            'Password' => Hash::make(strval($password)),
            'created_at' => Carbon::now(),
            'updated_at' => NULL
        ]);
    }

    public function getTable(): Builder
    {
        return DB::table($this->table);
    }

    public function userInfo(): HasOne
    {
        return $this->hasOne(UsersInfoModel::class, 'PassportNum', 'PassportNum');
    }
}
