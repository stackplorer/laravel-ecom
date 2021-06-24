<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use App\Models\CartItem;

class CartController extends Controller
{
    public function addItem(Request $request)
    {
    	$item = new CartItem();

    	if(Auth::check()){
    		$item->user_id = Auth::id();
    	}else{
    		$item->session_token = Session::token();
    	}

    	$item->product_id = $request['product_id'];
    	$item->quantity = $request['quantity'];
    	$item->active = true;

    	$item->save();

    	return "Item added to cart. Send an AJAX request instead...";
    }

    public function removeItem(Request $request, $id)
    {
    	$item = CartItem::find($id);
    	$item->active = false;
    	$item->save();
    	return Redirect::back()->withSuccess("Item has been removed from your cart.");
    }

    public function index()
    {
    	if(Auth::check()){
    		$cart_items = Auth::user()->cart;
    	}else{
    		$cart_items = CartItem::where('session_token', Session::token())
    								->where('active', 1)
    								->get();
    	}
    	return view('cart.index', ['cart_items' => $cart_items]);
    }
}