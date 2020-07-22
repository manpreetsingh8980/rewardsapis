<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Response;
use App\RewardUsers;
use App\RewardUsersLoginSessions;
use App\RewardUsersRewards;
use App\RewardAllrewards;
use App\RewardContactus;
use App\RewardSurvey;
use Str;

class ApiController extends BaseController
{
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	public function __construct(Request $header_request){
       
        $this->device_type      = strtoupper($header_request->header('device-type'));
        $this->device_token     = $header_request->header('device-token');
        $this->header_api_token = $header_request->header('api-token');
    }    
	
	/****get data for dashboard****/
	public function dashboard(Request $request){
		
		$device_type = $request->get('device_type');
		if($device_type == ''){
			return Response::json(['success' => '0','message'=>'Please provide device type in url'],200);
		}
		
		$device_token = $request->get('device_token');
		if($device_token == ''){
			return Response::json(['success' => '0','message'=>'Please provide device token in url'],200);
		}
		
		$device_id = $request->get('device_id');
		if($device_id == ''){
			return Response::json(['success' => '0','message'=>'Please provide device id in url'],200);
		}
		
		#check if user alreday exists
		try{
			$check_reward_user = RewardUsers::select('id')->where(['device_token'=>$device_token,'device_id'=>$device_id])->first();
		}catch(\Exception $e){
			return Response::json(['success' => '0','message'=>$e->getMessage()],200);
		}
		if(empty($check_reward_user)){
			try{
				$reward_user_id = RewardUsers::insertGetId([
									'admin_id' => 1,
									'device_type' => $device_type,
									'device_token'=>$device_token,
									'device_id'=> $device_id,
									'datetime'=>strtotime("now")
									]);
			}catch(\Exception $e){
				return Response::json(['success' => '0','message'=>$e->getMessage()],200);
			}
			$reward_userid = (string)$reward_user_id;
		}else{
			$reward_userid = (string)$check_reward_user->id;
		}
		
		
		
		$api_token = Str::random(60);
		try{
			RewardUsersLoginSessions::insert([
						'reward_user_id' => $reward_userid,
						'api_token' => $api_token,
						'token_status'=>1
						]);
		}catch(\Exception $e){
			return Response::json(['success' => '0','message'=>$e->getMessage()],200);
		}
		
		$data = array('api_token'=>$api_token,'reward_user_id'=>(string)$reward_userid);
		return Response::json(['success'=>'1','message'=>'Login Successful.','data'=>$data ],200);exit;
		
	}/****dashboard fn ends here*****/
	
	/*****fn to get rewards of a user************/
	public function getRewardsUser(Request $request){
		
		if( $this->header_api_token==''){
            return Response::json(['success' => '0','message'=>'Please provide api-token in headers'],200);
        }
		
		#get user id from the login token $this->header_api_token
		try{
 
            $check_login = RewardUsersLoginSessions::select('reward_user_id')->where(['api_token'=>$this->header_api_token,'token_status'=>1])->first();
					   
			if(!empty($check_login)){
 
                $user_id = $check_login->reward_user_id;
				
				$get_user_rewards = RewardUsersRewards::with('getRewardDetail')->where(['reward_user_id'=>$user_id,'reward_status'=>1])->get()->toArray();
				
				if(!empty($get_user_rewards)){
					return Response::json(['success' => '1','message'=>'Get all rewards of a user.','data'=>$get_user_rewards],200);
				}else{
					return Response::json(['success' => '0','message'=>'No reward found.'],200);
				}
				//$allrewards = array();
				//foreach($get_user_rewards as $key=>$value){
				//	$allrewards['allrewards'][] = $value['get_reward_detail']['reward_title'];
				//}
				//echo "<pre>";print_r($allrewards);die;
				
				
            }else{
 
                return Response::json(['success' => '0','message'=>'API Token does not exists.'],200);
            }    			   
 
        }catch(\Exception $e){
 
            return Response::json(['success' => '0','message'=>$e->getMessage()],200);
        }
		
	}/*****fn get rewards ends here****/
	
