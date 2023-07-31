<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\NotificationHistory;
use App\AppUser;
use App\Notification;

class CheckNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $groupedRecords = NotificationHistory::all()->groupBy('notification_id');
        foreach($groupedRecords as $group)
        {
            $targets = array();
            $title = "";
            if (count($group) > 0)
            {
                $notificationId = $group[0]->notification_id;
                $title = Notification::where('id',$notificationId)->get()[0]->title;
            }else
                continue;
            foreach($group as $record)
            {
                try
                {
                    $userId = $record->user_id;
                    $user =  AppUser::where('id',$userId)->get()[0];
                    $token = $user->token;
                    array_push($targets,$token);

                }catch(\Exception $err)
                {
                    
                }
            }
            $this->sendNotificationFCM($title,$targets);
        }
    }

    public function sendNotificationFCM($title,$target)
    {
        $json_data = [
            "registration_ids" => $target,
            "notification" => [
                "body" => $title,
                "title" => "Notification",
                "icon" => "icon"
            ],
            "data" => [
                "title" => "my title",
                "message"=> "my message",
                "image"=> "http://www.androiddeft.com/wp-content/uploads/2017/11/Shared-Preferences-in-Android.png",
                "action"=> "url",
                "action_destination"=> "http://androiddeft.com"
            ]
        ];
        $data = json_encode($json_data);
        //FCM API end-point
        $url = 'https://fcm.googleapis.com/fcm/send';
        //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
        $server_key = $this->serverKey;
        //header with content_type api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$server_key
        );
        //CURL request to route notification to FCM connection server (provided by Google)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if ($result === FALSE) 
        {
            die('Oops! FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
    }
}
