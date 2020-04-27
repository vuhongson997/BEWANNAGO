<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TOccupiedReservation as TOCC;
use App\TStay as Stay;
use App\MCode as Type;
use App\MPlace as Highline;
use App\TRoomGallery as gallery;
use App\TStayRating as Rating;
use App\TAddress as Address;
use App\TStayFavorite as Favorite;
use App\Http\Resources\Search;
use App\Http\Resources\searchResource;
use App\Http\Resources\DetailResource;
use App\Http\Resources\HighlineResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\favoriteResource;
use App\TStayRating as Rate;
use Illuminate\Support\Facades\Log;

class stayController extends Controller
{
    public function index(){
        return 'ok';
    }
    // search
    public function search(Request $request)
    {
        $list = Stay::where('city_id',$_GET['city_id'])->get('stay_id');
        $count =TOCC::orWhereBetween('check_in',[date("Y-m-d", strtotime($_GET['check_in' ])),date("Y-m-d", strtotime($_GET['check_out']))])
        ->orWhereBetween('check_out',[date("Y-m-d", strtotime($_GET['check_in'])),date("Y-m-d", strtotime($_GET['check_out']))])
        ->orWhere('check_in','<=',date("Y-m-d", strtotime($_GET['check_in'])))
        ->Where('check_out','>=',date("Y-m-d", strtotime($_GET['check_out'])))
        ->whereIn('stay_id',$list)
        ->count();
       
        if($count!=0){
            $get =TOCC::orWhereBetween('check_in',[date("Y-m-d", strtotime($_GET['check_in'])),date("Y-m-d", strtotime($_GET['check_out']))])
            ->orWhereBetween('check_out',[date("Y-m-d", strtotime($_GET['check_in'])),date("Y-m-d", strtotime($_GET['check_out']))])
            ->orWhere('check_in','<=',date("Y-m-d", strtotime($_GET['check_in'])))
            ->Where('check_out','>=',date("Y-m-d", strtotime($_GET['check_out'])))->distinct()
            ->get('stay_id');
            $data = Stay::where('city_id',$_GET['city_id'])->whereNotIn('stay_id',$get)->paginate(20);
       
        }else{
            $data = Stay::where('city_id',$_GET['city_id'])->paginate(20);
        }
        return new Search($data);
        
    }
    //get slice
    public function getSlicesByType(){
            
        return response()->json(['status'=>'coming soon'],200);
        
    }

    // get hightline
    public function stayGetHighlightPlaces(){
        $place = Stay::distinct()->get('city_id');
        $data = Highline::whereIn('code_place',$place)->inRandomOrder()->limit(5)->get();
        return HighlineResource::collection($data);
    }
    
    // stay hot
    public function getHotStay(){
        $data = Stay::where('rate_average','>=',4)->inRandomOrder()->limit(10)->get();
        return searchResource::collection($data);
    }

    // get detail
    public function getStayDetail($id){
        $img_url = [];
        $data =Stay::where('stay_id',$id)->first();
        $imgs = gallery::where('stay_id',$data->stay_id)->get();
                foreach($imgs as $img){
                    $img_url= json_decode($img->img_url);
                }
        
        $count = Rate::where('stay_id',$data->stay_id)->count();
        $area = Address::where('stay_id',$data->stay_id)->first();        
        return response()->json([
            'hostId'        => $data->host_id,
            'stayTypeName'  =>$data->type->code_name,          
            
            'stayId'        => $data->stay_id,
            'lng'           => (float)$data->address->lng,
          
            'lat'           => (float)$data->address->lat,
            'stayName'      => $data->stay_name,
            'stayDescription'=>$data->description,
            'areaSquare'    =>(int) $data->address->area,
            'rate'          =>$data->rate_average,
            'ratingCount'   => (int)$count,
            'guestCount' => (int)$data->guest_count,
            'bedCount'   => (int)$data->bed_count,
            'bathCount'  =>(int)$data->bath_count,
            'price'      =>($data->discount)?(int)$data->discount:(int)$data->price,
            'discount'  => ($data->discount)?(int)$data->price:(int)$data->discount,
            'city'      => $data->city->name_place,
            'district' => $area->district_name->name,
            'ward' =>$area->ward->name,
            'street' => $area->street,
            'addressNumber' => $area->address_number,
            'stayUtility'=>[
                'wifi' => $data->wifi==1?true:false,
                'tivi' => $data->smoking==1?true:false,
                'kitchen'=>$data->kitchen==1?true:false,
                'pool'=>$data->pool==1?true:false,
                'refrigerator'=>$data->refrigerator==1?true:false,
                'cooler' =>$data->cooler==1?true:false
            ],
            'imgUrls' => $img_url
        ],200);
        
    }
    
    // get comment
    public function getStayComments($id){
        $data =Rating::where('stay_id',$id)->orderBy('rating_id', 'desc')->get();
        return CommentResource::collection($data);
    }
    // post comments
    public function postStayComment(Request $request){

        $reponse = Rating::create([
            'comment' => $request->comment,
            'stay_id' =>$request->stayId,
            'rate' =>$request->commentRate,
            'guest_id'=>$request->userId
        ]);
        $rate =Rate::where('stay_id',$request->stayId)->avg('rate');
        Stay::where('stay_id',$request->stayId)->update(['rate_average'=>round($rate,1)]);
        return response()->json([
            "userId"=>$reponse->guest_id,
            "stayId" =>$reponse->stay_id,
            "comment" =>$reponse->comment,
            "commentRate"=> $reponse->rate
        ], 200);
        
    }

    // favorite
    public function addFavorite(Request $request){
        $check = Favorite::where('guest_id',$request->userId)->where('stay_id',$request->stayId)->count();
        if($check==0){
        $reponse=Favorite::create([
            'guest_id'=>$request->userId,
            'stay_id'=>$request->stayId
            ]);
        
        return response()->json([
            'favoriteId'=>$reponse->favorite_id,
            'userId'=>$reponse->guest_id,
            'stayId'=>$reponse->stay_id
        ],201);
        }else{
            return response()->json(['status'=>2],400);
        }
            
    }

    public function removeFavorite($id){
        Favorite::where('favorite_id',$id)->delete();
        
        return response()->json(['status'=>1],202);
        
    }

    public function getFavorite($id){
        $data = Favorite::where('guest_id',$id)->get();
        return favoriteResource::collection($data);
    }

    public function getStay()
    {
        $data = Type::where('code_group',$_GET['code_group'])->where('lang',(isset($_GET['lang']))?$_GET['lang']:"VI")->first();

        return response()->json($data, 200);
    }
}