	/*****fn to get reward details******/
	public function rewardDetails(Request $request,$reward_id){
		
		if($reward_id == '' || $reward_id == null){
			return Response::json(['success' => '0','message'=>'Reward id missing'],200);
		}
		
		if( $this->header_api_token==''){
            return Response::json(['success' => '0','message'=>'Please provide api-token in headers'],200);
        }
		
		#get user id from the login token $this->header_api_token
		try{
 
			$check_login = RewardUsersLoginSessions::select('reward_user_id')->where(['api_token'=>$this->header_api_token,'token_status'=>1])->first();
					   
			if(!empty($check_login)){
 
				$user_id = $check_login->reward_user_id;
				
				$get_reward_detail = RewardAllrewards::where('id','=',$reward_id)->first();
				
				if(!empty($get_reward_detail)){
					return Response::json(['success' => '1','message'=>'Get all rewards of a user.','data'=>$get_reward_detail],200);
				}else{
					return Response::json(['success' => '0','message'=>'No reward found.'],200);
				}
				//$allrewards = array();
				//foreach($get_user_rewards as $key=>$value){
				//	$allrewards['allrewards'][] = $value['get_reward_detail']['reward_title'];
				//}
				//echo "<pre>";print_r($allrewards);die;
				
				
			}else{
 
				return Response::json(['success' => '0','message'=>'API Token does not exists.'],200);
			}    			   
 
		}catch(\Exception $e){
 
			return Response::json(['success' => '0','message'=>$e->getMessage()],200);
		}
		
		
		
	}/*****fn reward detail ends here****/
	
	/*****fn for user detail*******/
	public function userDetail($user_id){
		if($user_id == '' || $user_id == null){
			return Response::json(['success' => '0','message'=>'User id missing'],200);
		}
		
		if( $this->header_api_token==''){
            return Response::json(['success' => '0','message'=>'Please provide api-token in headers'],200);
        }
		
		try{
 
            $check_userid = RewardUsers::where('id',$user_id)->first();
 
        }catch(\Exception $e){
 
            return Response::json(['success' => '0','message'=>$e->getMessage()],200);
        }
		if(!empty($check_userid)){
			#get user id from the login token $this->header_api_token
			try{
	 
				$check_token = RewardUsersLoginSessions::select('reward_user_id')->where(['reward_user_id'=>$user_id,'api_token'=>$this->header_api_token,'token_status'=>1])->get();
						   
				if(count($check_token) > 0){
	 
					
					$get_user_rewards = RewardUsersRewards::with('getRewardDetail')->where('reward_user_id','=',$user_id)->get()->toArray();
				
					if(!empty($get_user_rewards)){
						return Response::json(['success' => '1','message'=>'Get all rewards of a user.','data'=>$get_user_rewards],200);
					}else{
						return Response::json(['success' => '0','message'=>'No reward found.'],200);
					}
					//$allrewards = array();
					//foreach($get_user_rewards as $key=>$value){
					//	$allrewards['allrewards'][] = $value['get_reward_detail']['reward_title'];
					//}
					//echo "<pre>";print_r($allrewards);die;
					
					
				}else{
	 
					return Response::json(['success' => '0','message'=>'API Token does not exists.'],200);
				}    			   
	 
			}catch(\Exception $e){
	 
				return Response::json(['success' => '0','message'=>$e->getMessage()],200);
			}
		}else{
			return Response::json(['success' => '0','message'=>'User does not exists.'],200);
		}
		
		
	}/****user details ends here****/
	
	/******Contact us************/
	public function userContactUs(Request $request){
		
		if( $this->header_api_token==''){
            return Response::json(['success' => '0','message'=>'Please provide api-token in headers'],200);
        }
		
		#get user id from the login token $this->header_api_token
		try{
 
			$check_token = RewardUsersLoginSessions::select('reward_user_id')->where(['api_token'=>$this->header_api_token,'token_status'=>1])->first();
					 
			if(!empty($check_token)){
				$user_id = $check_token->reward_user_id;
				
				$email = ((isset($request->email)) ? ($request->email) : '');
		
				if($email == ""){
					return Response::json(['success'=>'0','message'=>'Please provide email.'],200);
					exit;
				}
				
				$question = ((isset($request->question)) ? ($request->question) : '');
		
				if($question == ""){
					return Response::json(['success'=>'0','message'=>'Please provide question.'],200);
					exit;
				}
				
				//validations
				$userData = array( 'email'=> $request->email);
				$rules = array('email' =>'required|email');
				$validator = Validator::make($userData,$rules);
				#if validation error 
				if($validator->fails()){
					$main_errors = $validator->getMessageBag()->toArray();
		 
					$errors = array();
					foreach($main_errors as $key=>$value)
					{
						
						if($key == "email")
						{
							$main_errors[$key][0] = $value;
						}
						
						return Response::json([
							'success' => '0',
							'message' => $value[0]
						],200);
					}
				}#if no validation error then save the user
				else{
					#inset into the table
		 
					try{
		 
						$id = RewardContactus::insertGetId([
							'reward_user_id'=>$user_id,
							'email' =>$request->email, 
							'question'=>$request->question, 
						]);
					}catch(\Exception $e){
						return Response::json(['success' => '0','message'=>$e->getMessage()],200);
					}
		 
					#json final response
					return Response::json(['success'=>'1','message'=>'Question Send Successfully.'],200);
					exit;
				}#validation else ends
				
				
			}else{
 
				return Response::json(['success' => '0','message'=>'API Token does not exists.'],200);
			}    			   
 
		}catch(\Exception $e){
 
			return Response::json(['success' => '0','message'=>$e->getMessage()],200);
		}
		
	}/***fn contact us ends here***/
	
