<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class UsersModel
 *
 * @property int $PassportNum
 * @property string $Email
 * @property string $Password
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class UsersModel extends Model
{
    use HasFactory, Authenticatable;

    protected $table = 'Users';
    public $timestamps = true;

    public function getTable(): Builder
    {
        return DB::table($this->table);
    }
}
