<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use willvincent\Feeds\Facades\FeedsFacade;

class CommonController extends Controller
{
  public function fetchNews() {
  	$feed = file_get_contents('http://www.squareyards.com/blog/beatsfeed/');
        
        //all title here
         $breakForAll = explode("<item>",$feed);
         $dataArrNewFeeds = array();
         foreach($breakForAll as $k=>$allItem){
             if($k != 0){
                $brakForTitle = explode("<title>", $allItem);
                $brakForTitleFinal = explode("</title>", $brakForTitle[1]);
                $dataArrNewFeeds['title'][] = $brakForTitleFinal[0];
                
                $brakForlink = explode("<link>", $allItem);
                $brakForlinkFinal = explode("</link>", $brakForlink[1]);
                $dataArrNewFeeds['link'][] = $brakForlinkFinal[0];
                
                $brakForDesc = explode("<description><![CDATA[", $allItem);
                $brakForDescFinal = explode("]]></description>", $brakForTitle[1]);
                $dataArrNewFeeds['desc'][] = $brakForDescFinal[0];
                
                $brakForpubDate = explode("<pubDate>", $allItem);
                $brakForpubDateFinal = explode("</pubDate>", $brakForpubDate[1]);
                
                $dateFormat = explode(" ", $brakForpubDateFinal[0]);
                $newDateFormat = $dateFormat[2]." ".$dateFormat[1].",".$dateFormat[3];
                $dataArrNewFeeds['pubDate'][] = $newDateFormat;
                
                $brakForimage = explode("<image>", $allItem);
                $brakForimageFinal = explode("</image>", $brakForimage[1]);
                $dataArrNewFeeds['image'][] = $brakForimageFinal[0];
                
                $brakForguid = explode("<guid>", $allItem);
                $brakForguidFinal = explode("</guid>", $brakForguid[1]);
                $dataArrNewFeeds['guid'][] = $brakForguidFinal[0];
                 
             }
         }
       //echo "<pre>";print_r($dataArrNewFeeds); 
       //die;
   /* $data = array(
      'title'     => $feed->get_title(),
      'permalink' => $feed->get_permalink(),
      'items'     => $feed->get_items(),
    );*/ 
     //  die;
    return view('application.user.common.square-news')->with('data',$dataArrNewFeeds);
  }
}