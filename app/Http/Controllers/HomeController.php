<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function registUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required', 'password' => 'required', 'image' => 'required'
        ]);
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        //trying to upload images

        $image = $request->file('image');
        

    // Get the original name of the uploaded file
    //$imageName = $image->getClientOriginalName();
        //var_dump($image);
        
        if ($image) {
            $imagename = $image->getClientOriginalName();
            //$imagename = time() . '.' . $image->getClientOriginalName();
            //$imageName = $uploadedFile->getClientOriginalName();
            
            $path=$request->image->move('user', $imagename);
            
            
            $data->image = $imagename;
            //var_dump($data->image);
            //exit();
        }
        
        $data->save();

        return view("auth/login");
    }
    public function index()
    {
        return view('home');
    }
}
