<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $fillable = ['name', 'email', 'image_url', 'asana_id', 'access_token', 'message'];

    protected $hidden = ['access_token', 'remember_token'];

    public function getAccessTokenAttribute($value)
    {
        return Crypt::decrypt($value);
    }

    public function setAccessTokenAttribute($value)
    {
        $this->attributes['access_token'] = Crypt::encrypt($value);
    }

    public function getLatestTask(array $tasks)
    {
        $max = 0;

        foreach ($tasks as $task) {
            $max = ($task->id > $max) ? $task->id : $max;
        }

        return $max;
    }

    public function getNewTasks(array $tasks)
    {
        $min = $this->latest_task_id;

        $newTasks = array_filter($tasks, function($task) use ($min) {
            return ($task->id > $min);
        });

        return $newTasks;
    }
}