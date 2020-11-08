<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\User;
use DB;
class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:6',
        ]);
        if($v->fails())
        {
            return response()->json([
                'status' => 'error',
                'errors' => $v->errors()
            ]);
        }
        $user = new User;
        $user->email = $request->email;
        $user->user_role = '2';
        $user->password = bcrypt($request->password);
        $user->save();
        $credentials = request(['email', 'password']);
        Auth::attempt($credentials);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();
        return response()->json([
            'status'=>'success',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
        
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
       
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
        {
            return response()->json([
                'error' => 'Credential does not match',
                'status'=>'error'
            ]);
        }
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
        {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
            
        $token->save();
        return response()->json([
            'status'=>'success',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    function follow(Request $request,$id)
    {
        $user=$request->user();
        $user_id=$user->id;
        $row=DB::table('user_follow')->where('user_id',$user_id)->where('cat_id',$id)->first();
        if($row)
        {
            DB::table('user_follow')->where('id',$row->id)->delete();
            $status='0';
        }else{
            $insert=['cat_id'=>$id,
                    'user_id'=>$user_id,
                'created_at'=>date('Y-m-d H:i:s')];
            DB::table('user_follow')->insert($insert);
            $status='1';
        }
        return response()->json(['status'=>$status]);
        
    }
    function checkfollow(Request $request,$id)
    {
        $user=$request->user();
        $user_id=$user->id;
        $row=DB::table('user_follow')->where('user_id',$user_id)->where('cat_id',$id)->first();
        if($row)
        {
            $status='1';
        }else{
            $status='0';
        }
        return response()->json(['status'=>$status]);
        
    }
}
?>