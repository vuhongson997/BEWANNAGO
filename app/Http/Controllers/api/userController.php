<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TGuest;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\HostResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image as InterventionImage;
//job queue
use Illuminate\Support\Facades\Log;
use App\Jobs\SendWelcomeEmail;
use App\Jobs\sendResetPass  ;
class userController extends Controller
{
   
    //signin
    public function signIn(Request $request){
        $login = [
            'email'=>$request->emailAddress,
            'password'=>$request->password
        ];
        if (!$token = auth('api')->attempt($login)) {
            // if the credentials are wrong we send an unauthorized error in json format
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json([
            'accessToken' => $token, //token login
            'type' => 'Bearer', // you can ommit this
            'expires' => auth('api')->factory()->getTTL() * 60, // time to expiration
            
        ]);
    }

    //sign up account
    public function signUp(Request $request){
        try
        {
            $count = TGuest::where('email',$request->emailAddress)->count();
            if ($count == 0){
            $reponse = TGuest::firstOrCreate([
                'email'=>$request->emailAddress,
                'name'=>$request->name,
                'phone'=>$request->phone,
                'password'=>bcrypt($request->password),
                'avatar'=>($request->hasFile('avatar'))?$this->upload($request->file('avatar'),'guest/'):'https://wannago.cf/storage/users/default.png'

            ]);
                // add Email in queue job when success
                dispatch(new SendWelcomeEmail($reponse));
                //response data 
                return response()->json([
                    'userId'=>$reponse->guest_id,
                    'name'=>$reponse->name,
                    'avatar'=>$reponse->avatar,
                    'emailAddress'=>$reponse->email,
                    'phone'=>$reponse->phone
                ], 201);
            } else {
                return response()->json(['status'=>'Email ton tai'], 400 );
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 0, 'message' => $e->getMessage),400);
        }   
    }
    //response info guest
    public function infoUser(){
        $data = [
            'userId' => auth('api')->user()->guest_id,
            'name' => auth('api')->user()->name,
            'emailAddress' => auth('api')->user()->email,
            'phone' =>auth('api')->user()->phone,
            'avatar'=>auth('api')->user()->avatar
        ];
        return response()->json($data, 200);
    }

    public function update(Request $request){
           try
           {
        
                $reponse = TGuest::where('guest_id',$request->userId)->where('email',$request->emailAddress)->update([
                    'name'=>$request->name,
                    'phone'=>$request->phone,
                    
                ]);
                if($request->hasFile('avatar')){
                    $reponse = TGuest::where('guest_id',$request->userId)->where('email',$request->emailAddress)->update([
                        'avatar'=>$this->upload($request->file('avatar'),'guest/')
                        
                    ]);
                }
                 
                if($reponse!=0){
                    $data= TGuest::where('email',$request->emailAddress)->first();
                        return response()->json([
                                    'userId'=>$data->guest_id,
                                    'name'=>$data->name,
                                    'avatar'=>$data->avatar,
                                    'emailAddress'=>$data->email,
                                    'phone'=>$data->phone
                                ], 200);
                }else{
                    return response()->json(['error data'], 500);
                }
            } catch (Exception $e) {
                return response()->json(array('status' => 0, 'message' => $e->getMessage),400);
            }   
    }


    public function getHostInfo($id){
        $data = User::where('id',$id)->first();
        return response()->json([
            'avatarUrl'=>$data->avatar,
            'signInDate'=> date("Y/m/d",strtotime($data->created_at)),
            'hostId' =>$data->id,
            'hostName'=>$data->name,
            'phoneNumber'=>$data->phone
        ], 200);
    }

    public function resetPassword(Request $request){
        try
           {
                $check= TGuest::where('email',$request->emailAddress)->count();
                if($check>0){
                    $secret = $this->RandomString();
                    TGuest::where('email',$request->emailAddress)->update(['token'=>$secret]);
                    $data=[
                        'email'=>$request->emailAddress,
                        'secret'=>$secret
                    ];
                    dispatch(new sendResetPass($data));
                    return response()->json(['status'=>1]); 
                }else{
                    return response()->json(['status'=>0]);
                }
        } catch (Exception $e) {
            return response()->json(array('status' => 0, 'message' => $e->getMessage),400);
        }  
    }

    public function changepass(Request $request){ 
        
        $check= TGuest::where('email',$request->emailAddress)->where('token',$request->secret)->count();
        if($check>0){
            try{
                TGuest::where('email',$request->emailAddress)->update([
                    'token'=>null,
                    'password'=>bcrypt($request->password)
                ]);
                return response()->json(array('status' => 1, 'message' => 'Đã thay đổi mật khẩu thành công'),200);
            } catch (Exception $e) {
                return response()->json(array('status' => 0, 'message' => $e->getMessage),400);
            }     
        
        }else{
            return response()->json(array('status' => 0, 'message' => "Thất bại"),400);
        }
    }

   

    public function test(Request $request){
        
        $data = [
            'email'=>$request->email,
            'name'=>$request->name
        ];
        // for($i=1;$i<50;$i++){
            dispatch(new SendWelcomeEmail($data));
        // };
    
        return json_encode('ok');
    }
    //==================================================

