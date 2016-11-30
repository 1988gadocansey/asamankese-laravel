@extends('layouts.app')

 
@section('style')
 
@endsection
 @section('content')
   <div class="md-card-content">
@if(Session::has('success'))
            <div style="text-align: center" class="uk-alert uk-alert-success" data-uk-alert="">
                {!! Session::get('success') !!}
            </div>
 @endif
 @if(Session::has('error'))
            <div style="text-align: center" class="uk-alert uk-alert-danger" data-uk-alert="">
                {!! Session::get('error') !!}
            </div>
 @endif
 
     @if (count($errors) > 0)

    <div class="uk-form-row">
        <div class="uk-alert uk-alert-danger" style="background-color: red;color: white">

              <ul>
                @foreach ($errors->all() as $error)
                  <li> {{  $error  }} </li>
                @endforeach
          </ul>
    </div>
  </div>
@endif
  </div>
  
 <div style="">
     <div class="uk-margin-bottom" style="margin-left:750px" >
         <!-- <a  href="#new_task" data-uk-modal="{ center:true }"> <i title="click to send sms to students owing"   class="material-icons md-36 uk-text-success"   >phonelink_ring message</i></a>
 -->
         <a href="#" class="md-btn md-btn-small md-btn-success uk-margin-right" id="printTable">Print Table</a>
        <!--  <a href="#" class="md-btn md-btn-small md-btn-success uk-margin-right" id="">Import from Excel</a>
         -->
         <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
             <button class="md-btn md-btn-small md-btn-success"> columns <i class="uk-icon-caret-down"></i></button>
             <div class="uk-dropdown">
                 <ul class="uk-nav uk-nav-dropdown" id="columnSelector"></ul>
             </div>
         </div>
         

                       
                          
                           
         <div style="margin-top: -5px" class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
                                <button class="md-btn md-btn-small md-btn-success uk-margin-small-top">Export <i class="uk-icon-caret-down"></i></button>
                                <div class="uk-dropdown">
                                    <ul class="uk-nav uk-nav-dropdown">
                                         <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'csv',escape:'false'});"><img src='{!! url("public/assets/icons/csv.png")!!}' width="24"/> CSV</a></li>
                                           
                                            <li class="uk-nav-divider"></li>
                                            <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'excel',escape:'false'});"><img src='{!! url("public/assets/icons/xls.png")!!}' width="24"/> XLS</a></li>
                                            <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'doc',escape:'false'});"><img src='{!! url("public/assets/icons/word.png")!!}' width="24"/> Word</a></li>
                                            <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'powerpoint',escape:'false'});"><img src='{!! url("public/assets/icons/ppt.png")!!}' width="24"/> PowerPoint</a></li>
                                            <li class="uk-nav-divider"></li>
                                           
                                    </ul>
                                </div>
                            </div>
                       
                           
                            
                                                   
                                  <i title="click to print" onclick="javascript:printDiv('print')" class="material-icons md-36 uk-text-success"   >print</i>
                   
                            
                           
     </div>
 </div>
 <div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">
            
                <form action=" "  method="get" accept-charset="utf-8" novalidate id="group">
                   {!!  csrf_field()  !!}
                    <div class="uk-grid" data-uk-grid-margin="">

                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                    {!! Form::select('program', 
                                (['' => 'All programs'] +$program ), 
                                  old("program",""),
                                    ['class' => 'md-input parent','id'=>"parent",'placeholder'=>'select program'] )  !!}
                         </div>
                        </div>
                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                 
                                             {!!  Form::select('level', array('50'=>'50','1'=>'1st years','2' => '2nd years', '3' => '3rd years','400/1'=>'BTECH level 1','400/2'=>'BTECH level 2'), null, ['placeholder' => 'select level','id'=>'parent','class'=>'md-input parent'],old("level","")); !!}
                          
                            </div>
                        </div>
                       
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                 
                                              {!!  Form::select('semester', array('1'=>'1st sem','2'=>'2nd sem','3' => '3rd sem'), null, ['placeholder' => 'select semester','id'=>'parent','class'=>'md-input parent'],old("semester","")); !!}
                          
                            </div>
                        </div>
                        

                        
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">                            
                                <input type="text" style=" "   name="search"  class="md-input" placeholder="search by course name or course code">
                            </div>
                        </div>
                         
                         
                        
                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                                {!!  Form::select('by', array('COURSE_CODE'=>'Course Code','COURSE_NAME'=>'Course Name' ), null, ['placeholder' => 'select criteria','class'=>'md-input'],old("by","")); !!}
                          
                            </div>
                        </div>
                        
                        
                    
                    </div> 
                         <div  align='center'>
                            
                            <button class="md-btn  md-btn-small md-btn-success uk-margin-small-top" type="submit"><i class="material-icons">search</i></button> 
                             
                        </div>
                   
                </form> 
        </div>
    </div>
 </div>
 <h5>Courses</h5>
 <div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">
   <div class="uk-overflow-container" id='print'>
         <center><span class="uk-text-success uk-text-bold">{!! $data->total()!!} Records</span></center>
                <table class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter"> 
               <thead>
                 <tr>
                     <th class="filter-false remove sorter-false" data-priority="6">NO</th>
                      <th>COURSE</th>
                     <th  style="text-align:center">CODE</th>
                     <th>PROGRAMME</th> 
                     <th style="text-align:center">CREDIT</th>

                     <th style="text-align:center">LEVEL</th>
                     <th style="text-align:center">SEMESTER</th>
                      
                     <th  class="filter-false remove sorter-false uk-text-center" colspan="2" data-priority="1">ACTION</th>   
                                     
                </tr>
             </thead>
      <tbody>
                                        
                                         @foreach($data as $index=> $row) 
                                         
                                         
                                        <tr align="">
                                            <td> {{ $data->perPage()*($data->currentPage()-1)+($index+1) }} </td>
                                            <td> {{ @$row->COURSE_NAME }}</td>
                                            <td> {{ @$row->COURSE_CODE	 }}</td>
                                            <td> {{ @$row->programme->PROGRAMME	 }}</td>
                                            <td> {{ @$row->COURSE_CREDIT	 }}</td>
                                            <td> {{ @$row->COURSE_LEVEL }}</td>
                                           <td> {{ @$row->COURSE_SEMESTER }}</td>
                                           
                                            <td> 
                                                
