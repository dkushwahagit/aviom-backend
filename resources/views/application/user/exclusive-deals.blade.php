@extends('layout.dashboard-layout')

@section('title', 'Exclusive Deals')

@push('styles')
<link href="{{asset('assets/css/my-profile.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/dse.css')}}" rel="stylesheet" type="text/css">
@endpush

@section('content')

  <div class="rightBox">
    <h1>Exclusive Deals</h1>
    <div class="containerBox">
      <div class="dsePage">
        <div class="listingBox">
          <div class="mostUseful clearfix">
            <h3 id="project-count">{{ count($data['retData'])}} Projects Found</h3>
            <div class="PriceSelectBox sortBy">
              <div class="dropDownSelectBox">
                <div class="input-group-btn select" id="sortBy">
                  <button type="button" class="btn btn-default dropdown-toggle citySelect" data-toggle="dropdown" aria-expanded="false"> 
                  <span class="selected">Sort By</span> </button>
                  <ul class="dropdown-menu option" role="menu">
                    <span class="diamond"></span>
                    <li id="MostWellConnected"><a href="javascript:void(0)"><em class="icon-Most-Well-Connected"></em> Most Well Connected</a></li>
                    <li id="MostLuxurious"><a href="javascript:void(0)"><em class="icon-Most-Luxurious"></em> Most Luxurious</a></li>
                    <li id="LeastPrice"><a href="javascript:void(0)"><em class="icon-price"></em> Least Price Per Sq.Ft</a></li>
                    <li id="HighPrice"><a href="javascript:void(0)"><em class="icon-High-Price"></em> High Price Per Sq.Ft</a></li>
                    <li id="TopSelling"><a href="javascript:void(0)"><em class="icon-Top-Selling"></em> Top Selling</a></li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div class="PriceSelectBox sortByCity">
              <div class="dropDownSelectBox">
                <div class="input-group-btn select" id="sortByCity">
                  <button type="button" class="btn btn-default dropdown-toggle citySelect" data-toggle="dropdown" aria-expanded="false"> 
                  <span class="selected">City Filter</span> </button>
                  <ul class="dropdown-menu option" role="menu">
                    <span class="diamond"></span>
                    @if(isset($city) && !empty($city))
                    @foreach($city as $c)
                    <li><a href="{{ url('/exclusive-deals/'.$c['cityId'])}}"> {{$c['cityName']}}</a></li>
                    @endforeach
                    @endif
                  </ul>
                </div>
              </div>
            </div>
            <div class="usefullTag"><small>Most Useful</small></div>
          </div>
          <div class="tileListingBox">
              
              {{-- print_r($city) --}}
              <?php $i = 1;?>
         @if (isset($data['retData']) && !empty($data['retData']))      
         @foreach ($data['retData'] as $k => $v)  
         @if($v['type'] == 'INDIA' && $v['projectId'] > 0)
            <div class="tile" id='{{$v['projectId']}}'>
              <div class="card">
                <div class="front">
                  <?php
                  $totalProjects = $i++;
                  $projectIdsArr[$k] = $v['projectId'];
                     foreach ($v['projectImages'] as $imgArr) {    
                       if ($imgArr['imageTypeName'] == 'Flagship') {
                           $src = 'https://d2e9thu7mhurn3.cloudfront.net/resources/images/'.strtolower($v['cityName']).'/tn-projectflagship/'.$imgArr['imageRelPath'];
                           break;
                       }  
                       
                     }
                   ?>  
                  <figure> <em class="icon-heart-line active"></em> <img src="{{$src}}" alt="associated-developers-logo" class="img-responsive">
                      <?php 
                      $projectName = strtolower($v['projectName']);
                      $projectName = str_replace(' ','-', $projectName);
                      $projectUrl = "http://squareyards.com/".strtolower($v['cityName'])."-residential-property/$projectName/".$v['projectId']."/project";
                      ?>
                    <div class="txt-mask"> <a href="{{$projectUrl}}" target="_blank"> <span>{{$v['projectName']}}</span></a>
                      
                        <p><small>{{ $v['subLocation'][0]['locationName'] or ''}}</small>, <small>{{ $v['cityName']}}</small></p>
                    </div>
                  </figure>
                  <div class="clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <h3>₹ {{ $v['projectMinPriceView'] }} to {{ $v['projectMaxPriceView'] }}</h3>
                      <div class="sub-price">₹ {{ $v['projectBSPCostView']}}   Per Sq. Ft Onwards</div>
                      <div class="statusPossion">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="status">
                              <p>Status<span>{{$v['projectStatusDesc']}}</span></p>
                              <div class="progressBar"> <span style="width:{{$v['projectStatusPer']}};"></span> </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                          <p class="sizeRange">Size Range</p>
                          <span class="sizeRangeDetails">{{$v['projectMinSize'] .' - '. $v['projectMaxSize']}} SF</span></div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <?php $bhkOptions = trim($v['bhkOptions']);?>
                        @if (isset($bhkOptions) && !empty($bhkOptions))    
                          <p class="bhkOptions">BHK Options</p>
                          <span class="bhkOptionsDetails">{{ $v['bhkOptions']}} BHK</span>
                        @endif  
                        </div>
                          
                      </div>
                    </div>
                  </div>
                  <div class="buttonBox2">
                    <ul>
                      <li> <a class="unit" href="javascript:void(0)"><em class="fa fa-th" ></em> Units</a> </li>
                      <li> <a href="{{$projectUrl}}" target="_blank">Detail</a> </li>
                    </ul>
                  </div>
                </div>
                <div class="back">
                  <div class="close"><em class="fa fa-times"></em></div>
                  
                  <ul>
                   <?php $unitUrl = "http://squareyards.com/".strtolower($v['cityName'])."-residential-property/";?>   
                  @foreach($v['unit'] as $unit)
                  <?php
                    $unitName = strtolower($unit['unitName']);
                    $unitName = str_replace(' ','-',$unitName);
                    
                  ?>
                  <li><a href="{{$unitUrl.$unitName."/".$v['projectId']."/".$unit['unitId']."/unit"}}" target="_blank"><strong>{{ $unit['bedroomCount']. ' BHK '}} {{ $unit['unitSize'] . ' Sq. Ft. '. $unit['unitCatName']}}</strong></a> <span>{{ $unit['totalLowCostView'] .' - '. $unit['totalHighCostView'] }}</span></li>
                  @endforeach  
                  </ul>
                </div>
              </div>
            </div>
         @endif
          @endforeach
          @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  