	/******fn to get survey details******/
	public function getSurvey(Request $request){
		
		if( $this->header_api_token==''){
            return Response::json(['success' => '0','message'=>'Please provide api-token in headers'],200);
        }
		
		
		$cURLConnection = curl_init();

		
		curl_setopt($cURLConnection, CURLOPT_URL, 'https://api.adgem.com/v1/wall/json?playerid=zlLfeOcUng8yPGFsG4hStVUsCfqCjyNp&appid=2597&platform=ios');
	//	curl_setopt($cURLConnection, CURLOPT_URL, 'https://api.adgem.com/v1/click?all=1&appid=2597&cid=7313&playerid=zlLfeOcUng8yPGFsG4hStVUsCfqCjyNp');
		
	//	curl_setopt($cURLConnection, CURLOPT_URL, 'https://api.adgem.com/v1/all/campaigns?&appid=2597&token=zlLfeOcUng8yPGFsG4hStVUsCfqCjyNp');
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

		$phoneList = curl_exec($cURLConnection);
		curl_close($cURLConnection);

		$jsonArrayResponse = json_decode($phoneList);
				
		if(!empty($jsonArrayResponse)){
			if($jsonArrayResponse->status == 'success' && !empty($jsonArrayResponse->data)){
				
				foreach($jsonArrayResponse->data as $key=>$value){
					if(!empty($value->data)){
						foreach($value->data as $key1=>$value1){
							
							#check if already exists
							
							try{
								$check_data = RewardSurvey::where([
										'campaign_id' => $value1->campaign_id,
										'icon' => $value1->icon,
										'name'=> $value1->name,
										'url'=> $value1->url,
										'instructions'=> $value1->instructions,
										'description'=> $value1->description,
										'short_description'=> $value1->short_description,
										'amount'=> $value1->amount
									])->get()->toArray();
							}catch(\Exception $e){
								return Response::json(['success' => '0','message'=>$e->getMessage()],200);
							}
							if(empty($check_data)){
								try{
									$add_data = RewardSurvey::insertGetId([
											'campaign_id' => $value1->campaign_id,
											'icon' => $value1->icon,
											'name'=> $value1->name,
											'url'=> $value1->url,
											'instructions'=> $value1->instructions,
											'description'=> $value1->description,
											'short_description'=> $value1->short_description,
											'amount'=> $value1->amount
										]);
								}catch(\Exception $e){
									return Response::json(['success' => '0','message'=>$e->getMessage()],200);
								}
							}
							
							
							
							
							
						}
						return Response::json(['success'=>'1','message'=>'Get data successfully.','data'=>$jsonArrayResponse->data],200);
							exit;
						
					}
					return Response::json(['success' => '0','message'=>'No data Found.'],200);
					
				}
			}
			return Response::json(['success' => '0','message'=>'No data Found.'],200);
			
		}else{
			return Response::json(['success' => '0','message'=>'No data Found.'],200);
		}
		/*$url = 'http://api.hangmytracking.com/api.php';

		$params = array(
			'method' => 'getCategories',
			'apiToken' => '7be22a60b1503fb664ebabfdaac473573faf5164',
			'apiID' => 'SafeMe_Inc._-_APIAPI'
		);
		
		$postData = http_build_query($params);
 
		$ch = curl_init();  
	 
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_HEADER, false); 
		curl_setopt($ch, CURLOPT_POST, count($params));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
	 
		$output=curl_exec($ch);
	 
		curl_close($ch);
		return $output;*/
		//$response = json_decode(httpPost($base_url,$parameters));
		
		//echo "<pre>";print_r($response);die;
	}/*****get survey ends here****/
	
	public function getSurveyDetail($campaign_id){
		if( $this->header_api_token==''){
            return Response::json(['success' => '0','message'=>'Please provide api-token in headers'],200);
        }
		
		if( $campaign_id==''){
            return Response::json(['success' => '0','message'=>'Campaign id is missing'],200);
        }
		
		#check if already exists
							
		try{
			$check_data = RewardSurvey::where('campaign_id',$campaign_id)->get()->toArray();
		}catch(\Exception $e){
			return Response::json(['success' => '0','message'=>$e->getMessage()],200);
		}
		
		if(!empty($check_data)){
			return Response::json(['success'=>'1','message'=>'Get data successfully.','data'=>$check_data],200);
							exit;
		}else{
			return Response::json(['success' => '0','message'=>'Campaign id does not exists.'],200);
		}
	}
	
	
}
