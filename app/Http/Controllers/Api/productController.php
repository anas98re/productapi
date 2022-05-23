<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Models\product;
use App\Models\discount;
use App\Models\comment;
use App\Models\like;
use App\Models\user;
//use Validator;
use App\Http\Resources\product as productResources;
use App\Http\Controllers\Api\BaseController as BaseController;
// use App\Models\comment;
use Carbon\Carbon;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Token;
use Nette\Utils\Validators;

class productController extends BaseController
{
    // public function __construct()
    // {
    //     $products = product::all();
    //     if($products->pro_expiration_Date==now())
    //     {
    //         $products->delete();
    //     }
    // }
    public function index()
    {
        // $product = product::find('pro_expiration_Date');
        $like=like::all();

        // $count=0;
        $products = product::all(); //send products to mobile
        // $productss = product::withCount(['comments','likes'])->get();
        foreach($products as $product){

                if($product['pro_expiration_Date']<=now()){
                    $product->delete();
              }
        }
        $productss = product::all();

        // $products['countOfLikes']= $productss;
        return $this->sendResponse(productResources::collection($productss), 'all product sent'); //send it as json arrey
        //collection used when we have more then one
    }

    public function userProduct($id)
    {


        $products = product::where('user_id',$id)->get();
        // $product=product::find($id);
        if($id != Auth::id()) //Auth::id() Is The Token That Came
        {
            return $this->sendError('YOU DONT HAVE RIGHTS');
        }
        return $this->sendResponse(productResources::collection($products), 'all product sent elderly user');
    }
    public function sortedProduct($attribute)//There is a problem , It is processed according to the first number only
    {
        $products = product::orderBy($attribute)->get();
        return $this->sendResponse(productResources::collection($products), 'all product sent Sorted');
    }


    public function store(Request $request)
    {
        // $input = $request->all();
        // $Validator = Validator::make($input, [
        //     'pro_name' => 'required',
        //     // 'user_id' => 'required',
        //     'pro_image' => 'required',
        //     'pro_expiration_Date' => 'required',
        //     'pro_Category' => 'required',
        //     'pro_phone' => 'required',
        //     'pro_quantity' => 'required',
        //     'pro_disCount1' => 'required',
        //     'pro_disCount2' => 'required'
        // ]);
        // if ($Validator->fails()) {
        //     return $this->sendError('please validate error', $Validator->errors());
        // }

        $user=Auth::user();
        // $input['user_id']=$user->id;
        $product=new product;
        $product->title = $request->title;

            if ($request->hasFile('image')) {

                // $path = $request->file('image')->move('uploads\products');
                $path = $request->file('image');
                $newPhoto=time().$path->getClientOriginalName();
                $path->move('uploads/products', $newPhoto);
            // $product->url = $path;
            // $product->url->move('uploads', $product->url);
           }
           
        $product=product::query()->create([
            'pro_name'=>$request->pro_name,
            //'pro_image'=>$request->pro_image,
            // 'pro_image'=>$request->pro_image->image,
            'pro_expiration_Date'=>$request->pro_expiration_Date,
            'pro_Category'=>$request->pro_Category,
            'pro_phone'=>$request->pro_phone,
            'pro_quantity'=>$request->pro_quantity,
            'price'=>$request->price,
            'user_id'=>$user->id,
            'title'=>$product->title,
            'views'=>'0',
            // 'url'=>'5'
            // 'url'=>'uploads/products/'.$newPhoto,
           'url'=>$request->url,
        ]);
        // $product = new product;
        // $product->title = $request->title;
        // $image = base64_decode('title');



        // $product->create([
        //         'title'=>$product->title,
        //         'url'=>$product->url,
        // ]);
         // $filename=time().'.'.$request->title->extension(filePath);
        // $product->create([

        // ]);

            // if ($request->hasFile('image')) {
                // $photo=$;
                //  $filename=time().$photo->getClientOriginalName();
                // $photo->move('uploads/producs', $newPhoto);
            // $path = $request->file('image')->store('uploads/products');
            // $product->url = $path;
        //    }
        //    $product->save();
        // return new productResources($image);

        foreach($request->list_discounts as $discount)
        {
            $product->discounts()->create([
                'date'=>$discount['date'],
                'discount_percentage'=>$discount['discount_percentage'],
            ]);
        }
        // $product = product::create($input);

        return $this->sendResponse(new productResources($product), 'Product Created successullly ');
        //productResources converts the existing date in the product variable that comed from database into an arreay of data and sendes it to frontEnd
        //collection used when we have more then one
        // new until is considered an object
    }

