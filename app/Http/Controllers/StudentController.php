<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\StudentModel;
use App\Models\ProgrammeModel;
use App\Models;
use App\User;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Excel;
class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
//        //$this->generateAccounts();
//        $query=  \App\Models\StudentModel::where("LEVEL","!=","")->get();
//        foreach($query as $index=>$row){
//        $que=Models\PortalPasswordModel::where("username",$row->STNO)->first();  
//                  if(empty($que)){
//                   
//                   $real=strtoupper(str_random(9));
//                   
//                    Models\PortalPasswordModel::create([
//                    'username' => $row->STNO,
//                     'real_password' =>$real,
//                          'level' =>$row->LEVEL,
//                         'programme' =>$row->PROGRAMMECODE,
//                    'password' => bcrypt($real),
//                  ]);}
//        }
    }
    public function generateAccounts() {
        ini_set('max_execution_time', 3000); //300 seconds = 5 minutes
         $user=  Models\PortalPasswordModel::where('year','2016/2017')->where('level','100')->get();
         foreach($user as $users=>$row){
             
             $student=$row->username;
             $password=  strtoupper(str_random(9));
             $hashedPassword = bcrypt($password);
             
             Models\PortalPasswordModel::where('username',$student)->where('level','100')->update(array("password" => $hashedPassword,'real_password'=>$password));
             
         } 
         
    }
    public function index(Request $request, SystemController $sys) {
         
          if($request->user()->isSupperAdmin || @\Auth::user()->role=="FO" || @\Auth::user()->department=="Finance" ||    @\Auth::user()->department=="top"){
             $student = StudentModel::query();
         }
         
         else{
        $student = StudentModel::where('indexNo', '!=', '')->whereHas('programme', function($q) {
            $q->whereHas('departments', function($q) {
                $q->whereIn('deptCode', array(@\Auth::user()->department));
            });
        }) ;
         }
         
         if ($request->has('department') && trim($request->input('department')) != "") {
               $student->whereHas('programme', function($q)use ($request) {
            $q->whereHas('departments', function($q)use ($request) {
                $q->whereIn('deptCode', [$request->input('department')]);
            });
        });
        }
        if ($request->has('type') && trim($request->input('type')) != "") {
               $student->whereHas('programme', function($q)use ($request) {
         
                $q->where('TYPE', [$request->input('type')]);
            
        });
        }
        
          
        
         
         
        if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $student->where($request->input('by'), "LIKE", "%" . $request->input("search", "") . "%");
        }
        if ($request->has('program') && trim($request->input('program')) != "") {
            $student->where("programme", $request->input("program", ""));
        }
        if ($request->has('class') && trim($request->input('class')) != "") {
            $student->where("currentClass", $request->input("class", ""));
        }
        if ($request->has('status') && trim($request->input('status')) != "") {
            $student->where("status", $request->input("status", ""));
        }
        if ($request->has('group') && trim($request->input('group')) != "") {
            $student->where("yearGroup", $request->input("yearGroup", ""));
        }
        if ($request->has('nationality') && trim($request->input('nationality')) != "") {
            $student->where("nationality", $request->input("country", ""));
        }
        if ($request->has('region') && trim($request->input('region')) != "") {
            $student->where("region", $request->input("region", ""));
        }
        if ($request->has('gender') && trim($request->input('gender')) != "") {
            $student->where("gender", $request->input("gender", ""));
        }
        if ($request->has('sms') && trim($request->input('sms')) != "") {
            $student->where("SMS_SENT", $request->input("sms", ""));
        }
        if ($request->has('house') && trim($request->input('house')) != "") {
            $student->where("house", $request->input("house", ""));
        }
        
        if ($request->has('religion') && trim($request->input('religion')) != "") {
            $student->where("religion", $request->input("religion", ""));
        }
        if ($request->has('search') && trim($request->input('search')) != "" && trim($request->input('by')) != "") {
            // dd($request);
            $student->where($request->input('by'), "LIKE", "%" . $request->input("search", "") . "%")
               ->orWhere("indexNo","LIKE", "%" . $request->input("search", "") . "%");
        }
        $data = $student->orderBy('currentClass')->orderBy('programme')->orderBy('indexNo')->paginate(300);

        $request->flashExcept("_token");

        \Session::put('students', $data);
        return view('students.index')->with("data", $data)
                        ->with('year', $sys->years())
                        ->with('nationality', $sys->getCountry())
                         
                        ->with('religion', $sys->getReligion())
                        ->with('region', $sys->getRegions())
                        ->with('department', $sys->getDepartmentList())
                        ->with('class', $sys->getClassList())
                        ->with('house', $sys->getHouseList())
                        ->with('programme', $sys->getProgramList())
                      ;
        
        
    }
     public function sms(Request $request, SystemController $sys){
         ini_set('max_execution_time', 3000); //300 seconds = 5 minutes
         $message = $request->input("message", "");
        $query = \Session::get('students');
        


        foreach($query as $rtmt=> $member) {
            $NAME = $member->NAME;
            $FIRSTNAME = $member->FIRSTNAME;
            $SURNAME = $member->SURNAME;
            $PROGRAMME = $sys->getProgram($member->PROGRAMME);
            $INDEXNO = $member->INDEXNO;
            $CGPA = $member->CGPA;
            $BILLS = $member->BILLS;
            $BILL_OWING = $member->BILL_OWING;
            $PASSWORD=$sys->getStudentPassword($INDEXNO);
            $newstring = str_replace("]", "", "$message");
            $finalstring = str_replace("[", "$", "$newstring");
            eval("\$finalstring =\"$finalstring\" ;");
             if ($sys->firesms($finalstring,$member->TELEPHONENO,$member->INDEXNO)) {

                 StudentModel::where("INDEXNO",$INDEXNO)->update(array("SMS_SENT","1"));
               
            } else {
               // return redirect('/students')->withErrors("SMS could not be sent.. please verify if you have sms data and internet access.");
            }
        }
          return redirect('/students')->with('success','Message sent to students successfully');
         
         \Session::forget('students');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function getIndex(Request $request)
    {
        
        return view('students.index');
    }
    public function anyData(Request $request)
    {
         
        $students = StudentModel::join('tpoly_programme', 'tpoly_students.PROGRAMMECODE', '=', 'tpoly_programme.PROGRAMMECODE')
           ->select(['tpoly_students.ID', 'tpoly_students.NAME','tpoly_students.INDEXNO', 'tpoly_programme.PROGRAMME','tpoly_students.LEVEL','tpoly_students.INDEXNO','tpoly_students.SEX','tpoly_students.AGE','tpoly_students.TELEPHONENO','tpoly_students.COUNTRY','tpoly_students.GRADUATING_GROUP','tpoly_students.STATUS']);
         


        return Datatables::of($students)
                         
            ->addColumn('action', function ($student) {
                 return "<a href=\"edit_student/$student->INDEXNO/id\" class=\"\"><i title='Click to view student details' class=\"md-icon material-icons\">&#xE88F;</i></a>";
                 // use <i class=\"md-icon material-icons\">&#xE254;</i> for showing editing icon
                //return' <td> <a href=" "><img class="" style="width:70px;height: auto" src="public/Albums/students/'.$student->INDEXNO.'.JPG" alt=" Picture of Employee Here"    /></a>df</td>';
                          
                                         
            })
               ->editColumn('id', '{!! $ID!!}')
            ->addColumn('Photo', function ($student) {
               // return '<a href="#edit-'.$student->ID.'" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light">View</a>';
            
                return' <a href="show_student/'.$student->INDEXNO.'/id"><img class="md-user-image-large" style="width:60px;height: auto" src="Albums/students/'.$student->INDEXNO.'.JPG" alt=" Picture of Student Here"    /></a>';
                          
                                         
            })
              
            
            ->setRowId('id')
            ->setRowClass(function ($student) {
                return $student->ID % 2 == 0 ? 'uk-text-success' : 'uk-text-warning';
            })
            ->setRowData([
                'id' => 'test',
            ])
            ->setRowAttr([
                'color' => 'red',
            ])
                  
            ->make(true);
             
            //flash the request so it can still be available to the view or search form and the search parameters shown on the form 
      //$request->flash();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(SystemController $sys)
    {
        $region=$sys->getRegions();
             $programme=$sys->getProgramList();
        $hall=$sys->getHalls();
        $religion=$sys->getReligion();
        return view('students.create')
            ->with('programme', $programme)
            ->with('country', $sys->getCountry())
            ->with('region', $region)
            ->with('hall',$hall)
            ->with('religion',$religion);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SystemController $sys)
    {
      
          set_time_limit(36000);
        /*transaction is used here so that any errror rolls
         *  back the whole process and prevents any inserts or updates
         */
 if($request->user()->isSupperAdmin || @\Auth::user()->role=="Dean" || @\Auth::user()->department=="top"|| @\Auth::user()->role=="HOD"){
       
  \DB::beginTransaction();

        $user = @\Auth::user()->id;
        
        $year=$request->input('year');
        if($year=='1'){
            $level='100';
        }
        elseif($year=='2'){
            $level='200';
        }
         elseif($year=='3'){
            $level='300';
        }
        elseif($year=='4'){
            $level='400';
        }
        else{
           $level= $year;
        }
       $array=$sys->getSemYear();
                  
        $fiscalYear=$array[0]->YEAR;
         $indexno = $request->input('indexno');
         $program = $request->input('programme');
        $gender = $request->input('gender');
        $category = $request->input('category');
        $hostel = $request->input('hostel');
        $hall = $request->input('halls');
        $dob = $request->input('dob');
        $gname = $request->input('gname');
        $gphone = $request->input('gphone');
        $goccupation = $request->input('goccupation');
        $gaddress = $request->input('gaddress');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $marital_status = $request->input('marital_status');
        $region = $request->input('region');
        $country = $request->input('nationality');
        $religion = $request->input('religion');
        $residentAddress = $request->input('contact');
        $address = $request->input('address');
        $hometown = $request->input('hometown');
        $nhis = $request->input('nhis');
        $type = $request->input('type');
        $disability = $request->input('disabilty');
        $title = $request->input('title');
        $age = $sys->age($dob, 'eu');
        $group = "";
        $fname = $request->input('fname');
        $bill= $request->input('bill');
        $lname = $request->input('surname');
        $othername = $request->input('othernames');
      
        $sql=  StudentModel::where("STNO",$indexno)->first();
        if(empty($sql)){
            /////////////////////////////////////////////////////
        
        $name = $lname . ' ' . $othername . ' ' . $fname;
        $query = new StudentModel();
        $query->YEAR = $year;
        $query->LEVEL = $level;
        $query->FIRSTNAME = $fname;
        $query->SURNAME = $lname;
        $query->OTHERNAMES = $othername;
        $query->TITLE = $title;
        $query->SEX = $gender;
        $query->DATEOFBIRTH = $dob;
        $query->NAME = $name;
        $query->AGE = $age;
        $query->GRADUATING_GROUP = $group;
        $query->MARITAL_STATUS = $marital_status;
        $query->HALL = $hall;
        $query->ADDRESS = $address;
        $query->RESIDENTIAL_ADDRESS = $residentAddress;
        $query->EMAIL = $email;
        $query->PROGRAMMECODE = $program;
        $query->TELEPHONENO = $phone;
        $query->COUNTRY = $country;
        $query->REGION = $region;
        $query->RELIGION = $religion;
        $query->HOMETOWN = $hometown;
        $query->GUARDIAN_NAME = $gname;
        $query->GUARDIAN_ADDRESS = $gaddress;
        $query->GUARDIAN_PHONE = $gphone;
        $query->GUARDIAN_OCCUPATION = $goccupation;
        $query->DISABILITY = $disability;
        $query->STATUS = "In School";
        $query->SYSUPDATE = "1";
        $query->NHIS = $nhis;
        $query->STUDENT_TYPE = $type;
        $query->TYPE = $category;

        $query->HOSTEL = $hostel;
         $query->BILLS=$sys->getYearBill( $fiscalYear, $level, $program);
         $query->BILL_OWING=$sys->getYearBill( $fiscalYear, $level, $program);
        $query->STNO =$indexno;
        $query->INDEXNO =$indexno;

        if($query->save()){
             \DB::commit();
               $que=Models\PortalPasswordModel::where("username",$indexno)->first();  
                  if(empty($que)){
                    $program=$program;
                    $str = 'abcdefhkmnprtuvwxyz234678';
                    $shuffled = str_shuffle($str);
                    $vcode = substr($shuffled,0,9);
                   $real=strtoupper($vcode);
                   $level= $level;
                    Models\PortalPasswordModel::create([
                    'username' => $indexno,
                     'real_password' =>$real,
                          'level' =>$level,
                         'programme' =>$program,
                        'biodata_update' =>'1',
                    'password' => bcrypt($real),
                ]);
                  
                     $message = "Hi $fname, Please visit portal.tpolyonline.com to do update your biodata with $indexno as your username  and $real as password and then follow to the Account office for fee verification ";
                   
                     \DB::commit();
                    if ($sys->firesms($message, $phone, $indexno)) {
                        
                    }
                  }
       return redirect("/students")->with("success"," <span style='font-weight:bold;font-size:13px;'> student successfully added!</span> ");
             
          }else{
           
             return redirect("/add_students")->with("error"," <span style='font-weight:bold;font-size:13px;'> student could not be added try again!</span>");
           
              
          }
        }
        else{
             return redirect("/add_students")->with("error"," <span style='font-weight:bold;font-size:13px;'>Please student exist in the system already!</span>");
           
        }
    } else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,  SystemController $sys,Request $request)
    {
        
         $region=$sys->getRegions();
        
        
        // make sure only students who are currently in school can update their data
        $query = StudentModel::where('id', $id)->first();
        
        
        $trails=  Models\AcademicRecordsModel::where('student', $id)->where("grade","E")->orwhere("grade","F")->paginate(100);
        return view('students.show')->with('student', $query) ->with('trail',$trails);
             
            
            
           
             
    }
    public function uploadStaff(Request $request) {
       if($request->hasFile('file')){
            $file=$request->file('file');
            $user = \Auth::user()->id;
             
            $ext = strtolower($file->getClientOriginalExtension());
            $valid_exts = array('csv','xlx','xlsx'); // valid extensions
            
            $path = $request->file('file')->getRealPath();
         if (in_array($ext, $valid_exts)) {
            $data = Excel::load($path, function($reader) {
                        
                    })->get();

                    dd($data);
            if(!empty($data) && $data->count()){

				foreach ($data as $key => $value) {

		$insert[] = ['fullName' => $value->name, 'staffID' => $value->staffID,'department'=>$value->Department,'grade'=>$value->grade,'designation'=>$value->position,'phone'=>$value->phone];

				}

                               // dd($insert);
				if(!empty($insert)){

					\DB::table('tpoly_workers')->insert($insert);
 
					// return redirect('/dashboard')->with("success",  " <span style='font-weight:bold;font-size:13px;'>Staff  successfully uploaded!</span> " );
                              

				}

			}

	}
        else{
            //  return redirect('/getStaffCSV')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload file format must be xlx,csv,xslx!</span> ");
                             
        }
       }
		 
       }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,  SystemController $sys,Request $request)
    {
        //
        if($request->user()->isSupperAdmin || @\Auth::user()->department=="top"|| @\Auth::user()->role=="HOD" || @\Auth::user()->role=="Support" ||   @\Auth::user()->role=="Dean"){
              
               $query = StudentModel::where('ID', $id)->where('STATUS','In school')->first();
               
         }
         else{  
        $query = StudentModel::where('ID', $id)->whereHas('programme', function($q) {
            $q->whereHas('departments', function($q) {
                $q->whereIn('DEPTCODE', array(@\Auth::user()->department));
            });
        })->first();
       
         }
         $region=$sys->getRegions();
        
        
        // make sure only students who are currently in school can update their data
         $programme=$sys->getProgramList();
        $hall=$sys->getHalls();
        $religion=$sys->getReligion();
        return view('students.edit')->with('data', $query)
            ->with('programme', $programme)
            ->with('country', $sys->getCountry())
            ->with('region', $region)
            ->with('hall',$hall)
            ->with('religion',$religion);
    }
public function gad()
    {
        //
        return view('autocomplete');
    }

    public function updateLevel()
    {
        $students=  StudentModel::query()->where('level'," ")->get();
            
         foreach ($students as $key => $row) {
              //$student= new StudentModel();
                  $indexno=$row->INDEXNO;
                 
                  $level= substr($indexno, 2,2);
                   //dd($level);
                  if($level=='15'){
                      StudentModel::where('INDEXNO','LIKE','0715%')->update(array("LEVEL"=>'100',"YEAR"=>'1'));
                  }
                  elseif($level=='14'){
                      
                      StudentModel::where('INDEXNO','LIKE','0714%')->update(array("LEVEL"=>'200',"YEAR"=>'2'));
                      
                  }
                  elseif($level=='13'){
                         
                       StudentModel::where('INDEXNO','LIKE','0713%')->update(array("LEVEL"=>'300',"YEAR"=>'3'));
                  }
                  else{
                         
                        //StudentModel::where('LEVEL','=','')->update(array("STATUS"=>'Alumni'));
                  }
               
         }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, SystemController $sys)
    {
        if($request->user()->isSupperAdmin || @\Auth::user()->role=="HOD" || @\Auth::user()->role=="Dean"||@\Auth::user()->department=="top" || @\Auth::user()->role=="Support"){
        {
       set_time_limit(36000);
        /*transaction is used here so that any errror rolls
         *  back the whole process and prevents any inserts or updates
         */

  \DB::beginTransaction();
         $year=$request->input('year');
        if($year=='1'){
            $level='100';
        }
        elseif($year=='2'){
            $level='200';
        }
         elseif($year=='3'){
            $level='300';
        }
        elseif($year=='4'){
            $level='400';
        }
        else{
           $level= $year;
        }
        
        $indexno=$request->input('indexno');
     
        $program=$request->input('programme');
        $gender=$request->input('gender');
        $category=$request->input('category');
        $hostel=$request->input('hostel');
        $hall=$request->input('halls');
        $dob=$request->input('dob');
        $gname=$request->input('gname');
        $gphone=$request->input('gphone');
        $goccupation=$request->input('goccupation');
        $gaddress=$request->input('gaddress');
        $email=$request->input('email');
        $phone=$request->input('phone');
        $marital_status=$request->input('marital_status');
        $region=$request->input('region');
        $country=$request->input('nationality');
        $religion=$request->input('religion');
        $residentAddress=$request->input('contact');
        $address=$request->input('address');
        $hometown=$request->input('hometown');
        $nhis=$request->input('nhis');
        $type=$request->input('type');
        $disability=$request->input('disabilty');
        $title=$request->input('title');
        $age=$sys->age($dob,'eu');
        $group=$sys->graduatingGroup($indexno);
        $firstname=$request->input('fname');
        $surname=$request->input('surname');
        $othername=$request->input('othernames');
        if( @\Auth::user()->role=="Support"){
             $query= StudentModel::where("ID",$id)->update(array(
                 "FIRSTNAME"=>$firstname,
                 "SURNAME"=>$surname,
                 "NAME"=>$surname." ".$othername." ".$firstname,
                "OTHERNAMES"=>$othername));
             
        }
        else{
            $array=$sys->getSemYear();
                  
                  $fiscalYear=$array[0]->YEAR;
                   $sem=$array[0]->SEM;
             $bill=$sys->getYearBill($fiscalYear, $level, $program);
         $bill_owing=$sys->getYearBill($fiscalYear, $level, $program);
         $test=@StudentModel::where("ID",$id)->select("BILLS","BILL_OWING","PROGRAMMECODE")->first();
         if(empty($test) || $test->PROGRAMMECODE!=$program)
         {
             $owe=$test->BILL_OWING+ ($bill-$test->BILLS);
              StudentModel::where("ID",$id)->update(array(
                 "BILLS"=>$bill,
                  "BILL_OWING"=>$owe
                  ));
         }
        $query= StudentModel::where("ID",$id)->update(array(
                 "FIRSTNAME"=>strtoupper($firstname),
                 "SURNAME"=>strtoupper($surname),
                 "NAME"=>strtoupper($surname." ".$othername." ".$firstname),
                "OTHERNAMES"=>strtoupper($othername),
                "TITLE"=>strtoupper($title),
                 "SEX"=>strtoupper($gender),
                 "DATEOFBIRTH"=>$dob,
                 "AGE"=>$age,
                 "GRADUATING_GROUP"=>$group,
                 "MARITAL_STATUS"=>strtoupper($marital_status),
                 "HALL"=>strtoupper($hall),
                 "ADDRESS"=>strtoupper($address),
                 "RESIDENTIAL_ADDRESS"=>strtoupper($residentAddress),
                 "EMAIL"=>strtoupper($email),
                 "TELEPHONENO"=>$phone,
                 "COUNTRY"=>strtoupper($country),
                 "REGION"=>strtoupper($region),
                 "RELIGION"=>strtoupper($religion),
                 "HOMETOWN"=>strtoupper($hometown),
                 "GUARDIAN_NAME"=>strtoupper($gname),
                 "GUARDIAN_ADDRESS"=>strtoupper($gaddress),
                 "GUARDIAN_PHONE"=>$gphone,  
                 "GUARDIAN_OCCUPATION"=>strtoupper($goccupation),
                 "DISABILITY"=>strtoupper($disability),
                "PROGRAMMECODE"=>strtoupper($program),
                 "STATUS"=>"In School",
                 "NHIS"=>$nhis,
                 "STUDENT_TYPE"=>strtoupper($type),
                 "TYPE"=>strtoupper($category),
                 "HOSTEL"=>$hostel,
                  
                 "SYSUPDATE"=>"1",
            
            
                ));
        }
     \DB::commit();
         if(!$query){
            return redirect("/students")->withErrors("  N<u>o</u> :<span style='font-weight:bold;font-size:13px;'> data</span>could not be updated!");
          }else{
                 Models\PortalPasswordModel::where("username",$indexno)->update(array("level"=>$level,"program"=>$program));
        
           \DB::commit();
           Models\FeePaymentModel::where("INDEXNO",$indexno)->where("YEAR",$fiscalYear)->where("SEMESTER",$sem)->update(array("LEVEL"=>$level,"PROGRAMME"=>$program));
         \DB::commit();
           return redirect("/students")->with("success"," <span style='font-weight:bold;font-size:13px;'>data successfully updated!</span> ");
              
        }}}
           else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    public function showUploadForm() {
        return view("students.upload");
    }
    public function applicantUploadForm() {
         return view("students.applicantUpload");
    }
    /*
     * upload continuing students 
     */
    public function uploadData(Request $request, SystemController $sys) {
        if($request->user()->isSupperAdmin || @\Auth::user()->role=="HOD" || @\Auth::user()->department=="top" ){
        {
        set_time_limit(36000);

         
            $user = \Auth::user()->id;
            $valid_exts = array('csv', 'xls', 'xlsx'); // valid extensions
            $file = $request->file('file');
            $path = $request->file('file')->getRealPath();

            $ext = strtolower($file->getClientOriginalExtension());
            
            if (in_array($ext, $valid_exts)) {

                $data = Excel::load($path, function($reader) {
                            
                        })->get();

                if (!empty($data) && $data->count()) {
                    foreach ($data as $key => $value) {

                        $num = count($data);
 
                        $indexno =$value->indexno;
                        $surname= $value->surname;
                        $othername = $value->othernames;
                        $name = $surname." ".$othername;
                        $program = $value->programme;
                        $gender = $value->gender;
                        $title= $value->title;
                        $dob =$value->dob;
                        $waec = $value->waec_indexno;
                        
                        $class = $value->class;
                        $stuType = $value->student_type;
                        
                        $hometown = $value->hometown;
                        
                        $phone = $value->phone;
                        
                        $house = $value->house;
                        
                        $contact = $value->contact_address;
                         
                        $gname = $value->guardian_name;
                        $gphone = $value->guardian_phone;
                        $grelation = $value->guardian_relationship;
                        $gaddress = $value->guardian_address;
                        $gocupation = $value->guardian_occupation;
                        $status ="In school";
                        
                        $group = @$sys->graduatingGroup($indexno);
                        //dd($dob);
                       // $bill = $value->bill;
                       // $owing = $value->owing;
//                        $programme = $sys->programmeSearchByCode(); // check if the programmes in the file tally wat is in the db
//                        if (array_search($program, $programme)) {

                            $testQuery = StudentModel::where('indexNo', $indexno)->first();
                            if (empty($testQuery)) {


                                $student = new StudentModel();
                                $student->indexNo = $indexno;
                                $student->waecIndexNo = $waec;
                                $student->surname = $surname;
                                $student->othernames = $othername;
                                $student->name = $name;
                                $student->title = $title;
                                $student->gender =$gender;
                                $student->dob =$dob;
                                $student->age = $sys->age($value->dob, 'eu');
                                $student->title =$title;
                                $student->studentType =$stuType;
                                 
                                
                                $student->currentClass =$class;
                                $student->hometown =$hometown;
                                $student->address =$contact;
                                $student->phone =$phone;
                               
                               
                                $student->programme =@$sys->getProgramCode($program);
                                $student->house =$house;
                                 
                                $student->parentName =$gname;
                                $student->parentPhone =$gphone;
                                $student->parentAddress=$gaddress;
                                $student->parentOccupation =$gocupation;
                                $student->parentRelation =$grelation;
                                $student->status =$status;
                                $student->yearGroup =$group;
                                 
                                $student->sysUpdate = "1";
                                
                                $student->save();
                                \DB::commit();
                            } else {
                                       return redirect('/upload/students')->with("error", " <span style='font-weight:bold;font-size:13px;'>Some student(s) already exist(s) in the system!</span> ");
           

                            }
//                        } else {
//                            return redirect('/upload/students')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognize programme.please try again!</span> ");
//                        }
                    }
                     return redirect('/students')->with("success", " <span style='font-weight:bold;font-size:13px;'>$num student(s) uploaded  successfully!</span> ");
           
                } else {
                    return redirect('/upload/students')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload a excel file!</span> ");
                }


                } else {
                return redirect('/upload/students')->with("error", " <span style='font-weight:bold;font-size:13px;'>Only excel file is accepted!</span> ");
            }
        }
        
       }
        else{
            return redirect("/dashboard");
        }
        
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
