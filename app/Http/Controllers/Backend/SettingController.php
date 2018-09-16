<?php

namespace App\Http\Controllers\Backend;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SettingController extends Controller
{
    public function index(){
        if(Setting::first()){
            $url_callback_bot=Setting::first();
        }else{
            $url_callback_bot='';
        }


        return view('backend.setting')->with(['url_callback_bot'=>$url_callback_bot]);
    }

    public function store(Request $request)
    {
        Setting::where('key','!=', NULL)->delete();

        foreach ($request->except('_token') as $key=>$value){

                $setting=new Setting();
                $setting->key =$key;
                $setting->value =$value;
                $setting->save();

        }

        return redirect()->route('admin.setting.index');
    }

    public function setwebhook(Request $request){

$result= $this->sendTelegramData('setwebhook',['query'=>['url'=>$request->url .'/'. \Telegram::getAccessToken()]]);

        return redirect()->route('admin.setting.index')->with(['status'=>$result]);
    }

    public function getwebhookinfo(Request $request)
    {
        $result =$this->sendTelegramData('getWebhookInfo');

        return redirect()->route('admin.setting.index')->with(['status'=>$result]);
    }

    public function sendTelegramData($route='',$params=[],$method="POST")
    {

        $client =new \GuzzleHttp\Client(['base_uri'=>'https://api.telegram.org/bot' .\Telegram::getAccessToken().'/']);

        $result=$client->request($method,$route,$params);
        return (string) $result->getBody();
    }
}