    public function storeOfComment(Request $request,$id)
    {
        $products = product::find($id);
        $user=Auth::user();
        $comment=comment::query()->create([
            'comment'=>$request->comment,
            'user_id'=>$user->id,
            'user_name'=>$user->name,
            'product_id'=>$products->id,
        ]);
        // foreach($request->list_comments as $comments)
        // {
        //     $comment->comments()->create([
        //         'comment'=>$comments['comment'],

        //     ]);
        // }

        return $this->sendResponse($comment, 'comment Created successullly ');

    }
    public function show($id)
    {

        $like=like::all(); $count=0;
        $product = product::find($id);
        $product->increment('views');
        if (is_null($product)) //product founded in database or not
        {
            return $this->sendError('product not found');
        }
        // $user=Auth::user();
        // $input['user_id']=$user->id;
        $product['The_Price_After_Discount']=null;
        $discounts=$product->discounts()->get();
        $maxDiscount=null;
        foreach($discounts as $discount){
            if(Carbon::parse($discount['date'])<=now()){
                // if($discount['date']<=now()){
                $maxDiscount=$discount; //MisDiscount we store the largest date from the
              }                        //desert which is smaller than the current date
        }

        if(!is_null($maxDiscount)){
            $discount_value=($product->price*$maxDiscount['discount_percentage'])/100;
            $product['The_Price_After_Discount']=$product->price-$discount_value;
        }

        foreach($like as $likes)
        {
            if($likes->deleted_at==null && $product->id==$likes->product_id)
            $count++;
        }
        $product['countOfLikes']=$count;

        $comments=$product->comments()->get();
        // $commentOfProduct[]=null;
        // foreach($comments as $comment){
        //     $commentOfProduct[]=$comment['comment'];
        // }
        // if(!is_null($commentOfProduct)){
            $product['comments']=$comments;
            // foreach($comments as $comment)
            //  {
            //       $product['comments']=$comment;
            //  }
    // }
        // return $product;
            // return $product->withCount();
          return $this->sendResponse($product, 'Product founded successullly');

        // return $this->sendResponse(new productResources($product), 'Product founded successullly');


    }




    public function update(Request $request,$id, product $product)
    {

        // $input = $request->all();
           $product=product::find($id);
        // $Validator = Validator::make($input, [
           $this->validate($request,[
            'pro_name' => 'required',
            //'pro_image' => 'required',
            // 'pro_expiration_Date' => 'required',
            'pro_Category' => 'required',
            'pro_phone' => 'required',
            'pro_quantity' => 'required',
            'price' => 'required',
            // 'pro_disCount1' => 'required',
            // 'pro_disCount2' => 'required'
        ]);

        // if ($Validator->fails()) {
        //     return $this->sendError('please validate error', $Validator->errors());
        // }


        // $product->pro_name = $input['pro_name']; // the first is Db and The second is the request the come from user
        // $product->pro_image = $input['pro_image'];
        // $product->pro_expiration_Date = $input['pro_expiration_Date'];
        // $product->pro_Category = $input['pro_Category'];
        // $product->pro_phone = $input['pro_phone'];
        // $product->pro_quantity = $input['pro_quantity'];
        // $product->pro_disCount1 = $input['pro_disCount1'];
        // $product->pro_disCount2 = $input['pro_disCount2'];

        if($product->user_id != Auth::id()) //Auth::id() Is The Token That Came
        {
            return $this->sendError('YOU DONT HAVE RIGHTS');
        }

        $product->pro_name = $request->pro_name;
       // $product->pro_image = $request->pro_image;
        // $product->pro_expiration_Date =$request->pro_expiration_Date;
        $product->pro_Category = $request->pro_Category;
        $product->pro_phone = $request->pro_phone;
        $product->pro_quantity = $request->pro_quantity;
        $product->price = $request->price;
        // $product->pro_disCount1 = $request->pro_disCount1;
        // $product->pro_disCount2 = $request->pro_disCount2;

        $product->save();
        // $user=Auth::user();
        // $input['user_id']=$user->id;
        return $this->sendResponse(new productResources($product), 'Product Updated successullly ');
    }


    public function destroy($id)
    {
        $errorMessage=[];
        // $discounts=discount::all();
        $product=product::findOrFail($id);
        if($product->user_id != Auth::id())
        {
            return $this->sendError('YOU DONT HAVE RIGHTS',$errorMessage);
        }
        $product->delete();
        // $discounts->delete();
        return $this->sendResponse(new productResources($product), 'Product Deleted successullly ');
    }

    public function destroyMyAccount($id)
    {
        $errorMessage=[];
        $user=user::find($id);
        if($user->id != Auth::id())
        {
            return $this->sendError('YOU DONT HAVE RIGHTS',$errorMessage);
        }else{
        $user->delete();
        echo "Done Deleted";
        }
    }
    public function searchByName( $req)
    {
      $data=product::
      where('pro_name','like','%'.$req.'%')
      ->get();
      return $this->sendResponse('search By Name',['products'=>$data]);
    }

    public function searchByCategory( $req)
    {
      $data=product::
      where('pro_Category','like','%'.$req.'%')
      ->get();
      return $this->sendResponse('search By Category',['products'=>$data]);
    }

    public function searchByExpirationDate( $req)
    {
      $data=product::
      where('pro_expiration_Date','like','%'.$req.'%')
      ->get();
      return $this->sendResponse('search By Expiration Date',['products'=>$data]);
    }

public function storeOfLikes(Request $request,$id)
{
     $product=product::find($id);

    if($product->likes()->where('user_id',Auth::id())->exists()){
        $product->likes()->where('user_id',Auth::id())->delete();
    }
    else{
        $product->likes()->create([
            'product_id'=>$product->id,
            'user_id'=>Auth::id()
        ]);
    }

    return response()->json(null);
}


// public function isLikedByMe($id)
// {
// $product = product::findOrFail($id)->first();
// if (Like::whereUserId(Auth::id())->whereProductId($product->id)->exists()){
//     return 'true';
// }
// return 'false';
// }

// public function like(product $product)
// {
//     // $products = product::find($id);
// $existing_like = Like::withTrashed()->whereProductId($product->id)->whereUserId(Auth::id())->first();

// if (is_null($existing_like)) {
//     Like::create([
//         'product_id'=>$product->id,
//         'user_id' => Auth::id()
//     ]);
// } else {
//     if (is_null($existing_like->deleted_at)) {
//         $existing_like->delete();
//     } else {
//         $existing_like->restore();
//     }
// }
// return $product;
// }
}