@endsection  {{-- content Section ends here --}}

@push('scripts')
    <script src="{{ asset("assets/js/function.js") }}" type="text/javascript"></script>
    <script>
$(document).ready(function(){ 
	var winHeight = $(window).height();
	var os = navigator.userAgent;	
	var height = 168;
	if (os.indexOf('Android') != -1 || os.indexOf("iPhone") != -1 || os.indexOf("iPad") != -1 || os.indexOf("BlackBerry") != -1) {
		height = 130;
	}
	else {
		height = 168;
	}
	$(".dsePage .viewFilterBox .filterSectionBox,.dsePage .viewFilterBox .filterSectionBox .tabs, .dsePage .viewFilterBox .filterSectionBox .tab-content") .css("height", (winHeight- height) + "px");
});


$(document).ready(function(){	
	var winHeight = $(window).height() ;
	$(".dsePage .listingBox") .css("min-height", (winHeight-60) + "px");	
	$(".dsePage .viewFilterBox") .css("height", (winHeight) + "px");	
});



$(document).ready(function(){		
	$(".dsePage .filterTypeBox ul li a,.dsePage .filterBtnBox").click(function(){
	$(".dsePage .viewFilterBox,.dsePage .filterBtnBox").addClass("active");
	});
	
	$(".dsePage .viewFilterBox .headingBox .clearBtn").click(function(){
	$(".dsePage .viewFilterBox,.dsePage .filterBtnBox").removeClass("active");
	});
	
	$(".dseHeadingBox .aboutTextBox .readMoreBox").click(function(){
	$(".dseHeadingBox .aboutTextBox, .dseHeadingBox").toggleClass("fullHeight");
	});	
	
	$(".tileListingBox").on('click','a.unit',function(event){
            event.stopPropagation();
	$(this).parents(".card").addClass("flipped");		
    
	});	
	
	$(".tileListingBox").on('click','.close',function(event){
        event.stopPropagation();    
	$(this).parents(".card.flipped").removeClass("flipped");		
    
	});	
});

