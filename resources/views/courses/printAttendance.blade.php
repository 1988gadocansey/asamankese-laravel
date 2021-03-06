@extends('layouts.printlayout')

@section('content')

 
      
                    @inject('sys', 'App\Http\Controllers\SystemController')
          <div class="uk-width-xLarge-1-1" style="margin-left:10px">
   
    <div class="md-card">
        <div class="md-card-content">         
          <div class="uk-width-xL">            
                      <div class="uk-overflow-container" id='print'>
                            <center> <p>COURSE:<b> {!! $course !!} {!! $code !!}</b>    |  ACADEMIC YEAR = <b> {!! $year !!} | </b> SEMESTER = <b> {!! $sem !!}  </center></p>
     
                 <table   class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter"> 
             
               <thead>
                 <tr>
                     <th>N<u>o</u></th>
                     
                     <th  style="text-align:">Photo</th>
                      
                      <th>Index N<u>o</u></th>
                     <th style="text-align:">Name</th>
                     <th style="text-align:">Signature</th>
                     
                      
                                      
                </tr>
             </thead>
             <tbody>
                                        
                             @foreach($mark as $index=> $row) 



                            <tr align="">
                                <td> {{ $mark->perPage()*($mark->currentPage()-1)+($index+1) }} </td>
                                <td><img class=" " style="width:60px;height: auto" src="{!!url('public/albums/students/'.$sys->getStudentByID($row->student).'.jpg') !!}" alt=" Picture of Student Here"    /></td>

                                <td class="uk-text-success"> {{ @$sys->getStudentByID($row->student) }}</td>
                                <td class="uk-text-primary"> {{ @$sys->getStudentNameByID($row->student) }}</td>
                                <td>.................................</td>
 
                            </tr>
                             @endforeach
                        </tbody>
                                    
                             </table>
           
     </div>
  
          </div>
                 
           </div>
           </div></div>     
      
 
 
        
 @endsection
 
@section('js')
 <script type="text/javascript">
  
$(document).ready(function(){
window.print();
//window.close();
});

</script>
  
@endsection