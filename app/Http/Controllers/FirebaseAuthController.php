<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;

use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

use Kreait\Firebase\Messaging\WebPushConfig;
use Kreait\Firebase\Messaging\ApnsConfig;

// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;

class FirebaseAuthController extends Controller
{
    protected $auth, $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->auth = Firebase::auth();
        // $this->auth = Firebase::project('ComplaintManagementSystem')->auth();
        $this->messaging = $messaging;

    }
    public function test_firebase(){
        $notification = [
            "title" => "User test msg",
            "body" => "Mr ABC, oy",  
            // "imageUrl"=>"http://cms.outreachmarketing.com.pk/storage/logos/app/logo-small.png"
        ];
        $data = [
            "id" => 105
            
        ];
        // $fb = new FirebaseAuthController();

        // $fcm = new FirebaseAuthController($this->messaging);
        $fcm_tokens = [
            auth()->user()->fcm_token,
        ];

        $resp = $this->send($fcm_tokens , $notification, $data);
        
        dd('sent', $resp, $fcm_tokens);
    }

    public function store_fcm(Request $request){
        $fcm_validator = Validator::make($request->all(), [
            'fcm_token' => ['required']
        ]);

        if ($fcm_validator->fails()) {
            return response()->json(['error' => $fcm_validator->errors()], 402);
        }

        $data = $fcm_validator->validated();

        User::where('id', auth()->user()->id)->update([
            'fcm_token' => $data['fcm_token']
        ]);
        return response()->json('done', 200);
    }

   
    public function send($fcm_tokens, $notification, $data){

        // $topic = 'a-topic';

        // // $message = CloudMessage::withTarget('topic', $topic)
        // //     ->withNotification($notification) // optional
        // //     ->withData($data) // optional
        // // ;

        // $message = CloudMessage::fromArray([
        //     'topic' => $topic,
        //     'notification' => ['hello'], // optional
        //     'data' => [], // optional
        // ]);

        // $this->messaging->send($message);


        $deviceTokens = $fcm_tokens ;

        $notification = Notification::fromArray($notification);
       
        $message = CloudMessage::fromArray([
            'notification' => $notification,
            'data' => $data,
              
        ]); 

    
        $sendReport = $this->messaging->sendMulticast($message, $deviceTokens);
    }


    // public function SendPushNotification($event,$user){
    //     // $messaging = new Messaging();
    //     if($user->fcm_token && $event){
    //         $creator = \App\Models\User::Where('id', $event['event_creatorid'])->first();
    //         $fcm = new FirebaseAuthController($this->messaging);
    //         // dd($user);
    //         $fcm_tokens = [$user->fcm_token];
    //         $title = $creator->first_name.' '.$creator->last_name;
    //         $body = runtimeLang($event['event_item_lang'])." in ".$event['event_parent_title']."\n".strip_tags($event['event_item_content']);
    //         $notification = [
    //             "title" => $title,
    //             "body" => $body,  
    //             // "imageUrl"=>"http://cms.outreachmarketing.com.pk/storage/logos/app/logo-small.png"
    //         ];
    //         $data = $event;
    //         // dd($event);

    //         // dd($fcm_tokens, $notification, $data);
    //         try{
    //             $resp = $fcm->send($fcm_tokens, $notification, $data);
    //         }catch(\Exception $e){
    //             Log::error($e->getMessage());
    //         }
    //     }
    // }

     // public function token(){
    //     $uid = 'some-uid';

    //     $customToken = $this->auth->createCustomToken($uid);

    //     // $key = '{
    //     //     "7602712682df99cfb891aa037d73bcc6a3970084": "-----BEGIN CERTIFICATE-----\nMIIDHTCCAgWgAwIBAgIJALeM3DBeoI3wMA0GCSqGSIb3DQEBBQUAMDExLzAtBgNV\nBAMMJnNlY3VyZXRva2VuLnN5c3RlbS5nc2VydmljZWFjY291bnQuY29tMB4XDTI0\nMDQzMDA3MzIyMVoXDTI0MDUxNjE5NDcyMVowMTEvMC0GA1UEAwwmc2VjdXJldG9r\nZW4uc3lzdGVtLmdzZXJ2aWNlYWNjb3VudC5jb20wggEiMA0GCSqGSIb3DQEBAQUA\nA4IBDwAwggEKAoIBAQCZ5H6KYWuP1+SwCsN9tQXsD4JXii7FEJr/NGoQnHePBobr\nOzHaSdyxov7o3XqtouXmRDetVpANdph2r5+rTFY1231KehQF9HosYDA4zT/Ph32Z\n+kpS9Xlkg+515lowQtYGnwlAmnHirivTgUmrHR7GaVVOo7K5erD1tbFiIjTgtHNR\njlxVS786WEdvOkVodJQcKX5/5FDlI01AAbnbLf+iKpCq/bXNGFQI/6r47TTo9qEm\nUAHoPaqW2LceGpH1qqoBoBRfsS4qaWbAxsHjs0cur4x4Ai1c+iJbFNHQfbis/BzM\n5d5DLDFV4n3ZPie03aJoonWbiJX1eTW1No4XyozfAgMBAAGjODA2MAwGA1UdEwEB\n/wQCMAAwDgYDVR0PAQH/BAQDAgeAMBYGA1UdJQEB/wQMMAoGCCsGAQUFBwMCMA0G\nCSqGSIb3DQEBBQUAA4IBAQBGERUt+83Ar/OjpwpG9n1hsgM5X5TBrZXMPLpzlr0Y\nDOSB3svrvwBOcJftddUIStJKaEaFwuK+N6TuxtYbcE8tBF7QG1H1M7OdIb8j1o4j\naGggP9ziXiFgRHBADd8o4gHgeBygfZQUU73XHDu1jSzNsUELF0mUt5ffKxSoRtq2\ne1ng74n9sBmExN7HNW8DnyXyF21AnFeCqY3ttTY4KttsGKIXJB1PKXZ31wbTTeVH\njmn+QRC6co2ENNCgCtWr1GiBrgkve8HbtR1qbSDnpBiGAdH+yxBWCRNTEEPW4E7b\nZTGhgbFh1YNFf/+ihvomrfCeCdfwbQEkvs6hhQAI4nTC\n-----END CERTIFICATE-----\n",
    //     //     "e2b22fd47ede682f68faccfe7c4cf5b11b12b54b": "-----BEGIN CERTIFICATE-----\nMIIDHTCCAgWgAwIBAgIJAINORaFSRUgEMA0GCSqGSIb3DQEBBQUAMDExLzAtBgNV\nBAMMJnNlY3VyZXRva2VuLnN5c3RlbS5nc2VydmljZWFjY291bnQuY29tMB4XDTI0\nMDUwODA3MzIyMloXDTI0MDUyNDE5NDcyMlowMTEvMC0GA1UEAwwmc2VjdXJldG9r\nZW4uc3lzdGVtLmdzZXJ2aWNlYWNjb3VudC5jb20wggEiMA0GCSqGSIb3DQEBAQUA\nA4IBDwAwggEKAoIBAQClR/jplqJnYpXpiLke5EqbbRL0+wxL0JC87YsIUB5zXuEL\nfxokjCl2lMfDc0w+L6PjG8CZt3W6S8M5UMTglNS/91oz4WiqaEc4hKaknYfSk8vs\nxnA3WAs/0OWulJc1M2B5ikuiFXi1lemLS+sOFe63ukuzXuQqYc2Z8uf/QUEQwvTn\n1VK2Ij4dDNYcygnTCgE8F+j5+SE/+C6Ik6B+xkG1EICPAO3FFCB8KxuDL7s4HKzY\nIzrp/Vf/pTv9DMPQykTgZ5crrtAQYMyyrOY50lYQgFc0V44Dt9OVh/pIpo9cHXO6\n9QY1EMZegRW1LINQDlEyHQaGK0CmkFqQoul8Y7cnAgMBAAGjODA2MAwGA1UdEwEB\n/wQCMAAwDgYDVR0PAQH/BAQDAgeAMBYGA1UdJQEB/wQMMAoGCCsGAQUFBwMCMA0G\nCSqGSIb3DQEBBQUAA4IBAQBZAFCH5zX+Bk1uwnh5duuj20HsvCSv5/OEBSEJyRfw\n6IHajwMQF6yxEXGcpcUGweUoCqDsqYiu/44/ZzTPILAU6rPTbznUp47miWWxGBu+\nnqFdv9OfrAKW+tTBhzaNskBrYI99uLPs+CbxRL4OjScZt8LhMKMg9nAzMOIgU+Z1\nqzQzl5WUZNMXvVjBCaSKVEn/GtdGTr+mGi1LOQkYAKs7lDl7656biGkY4ezfUiOi\nCWx70CyDv90Jy6V+uN+i3Fb1RHJYstavuRbZMJ4GxDrN6Fr3Z8xPmnpSJHrnL5Xl\nILs7iZPe6WcYlYkh8lO3oCXdOTSCVM/hL1dXqxL6Mqts\n-----END CERTIFICATE-----\n"
    //     //   }';
    //     // $customToken = JWT::decode($customToken, json_encode($key), array('HS256'));
    //     dd($customToken);

    //     // $this->verify_token($customToken);
    // }

    // public function verify_token($idTokenString){

    //     // $idTokenString = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9';

    //     try {
    //         $verifiedIdToken = $this->auth->verifyIdToken($idTokenString);
    //     } catch (FailedToVerifyToken $e) {
    //         dd('The token is invalid: '.$e->getMessage());
    //     }

    //     $uid = $verifiedIdToken->claims()->get('sub');

    //     $user = $this->auth->getUser($uid);

    //     dd($user, $uid,  $verifiedIdToken);
    // }

}