</script> 
<script>
$('body').on('click','.option li',function(){
		var i = $(this).parents('.select').attr('id');
		var v = $(this).children().text();
		var o = $(this).attr('id');
		$('#'+i+' .selected').attr('id',o);
		$('#'+i+' .selected').text(v);
		});    
	
</script>
<script>
    var projectsApiUrl = 'http://api.squareyards.com/SquareYards/site/city/dsesort/';
    //var projectsApiUrl = 'http://api-uat.squareyards.com/SquareYards/site/city/dsesort/';
    $('#MostWellConnected').click(function () {
       var projectIdStr = '<?php echo implode(',', $projectIdsArr)?>';    
       $.ajax({
           async     : false,
           type      : 'GET',
           dataType  : 'json',
           url       : projectsApiUrl+projectIdStr+'/DSE_KEY_1',
           success   : function (data,statusText,jqXHR) {
                         var sortedArr = data.retData;
                          var newHtml = '';
                         $.each(sortedArr,function (index,projectId) {
                             newHtml = newHtml + '<div class="tile" id="'+projectId+'">'+$('#'+projectId).html()+'</div>';
                         });
                         $('.tileListingBox').empty().html(newHtml);
           },
           error     : function (jqXHR,statusText,errorThrown) {
               console.log(errorThrown);
           }
       });
    });
    
    $('#MostLuxurious').click(function () {
       var projectIdStr = '<?php echo implode(',', $projectIdsArr)?>';    
       $.ajax({
           async     : false,
           type      : 'GET',
           dataType  : 'json',
           url       : projectsApiUrl+projectIdStr+'/DSE_KEY_2',
           success   : function (data,statusText,jqXHR) {
                         var sortedArr = data.retData;
                          var newHtml = '';
                         $.each(sortedArr,function (index,projectId) {
                             newHtml = newHtml + '<div class="tile" id="'+projectId+'">'+$('#'+projectId).html()+'</div>';
                         });
                         $('.tileListingBox').empty().html(newHtml);
           },
           error     : function (jqXHR,statusText,errorThrown) {
               console.log(errorThrown);
           }
       });
    });
    
    $('#LeastPrice').click(function () {
       var projectIdStr = '<?php echo implode(',', $projectIdsArr)?>';    
       $.ajax({
           async     : false,
           type      : 'GET',
           dataType  : 'json',
           url       : projectsApiUrl+projectIdStr+'/DSE_KEY_3',
           success   : function (data,statusText,jqXHR) {
                         var sortedArr = data.retData;
                          var newHtml = '';
                         $.each(sortedArr,function (index,projectId) {
                             newHtml = newHtml + '<div class="tile" id="'+projectId+'">'+$('#'+projectId).html()+'</div>';
                         });
                         $('.tileListingBox').empty().html(newHtml);
           },
           error     : function (jqXHR,statusText,errorThrown) {
               console.log(errorThrown);
           }
       });
    });
    
    $('#HighPrice').click(function () {
       var projectIdStr = '<?php echo implode(',', $projectIdsArr)?>';    
       $.ajax({
           async     : false,
           type      : 'GET',
           dataType  : 'json',
           url       : projectsApiUrl+projectIdStr+'/DSE_KEY_7',
           success   : function (data,statusText,jqXHR) {
                         var sortedArr = data.retData;
                          var newHtml = '';
                         $.each(sortedArr,function (index,projectId) {
                             newHtml = newHtml + '<div class="tile" id="'+projectId+'">'+$('#'+projectId).html()+'</div>';
                         });
                         $('.tileListingBox').empty().html(newHtml);
           },
           error     : function (jqXHR,statusText,errorThrown) {
               console.log(errorThrown);
           }
       });
    });
    
    $('#TopSelling').click(function () {
       window.location.reload();
    });
    $('#project-count').text('{{$totalProjects}} Projects Found')
</script>
@endpush