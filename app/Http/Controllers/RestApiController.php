<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\AppUser;
use App\Notification;
use App\News;
use DateTime;
use App\NotificationHistory;
use App\Menu;
use App\NotificationStatus;

class RestApiController extends Controller
{
    //Group Management apis

    public $serverKey = "AAAAWroYpZY:APA91bFYza17TGcWw3nKVYtdDyOGkx1wFY4BWBGmMH8zw5ITjtO3xyOvXrDu9e979Ocgy--r-38iW0Gq6DvmSXT8Gfry9rRlx6dwPfrUF5z8x9SNF_GS09_JGCMlYF_IMYV0XxcOed1i";

    public function addGroup(Request $request)
    {
        $input = $request->all();
        $groupName = $input['groupName'];
        $privilege = $input['privilege'];
        $newGroup = new Group();
        $newGroup->name = $groupName;
        $newGroup->privilege = $privilege;
        if($newGroup->save())
            return \redirect('group_manage')->with('message','You add new group successfully.');
        else
            return \redirect('group_manage')->with('message','You are failed to add new group.');
    }

    public function sendNotification(Request $request)
    {
        $index = $request->all();
        $notifiContent = $index['notification'];
        $groupId = $index['groupId'];
        $title = $index['title'];
        $notification = new Notification();
        $notification->group_id = $groupId;
        $notification->notification_content = $notifiContent;
        $notification->date = new DateTime();
        $notification->is_sent = false;
        $notification->title = $title; 
        $notification->from_id = "-1";
        if($notification->save()) 
        {
            //save log
            $groupName = Group::where('id',$groupId)->first()->name;
            $notificationLog = new NotificationStatus();
            $currentTime = new DateTime();
            $notificationLog->message = "Admin sent notification (".$title.") to '".$groupName."' group at ".$currentTime->format('Y-m-d H:i:s');
            $notificationLog->save();
            //
            if($notification->group_id == -1)
            {
                $targets = array();
                $users = AppUser::all();
                foreach($users as $userItem)
                {
                    $this->recordNotification($userItem->id,$notification->id);
                    array_push($targets,$userItem->token);
                }
                if(count($targets) > 0)
                    $this->sendNotificationFCM($title,$targets);
                return  response()->json(['result' => 'success']);

            }else
            {
                $targets = array();
                $users = AppUser::where('group_id',$groupId)->get();
                foreach($users as $userItem)
                {
                    $this->recordNotification($userItem->id,$notification->id);
                    array_push($targets,$userItem->token);
                }
                if(count($targets) > 0)
                    $this->sendNotificationFCM($title,$targets);
                return  response()->json(['result' => 'success']);
            }
        }else {
            return  response()->json(['result' => 'failed']);
        }
    }

    public function recordNotification($userId,$notificationId)
    {
        $record = new NotificationHistory();
        $record->user_id = $userId;
        $record->notification_id = $notificationId;
        $record->save();
    }

    public function getMenuList(Request $request)
    {
        $input = $request->all();
        $user_id = $input['user_id'];
        $menuLst= Menu::where('user_id',$user_id)->get();
        return response()->json(['menu' => $menuLst]);
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
        if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
    }

    public function getNotificationContent(Request $request)
    {
        $index = $request->all();
        $id = $index['id'];
        $notification = Notification::where('id',$id)->first();
        return response()->json(['content' => $notification->notification_content]);
    }

    public function getNewsContent(Request $request)
    {
        $index = $request->all();
        $id = $index['id'];
        $news = News::where('id',$id)->first();
        return response()->json(['content' => $news->news_content]);
    }

    public function addNews(Request $request)
    {
        $index = $request->all();
        $newsContent = $index['news'];
        $title = $index['title'];
        $news = new News();
        $news->date = new DateTime();
        $news->news_content = $newsContent;
        $news->title = $title;
        if($news->save())
            return  response()->json(['result' => 'success']);
        else 
            return  response()->json(['result' => 'failed']);
    }

