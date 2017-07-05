<?php

namespace App\Http\Controllers;

use App\Events\MemberOnline;
use App\Events\SentMessage;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $NameChannel = $request->NameChannel;
        $user =  Auth::user();
        $message = $user->messages()->create([
            'message' => $request->input('message'),
        ]);

       broadcast(new MemberOnline($user,$message,$NameChannel))->toOthers();


        return $request->message;
        //
    }


    public function SendMessage(Request $request)
    {
        $message = $request->message;
        $name = $request->name;
        $db = new Message();
        $db->user_id = Auth::user()->id;
        $db->message = $message;
        $db->save();
        $user = Auth::user();
        broadcast(new SentMessage($user, $message, $name))->toOthers();
        return $message;
    }

    public function getFormReceive($name,$NameChannel)
    {

        $data = ' 
                <li style="float: left;width: 266px;" >
           <div class="clearfix" style="height: auto;float: left; width: 276px; clear: both;content:\'-\';display: block;position: fixed;bottom: 2px;;padding: 5px;margin-left: 10px ">
      
        <div class="panel panel-default " style="">
            <div class="panel-heading">'.$name.'  <button id="btn-message-'.$NameChannel.'" style="float: right; margin-top: -5px;" class="btn btn-success" > X </button> </div>
            <div class="panel-body" id="message-content-'.$NameChannel.'" >
                <ul style="overflow: scroll;max-height: 250px" id="list-message-'.$NameChannel.'">
                    
                </ul>
            </div>
            <div class="panel-footer" style="height: 50px">
             
                <input type="text" style="width: 70%;float: left;margin-right: 10px" id="text-message-'.$NameChannel.'"  class="form-control" placeholder="Enter message">
                <input type="button"  style="float: left" onclick="sendmessage_private(\''.$NameChannel.'\');"  value="send" class="btn btn-danger">

            </div>
        </div>
    </div>
    </li>
    
    <script>
    $(\'#btn-message-'.$NameChannel.'\').click(function() {
      $(\'#message-content-'.$NameChannel.'\').toggle();
    });
    
    </script>
    
    
    
    
    ';

        return $data;

    }

    public function getFormsend($name,$NameChannel){
        $data = '
        <li style="float: left;width: 266px;" >
           <div class="clearfix" style="height: auto;float: left; width: 276px; clear: both;content:\'-\';display: block;position: fixed;bottom: 2px;;padding: 5px;margin-left: 10px ">
      
        <div class="panel panel-default " style="">
            <div class="panel-heading">'.$name.' <button id="btn-message-'.$NameChannel.'" style="float: right; margin-top: -5px;" class="btn btn-success" > X</button> </div>
            <div class="panel-body" id="message-content-'.$NameChannel.'" >
                <ul style="overflow: scroll;max-height: 250px" id="list-message-'.$NameChannel.'">
                    
                </ul>
            </div>
            <div class="panel-footer" style="height: 50px">
             
                <input type="text" style="width: 70%;float: left;margin-right: 10px" id="text-message-'.$NameChannel.'"  class="form-control" placeholder="Enter message">
                <input type="button"  style="float: left" onclick="sendmessage_private(\''.$NameChannel.'\');"  value="send" class="btn btn-danger">

            </div>
        </div>
    </div>
    </li>
    <script>
    $(\'#btn-message-'.$NameChannel.'\').click(function() {
      $(\'#message-content-'.$NameChannel.'\').toggle();
    });
    
    </script>
    
    ';
        return $data;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