<!--                                             <a href='{{url("edit_course/$row->ID/id")}}' ><i title='Click to edit course' class="md-icon material-icons">edit</i></a> 
                                                -->
                                             {!!Form::open(['action' =>['CourseController@destroy', 'id'=>$row->ID], 'method' => 'DELETE','name'=>'c' ,'style' => 'display: inline;'])  !!}

                                                      <button type="submit" onclick="return confirm('Are you sure you want to delete   {{$row->COURSE_NAME}} -  {{ @$row->programme->PROGRAMME	 }}?')" class="md-btn  md-btn-danger md-btn-small   md-btn-wave-light waves-effect waves-button waves-light" ><i  class="sidebar-menu-icon material-icons md-18">delete</i></button>
                                                        
                                                     {!! Form::close() !!}
                                            </td>
                                          
                                        </tr>
                                            @endforeach
                                    </tbody>
                                    
                             </table>
           {!! (new Landish\Pagination\UIKit($data->appends(old())))->render() !!}
     </div>
     </div>
<div class="md-fab-wrapper">
        <a class="md-fab md-fab-small md-fab-accent md-fab-wave" href="{!! url('/create_course') !!}">
            <i class="material-icons md-18">&#xE145;</i>
        </a>
    </div>
 </div>
</div>
  
@endsection
@section('js')
 <script type="text/javascript">
      
$(document).ready(function(){
 
$(".parent").on('change',function(e){
 
   $("#group").submit();
 
});
});

</script>
 <script src="{!! url('public/assets/js/select2.full.min.js') !!}"></script>
<script>
$(document).ready(function(){
  $('select').select2({ width: "resolve" });

  
});


</script>
 <!--  notifications functions -->
    <script src="public/assets/js/components_notifications.min.js"></script>
@endsection