    public function deleteGroup($groupId)
    {
        $groupItem = Group::where('id',$groupId)->first();
        $usersReatedGroup = AppUser::where('group_id',$groupItem->id)->get();
        foreach($usersReatedGroup as $appUser)
        {
            try
            {
                $userId = $appUser->id;
                if($appUser->delete())
                {
                    $menus = Menu::where('user_id',$userId)->get();
                    foreach($menus as $menuItem)
                    {
                        $menuItem->delete();
                    }
                    $notificationLst = NotificationHistory::where('user_id',$userId)->get();
                    foreach($notificationLst as $notificationItem)
                    {
                        $notificationItem->delete();
                    }
                }
            }catch(\Exception $err)
            {

            }
        }
        $groupId = $groupItem->id;
        if($groupItem->delete())
        {
            $notificationRelatedGroup = Notification::where('group_id',$groupId)->get();
            foreach($notificationRelatedGroup as $notificationRelatedItem)
            {
                $notificationRelatedItem->delete();
            }
            return \redirect('group_manage')->with('message','A group is removed.');
        }
        else 
            return \redirect('group_manage')->with('message','Failed to remove group.');
    }

    public function groupImport(Request $request)
    {
        try
        {
            $request->validate([
                'fileToUpload' => 'required|file',
            ]);
            $request->fileToUpload->storeAs('temp','groupList.txt');
            $content = \Storage::get('temp/groupList.txt');
            $json = json_decode($content,true);
            foreach($json['contents'] as $groupJson)
            {
                $groupItem = new Group();
                $groupItem->name = $groupJson['name'];
                $groupItem->privilege = $groupJson['privilege'];
                $groupItem->save();
            }
            return \redirect('group_manage')->with('message','To import groups is successfully.');
        }catch(\Exception $err)
        {
            return \redirect('group_manage')->with('message','To import groups is failed.');
        }
    }

    public function userImport(Request $request)
    {
        try
        {
            $request->validate([
                'fileToUpload' => 'required|file',
            ]);
            $request->fileToUpload->storeAs('temp','userList.txt');
            $content = \Storage::get('temp/userList.txt');
            $json = json_decode($content,true);
            foreach($json['contents'] as $userJson)
            {
                $userItem = new AppUser();
                $userItem->name = $userJson['name'];
                $userItem->pwd = $userJson['password'];
                $group = Group::where('name',$userJson['group_name'])->first();
                if($group != null)
                {
                    $userItem->group_id = $group->id;
                    $userItem->save();
                }
            }
            return \redirect('user_manage')->with('message','To import users is successfully.');
        }catch(\Exception $err)
        {
            return \redirect('user_manage')->with('message','To import users is failed.');
        }
    }

    public function deleteNews($newsId)
    {
        $newsItem = News::where('id',$newsId)->first();
        if($newsItem->delete())
            return \redirect('news_history')->with('message','A news is removed.');
        else
            return \redirect('news_history')->with('message','Failed to remove a news');
    }

    public function editGroup(Request $request)
    {
        $input = $request->all();
        $groupId = $input['groupId'];
        $groupName = $input['groupName'];
        $groupPrivilege = $input['privilege'];
        if(Group::where('id',$groupId)->first()->update(['name' => $groupName,'privilege' => $groupPrivilege]))
            return \redirect('group_manage')->with('message','A group is updated.');
        else 
            return \redirect('group_manage')->with('message','Failed to update a group.');

    }
    //User management apis
    public function addUser(Request $request)
    {
        $input = $request->all();
        $userName = $input['name'];
        $userPwd = $input['pwd'];
        $groupId = $input['groupId'];
        $menuName = array();
        $menuLink = array();
        try
        {
            $menuName = $input['menu_name'];
            $menuLink = $input['menu_link'];
        }catch(\Exception $err)
        {

        }
        $isAllow = false;
        try
        {
            if($input['isAllow'] == 'on')
                $isAllow = true;
        }catch(\Exception $err)
        {

        }
        $appUser = new AppUser();
        $appUser->name = $userName;
        $appUser->pwd = $userPwd;
        $appUser->group_id = $groupId;
        $appUser->is_allow = $isAllow;
        if($appUser->save())
        {
            for($i = 0;$i < count($menuName);$i++)
            {
                $userId = $appUser->id;
                $menu = new Menu();
                $menu->user_id = $userId;
                $menu->menu_text = $menuName[$i];
                $menu->url = $menuLink[$i];
                $menu->save();
            }
            return \redirect('user_manage')->with('message','A User is added.');
        }
        else 
            return \redirect('user_manage')->with('message','Failed to add a user.');
    }

