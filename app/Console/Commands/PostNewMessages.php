<?php

namespace App\Console\Commands;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class PostNewMessages extends Command
{
    protected $signature = 'message:send';

    protected $description = 'Post new reply messages to Asana\'s tasks';

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {

            if ($user->message) {

                $client = new Client([
                    'base_uri' => 'https://app.asana.com/api/1.0/',
                    'headers' => ['Authorization' => 'Bearer ' . $user->access_token]
                ]);

                $response = $client->get('workspaces/8231054812149/tasks', [
                    'query' => [
                        'assignee'        => $user->asana_id,
                        'completed_since' => 'now'
                    ]
                ]);

                $tasks = json_decode($response->getBody());

                $latestTaskId = $user->getLatestTask($tasks->data);

                if (!$user->latest_task_id) {
                    $user->latest_task_id = $latestTaskId;
                    $user->save();
                    continue;
                }

                $newTasks = $user->getNewTasks($tasks->data);

                if (!$newTasks) {
                    continue;
                }

                foreach ($newTasks as $newTask) {
                    $client->post('tasks/' . $newTask->id . '/stories', [
                        'form_params' => [
                            'text' => $user->message
                        ]
                    ]);
                }

                $user->latest_task_id = $latestTaskId;
                $user->save();

                Log::info('Message posted successfully',[
                    'user'    => $user->name,
                    'message' => $user->message,
                    'tasks'   => $newTasks
                ]);

            }

        }
    }
}