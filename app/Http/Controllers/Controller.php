<?php

namespace App\Http\Controllers;
use App\Models\CartDetail;
use App\Models\Carts;
use App\Models\Item;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //HOME
    function viewHome(){
        return view('home');
    }

    //PROFILE

    function viewRegister(){
        if (Session::get('user')) return redirect()->route('home');

        return view('register');
    }

    function runRegister(Request $req){
        $rules = [
            'fullname' => 'required|string|min:3',
            'email' => 'unique:users,email|email',
            'password' => 'required|string|min:6',
            'confirmPassword' => 'required|same:password'
        ];

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) return back()->withErrors($validator);

        $user = new User();
        $user->username = $req->fullname;
        $user->email = $req->email;
        $user->role = 'customer';
        $user->password = Hash::make($req->password, [
            'rounds' => 12,
        ]);
        $user->save();
        return redirect()->route('login')->with('register_success', 'Account Successfully Registered!');
    }

    function viewLogin(){
        if (Session::get('user')) return redirect()->route('home');
        return view('login');
    }

    function runLogin(Request $req){
        $rules = [
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ];

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) return back()->withErrors($validator);
        $credentials = [
            'email' => $req->email,
            'password' => $req->password
        ];

        if (!Auth::attempt($credentials)) return back()->withErrors('invalid credentials');
        Session::put('user', Auth::user());
        if ($req->remember === 'on') {

            Cookie::queue('email', $req->email, 20);
            Cookie::queue('password', $req->password, 20);
        } else {
            Cookie::queue(Cookie::forget('email'));
            Cookie::queue(Cookie::forget('password'));
        }
        return redirect('/home');
    }

    function runLogout(){
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }

    function viewEdit(){
        $user = auth()->user();
        return view('editProfile', compact('user'));
    }

    function runEditProfile(Request $request){
        $rules = [
                'username' => 'required|string|min:3',
                'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            ];
            $request->validate($rules);

            $user = User::find(auth()->user()->id);
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->save();

            return redirect('/editProfile')->with('success', 'Profile Successfully Updated!');
    }

    function viewChange(){
        $user = auth()->user();
        return view('changePassword', compact('user'));
    }

    function runChangePassword(Request $request) {

        $rules = [
            'password' => 'required',
            'newpassword' => 'required|min:6',
            'confirmPassword' => 'required|same:newpassword'
        ];
        $request->validate($rules);
        $user = User::find(auth()->user()->id);
        if (!Hash::check($request->input('password'), $user->password)) {
          return redirect('/changePassword')->with('fail', 'Incorrect old password');
        }
        $user->password = Hash::make($request->input('newpassword'));
        $user->save();

        return redirect('/changePassword')->with('success', 'Password Successfully Changed!');
      }


    //PRODUCTS

    public function viewProducts(){
        return view('showProduct', [
            'title' => 'Show Products',
            'products' => Item::latest()->filter()->paginate(3)
        ]);
    }

    public function viewProductDetail(Item $product){
        return view('productDetail', [
            "title" => "Product Detail",
            "product" => $product
        ]);
    }

    function viewManageItem(){
        return view('viewItem', ['products' => Item::all()]);
    }

    function deleteItem(item $product){
        Item::destroy($product->id);
        return redirect('/viewItem')->with('success', 'Item Successfully Deleted!');
    }

    function viewAddItem(){
        return view('addItem');
    }

    function runAddItem(Request $req){

        $validator = $req->validate([
            'id' => 'required|unique:items|string|min:3|max:3',
            'name' => 'required|unique:items|string|max:20',
            'price' => 'required|numeric|gte:500',
            'description' => 'required|string|max:500',
            'image' => 'required|image',
            'category' => 'required|in:Second,Recycle'
        ]);

        $image = $req->file('image');
        $imageName = date('YmdHi') . $image->getClientOriginalName();
        $imageURL = 'images/' . $imageName;
        $validator['image'] = $imageURL;
        Item::create($validator);
        return redirect('/viewItem')->with('success', 'Item Successfully Added!');
    }

    function viewUpdateItem(Item $product){
        return view('updateItem', [
            'title' => "updateItem",
            "product" => $product
        ]);
    }

    function runUpdateItem(Request $req, Item $product){
        $rules = [
            'price' => 'required|numeric|gte:500',
            'description' => 'required|string|max:500',
            'category' => 'required|in:Recycle,Second'
        ];

        if ($req->name != $product->name) {
            $rules['name'] = 'required|unique:items|string|max:20';
        }

        if ($req->has('image')) {
            $rules['image'] = 'required|image';
            $validator = $req->validate($rules);
            if ($product->image !== 'images/default-image.jpg')
                Storage::delete('public/' . $product->image);
            $image = $req->file('image');
            $imageName = date('YmdHi') . $image->getClientOriginalName();
            Storage::putFileAs('public/images', $image, $imageName);
            $imageURL = 'images/' . $imageName;
            $validator['image'] = $imageURL;
            Item::where('id', $product->id)->update($validator);
        } else {
            $validator = $req->validate($rules);
            $validator['image'] = $product->image;
            Item::where('id', $product->id)->update($validator);
        }
        return redirect('/viewItem')->with('success', 'Item Successfully Updated!');
    }

    //CART

    function viewCart(){
        return view('cartList', [
            'title' => 'Cart Page',
            'cartitems' => Carts::latest('carts.created_at')->where('carts.user_id', '=', strval(Session::get('user')['id']))
                ->join('cart_details', 'carts.id', '=', 'cart_details.cart_id')->join('items', 'items.id', '=', 'cart_details.item_id')
                ->groupBy('carts.id')->selectRaw('sum(qty*price) as sum,sum(qty) as ctr, carts.id')->first()
        ]);
    }

    public function runAddCart(Request $req){
        $validator = $req->validate([
            'qty' => 'required|gte:1',
            "id" => 'exists:items'
        ]);
        if (!Session::get('user')) {
            return redirect()->route('login');
        }
        if (Carts::where('user_id', '=', strval(Session::get('user')['id']))->get()->count() == 0) {
            $cart = new Carts();
            $cart->user_id = Session::get('user')['id'];
            $cart->save();
        }
        $cartId = Carts::where('user_id', '=', strval(Session::get('user')['id']))->select('id')->first()['id'];
        if (CartDetail::where([['cart_id', '=', $cartId],['item_id', '=', $validator['id']]])->get()->count() != 0) {
            CartDetail::where([['cart_id', '=', $cartId],['item_id', '=', $validator['id']] ])->increment('qty', $validator['qty']);
        } else {
            $cartDetail = new CartDetail();
            $cartDetail->cart_id = $cartId;
            $cartDetail->item_id = $validator['id'];
            $cartDetail->qty =  $validator['qty'];
            $cartDetail->save();
        }
        return back()->with('success', 'Added!');
    }

    function viewUpdateCart(Item $product){
        return view('updateCart', [
            'title' => "Update Cart Item",
            "product" => $product,
            "qty" => Carts::where('user_id', '=', strval(Session::get('user')['id']))->first()->cartDetail()->where('cart_details.item_id','=',$product->id)->first()['qty']
        ]);
    }
    public function runUpdateCartqty(Request $req){
        $rules = [
            'qty' => 'required|gte:1',
            "id" => 'exists:items'
        ];

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) return back()->withErrors($validator);
        if (!Session::get('user') ||Carts::where('user_id', '=', strval(Session::get('user')['id'])) ->get() ->count() == 0) {
            return redirect()->route('login');
        }
        $header = Carts::where('user_id', '=', strval(Session::get('user')['id']))->first('id');
        CartDetail::where([['cart_id', '=', $header['id']], ['item_id', '=', $req->id]])->update(['qty' => $req->qty]);
        return back()->with('success', 'Updated!');
    }

    public function runDeleteCartItem(Request $req){
        if (!Session::get('user') ||Carts::where('user_id', '=', strval(Session::get('user')['id']))->get()->count() == 0) {
            return redirect()->route('login');
        }
        CartDetail::where([['cart_id', '=', $req->cart_id], ['item_id', '=', $req->item_id] ])->delete();
        return back();
    }

    function viewTransaction(){

        return view('transactionHistory', [
            'title' => 'Transaction History',
            'histories' => TransactionHeader::latest('transaction_headers.created_at')->where('transaction_headers.user_id', '=', strval(Session::get('user')['id']))
                ->join('transaction_details', 'transaction_headers.id', '=', 'transaction_details.transaction_id')->join('items', 'items.id', '=', 'transaction_details.item_id')
                ->groupBy(['transaction_headers.id','transaction_headers.created_at'])->selectRaw('sum(qty*price) as sum,sum(qty) as ctr, transaction_headers.id, transaction_headers.created_at as created')->get()
        ]);
    }

    function runCheckout(Request $req){
        $rules = [
            'name' => 'required',
            "address" => 'required'
        ];

        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) return back()->withErrors($validator);
        $transheader = new TransactionHeader();
        $transheader->receiver_name = $req->name;
        $transheader->receiver_address = $req->address;
        $transheader->user_id = Session::get('user')['id'];
        $transheader->save();
        $cartdetail = CartDetail::where('cart_id', '=', $req->cart_id)->get();

        foreach ($cartdetail as $detail) {
            $transdetail = new TransactionDetail();
            $transdetail->transaction_id = $transheader->id;
            $transdetail->item_id = $detail->item_id;
            $transdetail->qty = $detail->qty;
            $transdetail->save();
        }
        CartDetail::where('cart_id', '=', $req->cart_id)->delete();
        return redirect()->route('transactionHistory');
    }
}
