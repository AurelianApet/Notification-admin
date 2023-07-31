<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\AppUser;
use App\Notification;
use App\News;
use Auth;
use App\Bill;
use App\BdAsistencias;
use App\BillRecord;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BillRecordExportor;
use App\OmUserMaster;
use Illuminate\Support\Facades\DB;
use App\BdInstructore;
use DateTime;
use App\NotificationStatus;

class AdminController extends Controller
{
    //
    public $emby_api_key = "71ed21d735ef4778b3f224b10ee31b58";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        return view('admin.dashboard');
    }

    public function notificationPage(Request $request)
    {
        $groupListHtml = '';
        $groupList = Group::all();
        foreach($groupList as $itemGroup)
        {
            $groupListHtml = $groupListHtml."<option value=\"".$itemGroup->id."\">".$itemGroup->name."</option>";
        }
        $groupListHtml = $groupListHtml."<option value=\""."-1"."\">"."All Group"."</option>";
        return view('admin.notification_page',['group_list_html'=>$groupListHtml]);
    }

    public function newsPage(Request $request)
    {
        return view('admin.news_page');
    }

    public function notificationHistoryPage(Request $request)
    {
        $allNotification = Notification::all();
        $notificationHistoryHtml = "";
        foreach($allNotification as $itemNotification)
        {
            $isSentCircleColor = "";
            if($itemNotification->is_sent)
                $isSentCircleColor = "green";
            else 
                $isSentCircleColor = "red";
            $group = Group::where('id',$itemNotification->group_id)->first();
            if($group == null)
            {
                $itemNotification->delete();
                continue;
            }
            $groupName = $group->name;
            $notificationHistoryHtml = $notificationHistoryHtml."<tr>".
            "<td>".$itemNotification->id."</td>".
            "<td>".$itemNotification->title."</td>".
            "<td data=\"".$itemNotification->id."\">"."<a class=\"preview\" style=\"cursor:pointer\">Preview</a>"."</td>".
            "<td>".$itemNotification->date."</td>".
            "<td>".$groupName."</td>".
            "<td>"."<span class=\"dot\" style=\"background-color:".$isSentCircleColor.";\">"."</td>".
            "</tr>";
        }
        return view('admin.notification_history_page',['notification_list_html' => $notificationHistoryHtml]);
    }

    public function newsHitoryPage(Request $request)
    {
        $allNews = News::all();
        $newsHistoryHtml = "";
        foreach($allNews as $itemNews)
        {
            $newsHistoryHtml = $newsHistoryHtml."<tr>".
            "<td>".$itemNews->id."</td>".
            "<td>".$itemNews->title."</td>".
            "<td data=\"".$itemNews->id."\">"."<a class=\"preview\" style=\"cursor:pointer\">Preview</a>"."</td>".
            "<td>".$itemNews->date."</td>".
            "<td style=\"text-align:  center;\">"."<a class=\"btn btn-danger btn-xs\" href=\"news_list/delete/".$itemNews->id."\">Remove</a>"."</td>";
        }
        return view('admin.news_history_page',['news_list_html' => $newsHistoryHtml]);
    }   

    public function successPage(Request $request)
    {
        return view('admin.success');
    }

    public function userPage(Request $request)
    {
        $userList = AppUser::all();
        $userListHtml = '';
        foreach($userList as $itemUser)
        {
            $groupName = Group::where('id',$itemUser->group_id)->first()->name;
            $allowedUser = "";
            if($itemUser->is_allow)
                $allowedUser = '<label><input type="checkbox" disabled="disabled" class="js-switch" checked /></label>';
            else
                $allowedUser = '<label><input type="checkbox" disabled="disabled" class="js-switch" /></label>';
            $userListHtml = $userListHtml."<tr>".
                "<td>".$itemUser->id."</td>".
                "<td>".$itemUser->name."</td>".
                "<td>".$itemUser->pwd."</td>".
                "<td data=\"".$itemUser->group_id."\">".$groupName."</td>".
                "<td>".$itemUser->lat."</td>".
                "<td>".$itemUser->lng."</td>".
                "<td>".$allowedUser."</td>".
                "<td style=\"text-align:  center;\">"."<a class=\"btn btn-info editBtn btn-xs\">Edit</a>"."</td>".
                "<td style=\"text-align:  center;\">"."<a class=\"btn btn-danger btn-xs\" href=\"user_list/delete/".$itemUser->id."\">Remove</a>"."</td>";
        }
        $groupListHtml = '';
        $groupList = Group::all();
        foreach($groupList as $itemGroup)
        {
            $groupListHtml = $groupListHtml."<option value=\"".$itemGroup->id."\">".$itemGroup->name."</option>";
        }
        return view('admin.user_page',['user_list_html'=>$userListHtml,'group_list_html'=>$groupListHtml]);
    }

    public function groupPage(Request $request)
    {
        $groupList = Group::all();
        $groupListHtml = '';
        foreach($groupList as $itemGroup)
        {
            $groupId = $itemGroup->id;
            $countUser = count(AppUser::where('group_id',$groupId)->get());
            $groupListHtml = $groupListHtml."<tr>".
                "<td>".$itemGroup->id."</td>".
                "<td>".$itemGroup->name."</td>".
                "<td>".$itemGroup->privilege."</td>".
                "<td>".$countUser."</td>".
                "<td style=\"text-align:  center;\">"."<a class=\"btn btn-info editBtn btn-xs\">Edit</a>"."</td>".
                "<td style=\"text-align:  center;\">"."<a class=\"btn btn-danger btn-xs\" href=\"group_list/delete/".$itemGroup->id."\">Remove</a>"."</td>";
        }
        return view('admin.group_page',['group_list_html'=>$groupListHtml]);
    }

    //Billing system
    public function downloadExcel()
    {
        return (new BillRecordExportor())->download('test.csv',\Maatwebsite\Excel\Excel::CSV);
    }

    public function emby_activate($user_id,$activate)
    {
        try
        {
            $client = new \GuzzleHttp\Client(['headers' => ['Content-Type' => 'application/json']]);
            $res = $client->get("http://mg.movimientognostico.org:8096/Users?api_key=71ed21d735ef4778b3f224b10ee31b58");
            $body = $res->getBody();
            $users_json = \json_decode($body);
            $emby_user;
            $asistencias = BdAsistencias::where('id_asistencia',$user_id)->first();
            if($asistencias != null)
            {
                $instructore = BdInstructore::where('id_instructor',$asistencias->id_instructor)->first();
                if($instructore != null)
                {
                    $emby_user_name = $instructore->usuario_trascendental;
                    for($i = 0;$i< $users_json;$i++)
                    {
                        if($users_json[$i]->Name == $emby_user_name)
                        {
                            $emby_user = $users_json[$i];
                            $emby_user->Policy->IsDisabled = $activate;
                            $update_json_content = json_encode($emby_user->Policy);
                            $headers = [
                                'Content-type' => 'application/json; charset=utf-8'
                            ];
                            $request = $client->post("http://mg.movimientognostico.org:8096/Users/".$emby_user->Id."/Policy?api_key=71ed21d735ef4778b3f224b10ee31b58",['body' => $update_json_content]);
                            if($request->getStatusCode() == 204)
                                return true;
                            else
                                return false;
                        }
                    }
                }
            }
        }catch(\Exception $err)
        {
            return false;
        }
    }

    public function OutputActivate($name,$activate)
    {
        $result = OmUserMaster::where("login_name",$name)->first();
        DB::table('om_user_master')->where('user_id',$result->user_id)->update(['active'=>$activate]);
    }

    public function add_payment(Request $request)
    {
        $all_users = BdAsistencias::all();
        $user_list = array();
        $index = 0;
        foreach($all_users as $user)
        {
            array_push($user_list,[$user->id_asistencia,$user->nombre]);
        }
        return view('admin.add_payment',['user_list' => $user_list]);
    }

    public function add_payment_p(Request $request)
    {
        $input = $request->all();
        $bill = Bill::where('user_id',$input['user_id'])->first();
        $request->validate([
            'media_attache_file' => 'file',
            'output_attache_file' => 'file',
        ]);
        $output_username = $input['output_name_'];
        $media_file_name = '';
        $output_file_name = '';
        if($request->media_attache_file != null)
        {
            $media_file_name = \Hash::make($request->media_attache_file->getClientOriginalName().date('Y-m-d H i s'));
            $media_file_name = str_replace("/","",$media_file_name);
            $request->media_attache_file->storeAs("/attach/",$media_file_name.'.jpg');
        }
        if($request->output_attache_file != null)
        {
            $output_file_name = \Hash::make($request->output_attache_file->getClientOriginalName().date('Y-m-d H i s'));
            $output_file_name = str_replace("/","",$output_file_name);
            $request->output_attache_file->storeAs("/attach/",$output_file_name.'.jpg');
        }
        if($bill ==  null)
        {            
            $bill = new Bill();
            $bill->user_id = $input['user_id'];
            $bill->media_balance = $input['media_balance'];
            $bill->output_user_name = $output_username;
            if($input['media_expired'] == null)
                $bill->media_expired = null;
            else
                $bill->media_expired = date('Y-m-d',strtotime($input['media_expired']));
            $bill->output_balance = $input['output_balance'];
            if($input['output_expired'] == null)
                $bill->output_expired = null;
            else
                $bill->output_expired = date('Y-m-d',strtotime($input['output_expired']));
            if($bill->media_balance <= 0)
            {
                if($this->emby_activate($bill->user_id,false))
                    $bill->media_activate = false;
            }
            else
            {
                if($this->emby_activate($bill->user_id,true))
                    $bill->media_activate = true;
            }
            if($bill->output_balance <= 0)
            {
                $bill->output_activate = false;
                $this->OutputActivate($bill->output_user_name,0);
            }
            else 
            {
                $bill->output_activate = true;
                $this->OutputActivate($bill->output_user_name,1);
            }
            $bill->save();
        }else
        {
            if($input['media_expired'] == null)
                $bill->media_expired = null;
            else
                $bill->media_expired = date('Y-m-d',strtotime($input['media_expired']));
            if($input['output_expired'] == null)
                $bill->output_expired = null;
            else
                $bill->output_expired = date('Y-m-d',strtotime($input['output_expired']));
            $bill->media_balance = $bill->media_balance + $input['media_balance'];
            $bill->output_balance = $bill->output_balance + $input['output_balance'];
            if($bill->media_balance <= 0)
            {
                if($this->emby_activate($bill->user_id,false))
                    $bill->media_activate = false;  
            }
            else 
            {
                if($this->emby_activate($bill->user_id,true))
                    $bill->media_activate = true;   
            }
            if($bill->output_balance <= 0)
            {
                $bill->output_activate = false;
                $this->OutputActivate($bill->output_user_name,0);
            }            
            else
            {
                $bill->output_activate = true;
                $this->OutputActivate($bill->output_user_name,1);
            }
            $bill->update();
        }
        $bill_record = new BillRecord();
        $bill_record->user_id = $input['user_id'];
        $user_name = BdAsistencias::where("id_asistencia",$input['user_id'])->first()->nombre;
        $bill_record->description = "";
        $bill_record->media_attach = $media_file_name;
        $bill_record->output_attach = $output_file_name;
        $bill_record->media_balance = $input['media_balance'];
        $bill_record->media_expired = date('Y-m-d',strtotime($input['media_expired']));
        $bill_record->output_balance = $input['output_balance'];
        $bill_record->output_expired = date('Y-m-d',strtotime($input['output_expired']));
        $bill_record->save();
        return redirect('/add_payment');
    }

    public function billing_table(Request $request)
    {
        $billing_table_html = '';
        $bills = Bill::all();
        foreach($bills as $bill)
        {
            $media_activate = "";
            $output_activate = "";
            if($bill->media_activate)
                $media_activate = '<label><input type="checkbox" disabled="disabled" class="js-switch" checked /></label>';
            else
                $media_activate = '<label><input type="checkbox" disabled="disabled" class="js-switch" /></label>';
            if($bill->output_activate)
                $output_activate = '<label><input type="checkbox" disabled="disabled" class="js-switch" checked /></label>';
            else
                $output_activate = '<label><input type="checkbox" disabled="disabled" class="js-switch" /></label>';
            $user_name = BdAsistencias::where('id_asistencia',$bill->user_id)->first()->nombre;
            $billing_table_html = $billing_table_html."<tr><td>".$bill->user_id
                ."</td><td>".$user_name
                ."</td><td>".$bill->media_balance
                ."</td><td>".$bill->media_expired
                ."</td><td>".$media_activate
                ."</td><td>".$bill->output_balance
                ."</td><td>".$bill->output_expired
                ."</td><td>".$output_activate
                ."</td><td>".$bill->output_user_name
                ."</td><td style=\"text-align:  center;\">"."<a class=\"btn btn-info btn-xs\" href=\"user/bill/edit/".$bill->user_id."\">Edit</a>"
                ."</td><td style=\"text-align:  center;\">"."<a class=\"btn btn-danger btn-xs\" href=\"user/bill/delete/".$bill->user_id."\">Delete</a>"
                ."</td></tr>";
        }
        return view('admin.billing_table',['billing_table_html' => $billing_table_html]);
    }

    public function delete_bill($id,Request $request)
    {
        $bill = Bill::where('user_id',$id)->first();
        $bill->delete();
        return redirect('/billing_table');
    }

    public function edit_bill($id,Request $request)
    {
        $bill = Bill::where('user_id',$id)->first();
        $user_name = BdAsistencias::where('id_asistencia',$bill->user_id)->first()->nombre;
        $media_format = date("m/d/Y",strtotime($bill->media_expired));
        if($bill->media_expired == null)
            $media_format = date("m/d/Y");
        $output_format = date("m/d/Y",strtotime($bill->output_expired));
        if($bill->output_expired == null)
            $output_format = date("m/d/Y");
        if($bill->media_activate)
            $media_service_activate = "checked";
        else
            $media_service_activate = "";
        if($bill->output_activate)
            $output_service_activate = "checked";
        else 
            $output_service_activate = "";
        return view('admin.billing_edit',['user_id' => $bill->user_id,'user_name' => $user_name,'media_balance' => $bill->media_balance,'output_balance' => $bill->output_balance,
        'media_expired' => $media_format, 'output_expired' => $output_format, 'media_service_activate' => $media_service_activate,'output_service_activate' => $output_service_activate]);
    }

    public function billing_record_table(Request $request)
    {
        $bill_records = BillRecord::all();
        $billing_record_table_html = '';
        foreach($bill_records as $bill_record)
        {
            $name = BdAsistencias::where('id_asistencia',$bill_record->user_id)->first()->nombre;
            $media_expired = "";
            $output_expired = "";
            $description = "";
            if($bill_record->media_expired != "1970-01-01")
            {
                $media_expired = $bill_record->media_expired;
                $description = $description.'Embey:'.$bill_record->media_balance.'$'.' and '.' to '.$media_expired.'  ';
            }
            if($bill_record->output_expired != "1970-01-01")
            {
                $output_expired = $bill_record->output_expired;
                $description = $description.'Output:'.$bill_record->output_balance.'$'.' and '.' to '.$output_expired.'.';
            }
            $created_time = $bill_record->created_at;
            $media_attach = "<img class=\"myImg\" src=\"storage\attach\/".$bill_record->media_attach.'.jpg'."\" alt=\"...\" class=\"img-circle profile_img\" style=\"width: 50px;height: 50px;\"></img>";
            $output_attach = "<img class=\"myImg\" src=\"storage\attach\/".$bill_record->output_attach.'.jpg'."\" alt=\"...\" class=\"img-circle profile_img\" style=\"width: 50px;height: 50px;\"></img>";
            if($bill_record->media_attach == null || $bill_record->media_attach == "")
                $media_attach = "";
            if($bill_record->output_attach == null || $bill_record->output_attach == "")
                $output_attach = "";
            $billing_record_table_html = $billing_record_table_html.'<tr><td>'.$bill_record->id.'</td><td>'.$name.'</td><td>'.$description."</td><td>".$media_attach."</td><td>".$output_attach."</td><td>".date($bill_record->created_at).'</td></tr>';
        }
        return view('admin.billing_record_table',['billing_record_table_html' => $billing_record_table_html]);
    }

    public function edit_bill_p($id,Request $request)
    {
        $input = $request->all();
        $bill = Bill::where('user_id',$id)->first();
        $bill->media_balance = $input['media_balance'];
        $bill->media_expired = date('Y-m-d',strtotime($input['media_expired']));
        $bill->output_balance = $input['output_balance'];
        $bill->output_expired = date('Y-m-d',strtotime($input['output_expired']));
        try
        {
            if($input['output_service_activate'] == "on")
            {
                $bill->output_activate = true;
                $this->OutputActivate($bill->output_user_name,1);
            }
        }catch(\Exception $err){
            $bill->output_activate = false;
            $this->OutputActivate($bill->output_user_name,0);
        }
        try
        {
            if($input['media_service_activate'] == "on")
            {
                if($this->emby_activate($bill->user_id,true))
                    $bill->media_activate = true;
            }
        }catch(\Exception $err){
            if($this->emby_activate($bill->user_id,false))
                $bill->media_activate = false;
        }
        $bill->update();
        return redirect('/billing_table');

    }

    public function billing_record_filter(Request $request)
    {
        $all_users = BdAsistencias::all();
        $user_list = array();
        foreach($all_users as $user)
            array_push($user_list,[$user->id_asistencia,$user->nombre]);
        return view('admin.billing_record_filter',['user_list'=>$user_list]);
    }

    public function check_output_name(Request $request)
    {
        $input = $request->all();
        $name = $input['name'];
        $db =  new OmUserMaster;
        $db->setTable("om_user_master");
        $result = $db->where("login_name",$name)->get();
        $exist = 0;
        if($result->count() > 0)
        {
            $exist = 1;
        }
        return response()->json(['exist'=>$exist]);
        
    }

    public function search_p(Request $request)
    {
        $input = $request->all();
        $user_id = $input['user_id'][0];
        $start_date = strtotime($input['start_date']);
        $end_date = strtotime($input['end_date']);
        $chk_emby = false;
        try
        {
            if($input['chk_emby'] == "true")
                $chk_emby = true;
        }catch(\Exception $err)
        {

        }
        $chk_output = false;
        try
        {
            if($input['chk_output'] == "true")
                $chk_output = true;
        }catch(\Exception $err)
        {

        }
        $bill_records = null;
        if(!$user_id)
            $bill_records = BillRecord::all();
        else
            $bill_records = BillRecord::where('user_id',$user_id)->get();
        $billing_record_table_html = "";
        $total_emby = 0;
        $total_output = 0;
        foreach($bill_records as $bill_record)
        {
            if($chk_emby && $chk_output)
            {

            }else
            {
                if($chk_emby)
                {
                    if($bill_record->media_balance == null)
                        continue;
                }
                if($chk_output)
                {
                    if($bill_record->output_balance == null)
                        continue;
                }
            }
            $create_time = strtotime($bill_record->created_at);
            if(!$start_date)
                $start_date = 0;
            if(!$end_date)
                $end_date = 9560047295;

            if($start_date <= $create_time && $end_date >= $create_time)
            {
                $name = BdAsistencias::where('id_asistencia',$bill_record->user_id)->first()->nombre;
                $media_expired = "";
                $output_expired = "";
                if($bill_record->media_expired != "1970-01-01")
                {
                    $media_expired = $bill_record->media_expired;
                }
                if($bill_record->output_expired != "1970-01-01")
                {
                    $output_expired = $bill_record->output_expired;
                }
                $description = "";
                if($bill_record->media_expired != "1970-01-01")
                {
                    $media_expired = $bill_record->media_expired;
                    $description = $description.'Embey:'.$bill_record->media_balance.'$'.' and '.' to '.$media_expired.'  ';
                }
                if($bill_record->output_expired != "1970-01-01")
                {
                    $output_expired = $bill_record->output_expired;
                    $description = $description.'Output:'.$bill_record->output_balance.'$'.' and '.' to '.$output_expired.'.';
                }
                $media_attach = "<img class=\"myImg\" src=\"storage\attach\/".$bill_record->media_attach.'.jpg'."\" alt=\"...\" class=\"img-circle profile_img\" style=\"width: 50px;height: 50px;\"></img>";
                $output_attach = "<img class=\"myImg\" src=\"storage\attach\/".$bill_record->output_attach.'.jpg'."\" alt=\"...\" class=\"img-circle profile_img\" style=\"width: 50px;height: 50px;\"></img>";
                if($bill_record->media_attach == null || $bill_record->media_attach == "")
                    $media_attach = "";
                if($bill_record->output_attach == null || $bill_record->output_attach == "")
                    $output_attach = "";
                $billing_record_table_html = $billing_record_table_html.'<tr><td>'.$bill_record->id.'</td><td>'.$name.'</td><td>'.$description."</td><td>".$media_attach."</td><td>".$output_attach."</td><td>".date($bill_record->created_at).'</td></tr>';
                $total_emby = $total_emby + $bill_record->media_balance;
                $total_output = $total_output + $bill_record->output_balance;
            }
        }
        return response()->json(['search_result' => $billing_record_table_html,'total_media' => $total_emby,'total_output' => $total_output]);
    }

    public function onLineReportPage(Request $request)
    {
        $appUsers = AppUser::all();
        $currentTime = new DateTime();
        $user_list_html = "";
        foreach($appUsers as $user)
        {
            try
            {
                $updatedTime = new DateTime($user->position_update_time);
                $differSeconds = $currentTime->getTimestamp() - $updatedTime->getTimestamp();
                if($differSeconds < 100)
                {
                    $groupName = Group::where("id",$user->group_id)->first()->name;
                    $user_list_html = $user_list_html."<tr>".
                    "<td>".$user->id."</td>".
                    "<td>".$user->name."</td>".
                    "<td>".$user->platform."</td>".
                    "<td>".$user->model."</td>".
                    "<td>".$user->number."</td>".
                    "<td>".$groupName."</td>".
                    "</tr>"
                    ;
                }
            }
            catch(\Exception $err)
            {

            }
        }   
        if($user_list_html !== "")
            return view('admin.online_report',['user_list_html' => $user_list_html]);
        else 
            return view('admin.online_report',['user_list_html' => $user_list_html])->with('message','There isn\' any online users.');
    }

    public function offLineReportPage(Request $request)
    {
        $appUsers = AppUser::all();
        $currentTime = new DateTime();
        $user_list_html = "";
        foreach($appUsers as $user)
        {
            try
            {
                $updatedTime = new DateTime($user->position_update_time);
                $differSeconds = $currentTime->getTimestamp() - $updatedTime->getTimestamp();
                if($differSeconds > 100)
                {
                    $groupName = Group::where("id",$user->group_id)->first()->name;
                    $user_list_html = $user_list_html."<tr>".
                    "<td>".$user->id."</td>".
                    "<td>".$user->name."</td>".
                    "<td>".$user->platform."</td>".
                    "<td>".$user->model."</td>".
                    "<td>".$user->number."</td>".
                    "<td>".$groupName."</td>".
                    "</tr>";
                }
            }
            catch(\Exception $err)
            {

            }
        }   
        if($user_list_html !== "")
            return view('admin.offline_report',['user_list_html' => $user_list_html]);
        else 
            return view('admin.offline_report',['user_list_html' => $user_list_html])->with('message','There isn\' any offline users.');
    }

    public function notificationReportPage(Request $request)
    {
        $notificationReports = NotificationStatus::all();
        $report_list_html  = "";
        foreach($notificationReports as $notificationItem)
        {
            $report_list_html = $report_list_html."<tr>".
            "<td>".$notificationItem->id."</td>".
            "<td>".$notificationItem->message."</td>".
            "</tr>";
        }
        if($report_list_html !== "")
            return view('admin.notification_report',['report_list_html' => $report_list_html]);
        else 
            return view('admin.notification_report',['report_list_html' => $report_list_html])->with('message','There isn\' any offline users.');
    }

}