   public function RandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


        public function upload($fileUpload,$path){
            if ($fileUpload) {
                $file = $fileUpload;

            

                $filename = $this->generateFileName($file, $path);

                $image = InterventionImage::make($file);

                $fullPath = $path.$filename.'.'.$file->getClientOriginalExtension();
                $fullPath1 = asset('storage').'/'.$path.$filename.'.'.$file->getClientOriginalExtension();

                $resize_width = null;
                $resize_height = null;
                if (isset($this->options->resize) && (
                        isset($this->options->resize->width) || isset($this->options->resize->height)
                    )) {
                    if (isset($this->options->resize->width)) {
                        $resize_width = $this->options->resize->width;
                    }
                    if (isset($this->options->resize->height)) {
                        $resize_height = $this->options->resize->height;
                    }
                } else {
                    $resize_width = $image->width();
                    $resize_height = $image->height();
                }

                $resize_quality = isset($this->options->quality) ? intval($this->options->quality) : 75;

                $image = $image->resize(
                    $resize_width,
                    $resize_height,
                    function (Constraint $constraint) {
                        $constraint->aspectRatio();
                        if (isset($this->options->upsize) && !$this->options->upsize) {
                            $constraint->upsize();
                        }
                    }
                )->encode($file->getClientOriginalExtension(), $resize_quality);

                if ($this->is_animated_gif($file)) {
                    Storage::disk(config('voyager.storage.disk'))->put($fullPath, file_get_contents($file), 'public');
                    $fullPathStatic = $path.$filename.'-static.'.$file->getClientOriginalExtension();
                    Storage::disk(config('voyager.storage.disk'))->put($fullPathStatic, (string) $image, 'public');
                } else {
                    Storage::disk(config('voyager.storage.disk'))->put($fullPath, (string) $image, 'public');
                }

                if (isset($this->options->thumbnails)) {
                    foreach ($this->options->thumbnails as $thumbnails) {
                        if (isset($thumbnails->name) && isset($thumbnails->scale)) {
                            $scale = intval($thumbnails->scale) / 100;
                            $thumb_resize_width = $resize_width;
                            $thumb_resize_height = $resize_height;

                            if ($thumb_resize_width != null && $thumb_resize_width != 'null') {
                                $thumb_resize_width = intval($thumb_resize_width * $scale);
                            }

                            if ($thumb_resize_height != null && $thumb_resize_height != 'null') {
                                $thumb_resize_height = intval($thumb_resize_height * $scale);
                            }

                            $image = InterventionImage::make($file)->resize(
                                $thumb_resize_width,
                                $thumb_resize_height,
                                function (Constraint $constraint) {
                                    $constraint->aspectRatio();
                                    if (isset($this->options->upsize) && !$this->options->upsize) {
                                        $constraint->upsize();
                                    }
                                }
                            )->encode($file->getClientOriginalExtension(), $resize_quality);
                        } elseif (isset($thumbnails->crop->width) && isset($thumbnails->crop->height)) {
                            $crop_width = $thumbnails->crop->width;
                            $crop_height = $thumbnails->crop->height;
                            $image = InterventionImage::make($file)
                                ->fit($crop_width, $crop_height)
                                ->encode($file->getClientOriginalExtension(), $resize_quality);
                        }

                        Storage::disk(config('voyager.storage.disk'))->put(
                            $path.$filename.'-'.$thumbnails->name.'.'.$file->getClientOriginalExtension(),
                            (string) $image,
                            'public'
                        );
                    }
                }

                return $fullPath1;
            }

        }


     /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param $path
     *
     * @return string
     */
    protected function generateFileName($file, $path)
    {
        if (isset($this->options->preserveFileUploadName) && $this->options->preserveFileUploadName) {
            $filename = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension());
            $filename_counter = 1;

            // Make sure the filename does not exist, if it does make sure to add a number to the end 1, 2, 3, etc...
            while (Storage::disk(config('voyager.storage.disk'))->exists($path.$filename.'.'.$file->getClientOriginalExtension())) {
                $filename = basename($file->getClientOriginalName(), '.'.$file->getClientOriginalExtension()).(string) ($filename_counter++);
            }
        } else {
            $filename = Str::random(20);

            // Make sure the filename does not exist, if it does, just regenerate
            while (Storage::disk(config('voyager.storage.disk'))->exists($path.$filename.'.'.$file->getClientOriginalExtension())) {
                $filename = Str::random(20);
            }
        }

        return $filename;
    }
    private function is_animated_gif($filename)
    {
        $raw = file_get_contents($filename);

        $offset = 0;
        $frames = 0;
        while ($frames < 2) {
            $where1 = strpos($raw, "\x00\x21\xF9\x04", $offset);
            if ($where1 === false) {
                break;
            } else {
                $offset = $where1 + 1;
                $where2 = strpos($raw, "\x00\x2C", $offset);
                if ($where2 === false) {
                    break;
                } else {
                    if ($where1 + 8 == $where2) {
                        $frames++;
                    }
                    $offset = $where2 + 1;
                }
            }
        }

        return $frames > 1;
    }

}
