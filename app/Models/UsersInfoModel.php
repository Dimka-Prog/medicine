<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model, Relations\BelongsTo};
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class UsersInfoModel extends Model
{
    use HasFactory;

    protected $table = 'UsersInfo';
    protected $connection = 'mysql';
    public $timestamps = true;

    public function createUsersInfo($FIO, $passport, $diploma, $organization, $specialization): void
    {
        $this->getTable()->insert([
            'FIO' => strval($FIO),
            'PassportNum' => intval($passport),
            'DiplomaNum' => intval($diploma),
            'OrganizationName' => $organization ?? strval($organization),
            'Specialization' => $specialization ?? strval($specialization),
            'created_at' => Carbon::now(),
            'updated_at' => NULL
        ]);
    }

    public function getTable(): string|Builder
    {
        return DB::table($this->table);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(UsersModel::class, 'PassportNum', 'PassportNum');
    }
}