    public function editUser(Request $request)
    {
        $input = $request->all();
        $userId = $input['userId'];
        $userName = $input['name'];
        $userPwd = $input['pwd'];
        $groupId = $input['groupId'];
        $menuName = array();
        $menuLink = array();
        try
        {
            $menuName = $input['menu_name'];
            $menuLink = $input['menu_link'];
        }catch(\Exception $err)
        {

        }
        $isAllow = false;
        try
        {
            if($input['isAllow'] == 'on')
                $isAllow = true;
        }catch(\Exception $err)
        {

        }
        if(AppUser::where('id',$userId)->first()->update(['name' => $userName,'pwd' => $userPwd,'is_allow' => $isAllow,'group_id' => $groupId]))
        {
            $menuLst = Menu::where('user_id',$userId)->get();
            foreach($menuLst as $menuItem)
            {
                $menuItem->delete();
            }
            for($i = 0;$i < count($menuName);$i++)
            {
                $menu = new Menu();
                $menu->user_id = $userId;
                $menu->menu_text = $menuName[$i];
                $menu->url = $menuLink[$i];
                $menu->save();
            }
            return \redirect('user_manage')->with('message','A user is updated.');
        }
        else 
            return \redirect('user_manage')->with('message','Failed to update a user.');
    }

    public function deleteUser($userId)
    {
        $userItem = AppUser::where('id',$userId)->first();
        if($userItem->delete())
        {
            $userId = $userItem->id;
            $menus = Menu::where('user_id',$userId)->get();
            foreach($menus as $menuItem)
            {
                $menuItem->delete();
            }
            $notificationLst = NotificationHistory::where('user_id',$userId)->get();
            foreach($notificationLst as $notificationItem)
            {
                $notificationItem->delete();
            }

            return \redirect('user_manage')->with('message','A User is removed.');
        }
        else 
            return \redirect('user_manage')->with('message','Failed to remove User.');
    }

    public function clearLog(Request $request)
    {
        try
        {
            NotificationStatus::truncate();
        }catch(\Exception $err)
        {

        }
        return \redirect('notification_report');
    }

    //Mobile Api

    public function mLogin(Request $request)
    {
        $input = $request->all();
        $userName = $input['userName'];
        $pwd = $input['pwd'];
        $user =  AppUser::where('name',$userName)->where('pwd',$pwd)->get();
        if (count($user) > 0)
            if ($user[0]->is_allow)
                return \response()->json(['result' => 'success','allow' => 'true']);
            else 
                return \response()->json(['result' => 'success','allow' => 'false']);
        else 
            return \response()->json(['result' => 'fail','allow' => 'false']);
    }

    public function mGetNotification(Request $request)
    {
        $input = $request->all();
        $userName = $input['userName'];
        $pwd = $input['pwd'];
        $user = AppUser::where('name',$userName)->where('pwd',$pwd)->get();
        $group_id = $user[0]->group_id;
        $notificationLst = Notification::where('group_id',$group_id)->get();
        return \response()->json($notificationLst);
    }

    public function mGetNews (Request $request)
    {
        $input = $request->all();
        $userName = $input['userName'];
        $pwd = $input['pwd'];
        $newsLst = News::all();
        return \response()->json($newsLst);
    }

    public function mSendNofitication(Request $request)
    {
        $input = $request->all();
        $userName = $input['userName'];
        $pwd = $input['pwd'];
        $groupId = $input['groupId'];
        $title = $input['title'];
        $content = $input['content'];
        $notification = new Notification();
        $user = AppUser::where('name',$userName)->where('pwd',$pwd)->get();
        $notification->from_id = $user[0]->id;
        $notification->title = $title;
        $notification->notification_content = "<p>".$content."</p>";
        $notification->group_id = $groupId;
        $notification->date = new DateTime();
        $notification->is_sent = false;
        if($notification->save())
        {
            // save log
            $groupName = Group::where('id',$groupId)->first()->name;
            $notificationLog = new NotificationStatus();
            $currentTime = new DateTime();
            $notificationLog->message =  $userName." sent notification (".$title.") to '".$groupName."' group at ".$currentTime->format('Y-m-d H:i:s');
            $notificationLog->save();
            //
            $targets = array();
            $users = AppUser::where('group_id',$groupId)->get();
            foreach($users as $userItem)
            {
                $this->recordNotification($userItem->id,$notification->id);
                array_push($targets,$userItem->token);
            }
            if(count($targets) > 0)
                $this->sendNotificationFCM($title,$targets);
            return \response()->json(['result' => 'success']);
        }
        else
            return \response()->json(['result' => 'fail']);
    }

