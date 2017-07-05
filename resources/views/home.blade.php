@extends('layouts.layoutChat')

@section('contentChat')
    <script src="https://unpkg.com/vue"></script>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div id="post_up" >

                </div>
                <div id="status"></div>
                <br>
                <div id="status2"></div>
                <div class="panel panel-default">


                    <div class="app">


                    </div>

                    <div class="panel-heading">Dashboard</div>


                    <div class="panel-body ">

                        {{--<ul id="list-message" style="max-height: 300px;overflow: scroll;">--}}


                        {{--</ul>--}}

                        <div class="form-group ">
                            <input class="form-control" type="text" id="message" name="message"
                                   placeholder="Enter message....">

                        </div>
                        <div class="form-group">
                            <input class="btn btn-success" type="button" id="submit" value="Send">
                        </div>


                    </div>
                </div>

                <input class="btn btn-danger" type="button" onclick="Member_Info()" value="Member Info">
            </div>
        </div>


    </div>

    <script src="https://js.pusher.com/4.0/pusher.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        Notification.requestPermission();

        //Private Chat
        let pusher_private = new Pusher('0ce6f92c19a5983cbb05', {
            authEndpoint: 'broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            },
            cluster: 'ap1',
            encrypted: true
        });




    </script>
    <script>

    </script>




    <div  style="position: fixed;float:left;z-index: 10;width: 100%">
        <ul id="chat-message" >

        </ul>
    </div>



    <script>
            //Chat private-channel
            function sendmessage_private(NameChannel) {
                var message = $('#text-message-'+NameChannel).val();
                $('#text-message-'+NameChannel).val('');

            $.post('sendmessage', {
                '_token': $('meta[name=csrf-token]').attr('content'),
              //  task: 'comment_insert',
                message: message,

                name:NameChannel
            }, function (data, status) {

                $('#list-message-'+NameChannel).append(' <li class="alert alert-danger small"> '+data+' </li>').scrollTop(2000);
                console.log("Data: " + data + "\nStatus: " + status);
            });
            }


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Send event presence
       function sendEventPresence(message,NameChannel,checkChannel){
           //Check form chat exist!
           if($('#message-content-'+NameChannel).attr('class')==null&&$('#message-content-'+checkChannel).attr('class')==null&&message!='{{auth::user()->name}}'){
           $.post('message', {
               '_token': $('meta[name=csrf-token]').attr('content'),
               NameChannel: NameChannel,
               message: message
           }, function (data, status) {
               console.log("Data: " + data + "\nStatus: " + status);
           });




                 $.get('FormSend/'+message+'/'+NameChannel,function(data){
                     $('#chat-message').append(data);

                     //  this.Channel_Private = NameChannel;
                     var PrivateChannel = pusher_private.subscribe('private-chat.'+NameChannel);
                     PrivateChannel.bind("App\\Events\\SentMessage", function (data) {

                         if(data.user.name!='{{auth::user()->name}}'){

                             $('#list-message-'+NameChannel).append(' <li class="alert alert-info small"> '+data.message+' </li>').scrollTop(2000);
                         }


                     });

                     //document.getElementById('chat-message').innerHTML = data;

                     console.log(data);
                 });

             }



       }

        //pusher-presence
            let pusher=null;
       function CreatePresence(){
           Pusher.logToConsole = true;
           pusher = new Pusher('0ce6f92c19a5983cbb05', {
               authEndpoint: 'broadcasting/auth',
               auth: {
                   headers: {
                       'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                   }
               },
               cluster: 'ap1',
               encrypted: true

           });
       }

        //Status connect
       function StatusConnect(){
           pusher.connection.bind('connected', function () {
               $('div#status').text('Realtime is go!');
               setTimeout(4000);
               Member_Info();

           });
           pusher.connection.bind('failed', function () {
               Member_Info();
               $('div#status').text('failed,Error connect!')
           });
           pusher.connection.bind('unavailable', function () {
               Member_Info();
               $('div#status').append('<label  class="alert alert-danger"> Error! Check connect internet!!!</label>');
           });

           pusher.connection.bind('disconnected', function () {
               Member_Info();
               $('div#status').text('Disconnected!Error connect!')
           });
           pusher.connection.bind('initialized', function () {
               Member_Info();
               $('div#status').text('Initialized,Error connect!')
           });
           pusher.connection.bind('connecting_in', function (delay) {
               Member_Info();
               $('div#status').text("I haven't been able to establish a connection for this feature.  " +
                   "I will try again in " + delay + " seconds.");
           });
           pusher.connection.bind('state_change', function (states) {
               // states = {previous: 'oldState', current: 'newState'}
               Member_Info();
               $('div#status').text("Pusher's current state is " + states.current);
           });
       }



      //Received message from presence channel
            let channel = null;
         function getNoticationPresence(){
             channel = pusher.subscribe('presence-memberOnline.1');
             channel.bind('App\\Events\\MemberOnline', function (data) {
                 if ('{{auth::user()->name}}' == data.message.message) {
                     var NameChannel = data.NameChannel;
                     //  this.Channel_Private = NameChannel;
                     var PrivateChannel = pusher_private.subscribe('private-chat.'+NameChannel);
                     PrivateChannel.bind("App\\Events\\SentMessage", function (data) {

                         if(data.user.name!='{{auth::user()->name}}'){
                             new Notification('Tin nhắn mới từ '+data.user.name,
                                 {
                                     body: data.message, // Nội dung thông báo
                                     icon: 'http://www.freeiconspng.com/uploads/message-icon-png-14.png'// Hình ảnh
                                     // tag: 'https://freetuts.net/' // Đường dẫn
                                 }
                             );
                             $('#list-message-'+NameChannel).append(' <li class="alert alert-info small"> '+data.message+' </li>');
                         }


                     });
                     //Check form chat exist!
                     if($('#message-content-'+NameChannel).attr('class')==null) {


                         $.get('FormReceive/' + data.user.name + '/' + NameChannel, function (data) {
                             $('#chat-message').append(data);

                             //   document.getElementById('chat-message').innerHTML = data;
                         });
                     }

                 }
             });

         }

        //PRESENCE CHANNEL
        function Member_Info() {
            var count = channel.members.count;
            //  $('div#status').innerHTML="<button class=\"btn btn-success\"   >Member online:"+count+" </button>";

            document.getElementById('status').innerHTML = "<button class=\"btn btn-success\"  >Member online:" + count + " </button>";
            var infor = "";
            channel.members.each(function (member) {
                var userInfo = member.info;
                infor += "<button class=\"btn btn-success\" id=\"chat\"   onclick=\"sendEventPresence('"+userInfo.name+"','"+userInfo.name+"-{{auth::user()->name}}','{{auth::user()->name}}-"+userInfo.name+"');\"  >" + userInfo.name + " </button>";
            });
            // alert(infor);
            document.getElementById('status2').innerHTML = infor;
        }


        function _Notication() {
            channel.bind('pusher:member_added', function (member) {
                new Notification('Thông báo từ laravel',
                    {
                        body: 'Member:'+member.info.name+' online!!', // Nội dung thông báo
                        icon: 'http://iconizer.net/files/Simplicio/orig/notification_warning.png'// Hình ảnh
                       // tag: 'https://freetuts.net/' // Đường dẫn
                    }
                );
               // $('div#post_up').append('<label  class="alert alert-danger">'+member.info.name+' online! </label>');
              //  $('div#post_up').hide(5000);
                Member_Info();
            });
            channel.bind('pusher:member_removed', function (member) {
                new Notification('Thông báo từ Laravel',
                    {
                        body: 'Member:'+member.info.name+' offline!!', // Nội dung thông báo
                        icon: 'http://iconizer.net/files/Simplicio/orig/notification_warning.png' // Hình ảnh
                        //tag: 'https://freetuts.net/' // Đường dẫn
                    });
              //  $('div#post_up').append('<label  class="alert alert-danger">'+member.info.name+' offline! </label>');
             //   $('div#post_up').hide(5000);

                Member_Info();
            });

        }
            CreatePresence();
            getNoticationPresence();
            StatusConnect();
            _Notication();

    </script>

@endsection
