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
  	$feed = Feeds::make('http://www.squareyards.com/blog/feed/');
    $data = array(
      'title'     => $feed->get_title(),
      'permalink' => $feed->get_permalink(),
      'items'     => $feed->get_items(),
    );
    return view('application.user.common.square-news')->with('data',$data);
  }
}