    public function mGroupNames(Request $request)
    {
        $input = $request->all();
        $userName = $input['userName'];
        $pwd = $input['pwd'];
        $groups = Group::all();
        return \response()->json($groups);
    }

    public function mSaveToken(Request $request)
    {
        $input = $request->all();
        $userName = $input['userName'];
        $pwd = $input['pwd'];
        $token = $input['token'];
        try
        {
            AppUser::where('name',$userName)->where('pwd',$pwd)->update(['token' => $token]);
            return \response()->json(['result' => 'success']);
        }catch(\Exception $err)
        {
            return \response()->json(['result' => 'fail']);
        }
    }

    public function mGetNotificationHistory(Request $request)
    {
        $input = $request->all();
        $userName = $input['userName'];
        $pwd = $input['pwd'];
        try
        {
            $user = AppUser::where('name',$userName)->where('pwd',$pwd)->get()[0];
            $records = NotificationHistory::where('user_id',$user->id)->get();
            $notificationLst = array();
            $recordIdLst = array();
            foreach($records as $recordItem)
            {
                try
                {
                    //save log
                    $notification = Notification::where('id',$recordItem->notification_id)->get()[0];
                    $notificationLog = new NotificationStatus();
                    $currentTime = new DateTime();
                    $notificationLog->message =  $userName." received notification (".$notification->title.")  at ".$currentTime->format('Y-m-d H:i:s');
                    $notificationLog->save();
                    //
                    array_push($notificationLst,$notification);
                    array_push($recordIdLst,$recordItem->id);
                }catch(\Exception $err)
                {

                }
            }
            return \response()->json(['result' => 'success','data' => $notificationLst,'recordIds' => $recordIdLst]);
        }catch(\Exception $err)
        {
            return \response()->json(['result' => 'fail']);
        }
    }

    public function mConfirmNotification(Request $request)
    {
        $input = $request->all();
        $userName = $input['userName'];
        $pwd = $input['pwd'];
        $recordId = $input['recordId'];
        try
        {
            $notificationHistory = NotificationHistory::where('id',$recordId)->get()[0];
            $notificationId = $notificationHistory->notification_id;
            $notification = Notification::where('id',$notificationId)->get()[0];
            $notification->update(['is_sent'=>'1']);
            $notificationHistory->delete();
            //save log
            $notificationLog = new NotificationStatus();
            $currentTime = new DateTime();
            $notificationLog->message =  $userName." confirm notification (".$notification->title.")  at ".$currentTime->format('Y-m-d H:i:s');
            $notificationLog->save();
            //
            return \response()->json(['result' => 'success']);
        }catch(\Exception $err)
        {
            return \response()->json(['result' => 'fail']);
        }
    }

    public function mUpdateGeolocation(Request $request)
    {
        $input = $request->all();
        $userName = $input['userName'];
        $pwd = $input['pwd'];
        $lng = $input['lon'];
        $lat = $input['lat'];
        $platform = $input['platform'];
        $model = $input['model'];
        $number = $input['number'];
        if ($lng == null)
            $lng = "0.0";
        if ($lat == null)
            $lat = "0.0";
        if ($platform == null)
            $platform = "";
        if ($number == null)
            $number = "";
        if ($model == null)
            $model = "";

        $currentTime = new DateTime();
        try
        {
            $user = AppUser::where('name',$userName)->where('pwd',$pwd)->get()[0];
            $user->update(['lat' => $lat,'lng' => $lng,'platform' => $platform,'model' => $model,'number' => $number,'position_update_time'=>$currentTime]);
            return \response()->json(['result' => 'success']);
        }catch(\Exception $err)
        {
            return \response()->json(['result' => 'fail']);
        }
    }

    public function mGetMenuList(Request $request)
    {
        $input = $request->all();
        $userName = $input['userName'];
        $pwd = $input['pwd'];
        try
        {
            $user = AppUser::where('name',$userName)->where('pwd',$pwd)->get()[0];
            $userId = $user->id;
            $menuLst = Menu::where('user_id',$userId)->get();
            return \response()->json(['result' => 'success','data'=>$menuLst]);
        }catch(\Exception $err)
        {
            return \response()->json(['result' => 'fail']);
        }
    }

    public function checkService1(Request $request)
    {
        //return \response()->json(['result' => 'success']);
        return \response()->json(['result' => 'fail']);
    }

}

