<?php
   
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Auth;
use App\RewardUsers;
use App\RewardUsersLoginSessions;
use App\RewardAllrewards;
use Illuminate\Support\Facades\Validator;
use Response;
use App\RewardContactus;
use App\RewardTasks;
use App\RewardRequest;
   
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome()
    {
		$total_users = RewardUsers::select('id')->count();
		$total_active = RewardUsersLoginSessions::where('token_status','=','1')->count();
		
        return view('adminHome')->with(['total_users'=>$total_users,'total_activeusers'=>$total_active]);
    }
	
	public function adminLogout(){
		Auth::logout();
		return redirect('/login');
	}
	
	#get all user list
	public function allUsers(){
		
		try{
			$all_users = RewardUsers::get()->toArray();
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
		
		return view('allUsers')->with(['all_users'=>$all_users]);
		
	}
	
	#fn to get user details
	public function userDetail($id){
		
		try{
			$user_details = RewardUsers::with(['getUserRewards','getUserRewards.getRewardDetail'])->where('id','=',$id)->first();
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
		
		return view('userDetail')->with(['user_details'=>$user_details]);
		
	}
	
	#get all reward list
	public function allRewards(){
		try{
			$all_rewards = RewardAllrewards::get()->toArray();
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
		
		return view('allRewards')->with(['all_rewards'=>$all_rewards]);
	}
	
	#fn to add or edit the rewards
	public function submitReward(Request $request){
		
		//validations
		$userData = array( 'title'=> $request->title,'description'=> $request->description,'coins'=> $request->coins,'legal_text'=> $request->legal_text);
		$rules = array('title' => 'required','description' => 'required','coins'=>'required|numeric','legal_text'=>'required',);
		$validator = Validator::make($userData,$rules);
		#if validation error 
		if($validator->fails()){
			$main_errors = $validator->getMessageBag()->toArray();
 
			$errors = array();
			foreach($main_errors as $key=>$value)
			{
				
				if($key == "title")
				{
					$main_errors[$key][0] = $value;
				}
				if($key == "description")
				{
					$main_errors[$key][0] = $value;
				}
				if($key == "coins")
				{
					$main_errors[$key][0] = $value;
				}
				if($key == "legal_text")
				{
					$main_errors[$key][0] = $value;
				}
				
				return back()->with(['error'=>$value[0]]);
				
				
			}
		}#if no validation error then save the user
		else{
			
			$type = $request->form_type;
			if($type == "add"){
				#inset into the table
				
				$icon = $request->file('icon');
			
				$profileImageSaveAsName = time() . "-profile." . $icon->getClientOriginalExtension();
				$imgname = 'r_'.$profileImageSaveAsName;
				$upload_path = public_path()."\images\icon_reward";
				
				$profile_image_url = $upload_path .'\r_'. $profileImageSaveAsName;
				$success = $icon->move($upload_path, $imgname);
				
				$t=time();
				$date = date("Y-m-d",$t);
				try{
	 
					$id = RewardAllrewards::insertGetId([
						'admin_id'=>1,
						'reward_title'=>$request->title,
						'reward_description' =>$request->description, 
						'reward_coins'=>$request->coins, 
						'reward_icons'=> $imgname,
						'legal_text'=>$request->legal_text,
						'created_at'=>$date,
						'updated_at'=>$date,
					]);
				}catch(\Exception $e){
					return back()->with(['error'=>$e->getMessage()]);
				}
				
				try{
					$all_rewards = RewardAllrewards::get()->toArray();
				}catch(\Exception $e){
					return back()->with(['error'=>$e->getMessage()]);
				}
				
				return redirect('rewards')->with(['all_rewards'=>$all_rewards,'success'=>'Reward Added Successfully']);
			}else{
				$reward_id = $request->reward_id;
				if($reward_id == ''){
					return back()->with(['error'=>'Please try again!!']);
				}else{
					
					$icon = $request->file('icon');
			
					$profileImageSaveAsName = time() . "-profile." . $icon->getClientOriginalExtension();
					$imgname = 'r_'.$profileImageSaveAsName;
					$upload_path = public_path()."\images\icon_reward";
					
					$profile_image_url = $upload_path .'\r_'. $profileImageSaveAsName;
					$success = $icon->move($upload_path, $imgname);
					
					
					$update_array = array( 'reward_title'=> $request->title,'reward_description'=> $request->description,'reward_coins'=> $request->coins,'reward_icons'=> $imgname,'legal_text'=> $request->legal_text);
					
					$update = RewardAllrewards::where('id','=',$reward_id)->update($update_array);
					if($update == 1){
						try{
							$all_rewards = RewardAllrewards::get()->toArray();
						}catch(\Exception $e){
							return back()->with(['error'=>$e->getMessage()]);
						}
						
						return redirect('rewards')->with(['all_rewards'=>$all_rewards,'success'=>'Reward Updated Successfully']);
					}
				}
			}
			
			
			
		}#else validation ends 
				
	}#fn ends to add or edit rewards
	
	#fn to open edit rewards page
	public function editReward($id){
		
		if($id == ''){
			return redirect('rewards')->with(['error'=>'Please try again!!']);
		}
		
		#check if reward exists or not
		try{
			$check_reward = RewardAllrewards::where('id','=',$id)->first();
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
		
		if(!empty($check_reward)){
			return view('editReward')->with(['reward_data'=>$check_reward]);
		}else{
			return back()->with(['error'=>'Please try again!!']);
		}
		
	}#end edot reward
	
	#fn to delete rewards
	public function deleteReward($id){
		if($id == ''){
			return redirect('rewards')->with(['error'=>'Please try again!!']);
		}
		
		#check if reward exists or not
		try{
			$check_reward = RewardAllrewards::where('id','=',$id)->first();
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
		
		if(!empty($check_reward)){
			try{
				RewardAllrewards::where('id','=',$id)->delete();
			}catch(\Exception $e){
				return back()->with(['error'=>$e->getMessage()]);
			}
			return redirect()->back()->with(['success'=>'Reward Deleted Successfully']);
		}else{
			return back()->with(['error'=>'Please try again!!']);
		}
	}#dlt rewards ends here
	
	#fn to get all contact us questions and emails
	public function contactUs(){
		
		try{
			$all_msg = RewardContactus::get()->toArray();
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
		return view('contactUs')->with(['all_msg'=>$all_msg]);
	}#contact fn ends here
	
	#fn to get list of all tasks
	public function tasks(){
		
		try{
			$get_tasks = RewardTasks::get()->toArray();
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
		
		return view('allTasks')->with(['get_tasks'=>$get_tasks]);
	}#all task list ends here
	
	#fn to add or edit the task
	public function submitTask(Request $request){
		
		//validations
		$userData = array( 'title'=> $request->title,'description'=> $request->description,'coins'=> $request->coins,'repeat'=> $request->repeat);
		$rules = array('title' => 'required','description' => 'required','coins'=>'required|numeric','repeat'=>'required',);
		$validator = Validator::make($userData,$rules);
		#if validation error 
		if($validator->fails()){
			$main_errors = $validator->getMessageBag()->toArray();
 
			$errors = array();
			foreach($main_errors as $key=>$value)
			{
				
				if($key == "title")
				{
					$main_errors[$key][0] = $value;
				}
				if($key == "description")
				{
					$main_errors[$key][0] = $value;
				}
				if($key == "coins")
				{
					$main_errors[$key][0] = $value;
				}
				if($key == "repeat")
				{
					$main_errors[$key][0] = $value;
				}
				
				return back()->with(['error'=>$value[0]]);
				
				
			}
		}#if no validation error then save the user
		else{
			
			$type = $request->form_type;
			if($type == "add"){
				#inset into the table
				
				$icon = $request->file('icon');
			
				$profileImageSaveAsName = time() . "-profile." . $icon->getClientOriginalExtension();
				$imgname = 't_'.$profileImageSaveAsName;
				$upload_path = public_path()."\images\icon_task";
				
				$profile_image_url = $upload_path .'\t_'. $profileImageSaveAsName;
				
				$success = $icon->move($upload_path, $imgname);
			
				$t=time();
				$date = date("Y-m-d",$t);
				try{
	 
					$id = RewardTasks::insertGetId([
						'title'=>$request->title,
						'description' =>$request->description, 
						'coins'=>$request->coins, 
						'icon'=>$imgname,
						'repeat'=>$request->legal_text,
						'created_at'=>$date,
						'updated_at'=>$date,
					]);
				}catch(\Exception $e){
					return back()->with(['error'=>$e->getMessage()]);
				}
				
				try{
					$all_tasks = RewardTasks::get()->toArray();
				}catch(\Exception $e){
					return back()->with(['error'=>$e->getMessage()]);
				}
				
				return redirect('tasks')->with(['get_tasks'=>$all_tasks,'success'=>'Task Added Successfully']);
			}else{
				 
				$task_id = $request->task_id;
				$icon = $request->file('icon');
			
				$profileImageSaveAsName = time() . "-profile." . $icon->getClientOriginalExtension();
				$imgname = 't_'.$profileImageSaveAsName;
				$upload_path = public_path()."\images\icon_task";
				
				$profile_image_url = $upload_path .'\t_'. $profileImageSaveAsName;
				
				$success = $icon->move($upload_path, $imgname);
			
				if($task_id == ''){
					return back()->with(['error'=>'Please try again!!']);
				}else{
					$update_array = array( 'title'=> $request->title,'description'=> $request->description,'coins'=> $request->coins,'icon'=>$imgname,'repeat'=> $request->repeat);
					
					$update = RewardTasks::where('id','=',$task_id)->update($update_array);
					if($update == 1){
						try{
							$all_tasks = RewardTasks::get()->toArray();
						}catch(\Exception $e){
							return back()->with(['error'=>$e->getMessage()]);
						}
						
						return redirect('tasks')->with(['get_tasks'=>$all_tasks,'success'=>'Task Updated Successfully']);
					}
				}
			}
			
			
			
		}#else validation ends 
				
	}#fn ends to add or edit rewards
	
	#fn to open edit task page
	public function editTask($id){
		
		if($id == ''){
			return redirect('tasks')->with(['error'=>'Please try again!!']);
		}
		
		#check if reward exists or not
		try{
			$check_task = RewardTasks::where('id','=',$id)->first();
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
		
		if(!empty($check_task)){
			return view('editTask')->with(['task_data'=>$check_task]);
		}else{
			return back()->with(['error'=>'Please try again!!']);
		}
		
	}#end edot task
	
	#fn to delete tasks
	public function deleteTask($id){
		if($id == ''){
			return redirect('tasks')->with(['error'=>'Please try again!!']);
		}
		
		#check if reward exists or not
		try{
			$check_task = RewardTasks::where('id','=',$id)->first();
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
		
		if(!empty($check_task)){
			try{
				RewardTasks::where('id','=',$id)->delete();
			}catch(\Exception $e){
				return back()->with(['error'=>$e->getMessage()]);
			}
			return redirect()->back()->with(['success'=>'Task Deleted Successfully']);
		}else{
			return back()->with(['error'=>'Please try again!!']);
		}
	}#dlt rewards ends here
	
	#fn to get reward request
	public function rewardRequest(){
		
		try{
			$reward_req = RewardRequest::with(['getUser','getReward'])->get()->toArray();
			
			return view('rewardRequest')->with(['reward_req'=>$reward_req]);
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
	}
	
	#Approve reward request
	public function rewardRequestAppr($id){
		if($id == ''){
			return redirect('reward_request')->with(['error'=>'Please try again!!']);
		}
		
		#check if reward exists or not
		try{
			$check_req = RewardRequest::where('id','=',$id)->first();
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
		
		if(!empty($check_req)){
			try{
				RewardRequest::where('id','=',$id)->update(['reward_status'=>1]);
			}catch(\Exception $e){
				return back()->with(['error'=>$e->getMessage()]);
			}
			return redirect()->back()->with(['success'=>'Request Approved Successfully']);
		}else{
			return back()->with(['error'=>'Please try again!!']);
		}
	}
	
	#deny reward request
	public function rewardRequestDeny($id){
		if($id == ''){
			return redirect('reward_request')->with(['error'=>'Please try again!!']);
		}
		
		#check if reward exists or not
		try{
			$check_req = RewardRequest::where('id','=',$id)->first();
		}catch(\Exception $e){
			return back()->with(['error'=>$e->getMessage()]);
		}
		
		if(!empty($check_req)){
			try{
				RewardRequest::where('id','=',$id)->update(['reward_status'=>2]);
			}catch(\Exception $e){
				return back()->with(['error'=>$e->getMessage()]);
			}
			return redirect()->back()->with(['success'=>'Request Deny Successfully']);
		}else{
			return back()->with(['error'=>'Please try again!!']);
		}
	}
    
}