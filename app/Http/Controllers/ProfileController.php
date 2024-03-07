<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use  Illuminate\Database\Eloquent\ModelNotFoundException;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Storage;
use App\Models\Poste;
use App\Models\Like;
use App\Models\commentaire;

class ProfileController extends Controller
{
    public function edit(): View
    {

        $user = Auth::user();
        $userId = Auth::id();

        $email = $user->email; // Access the email property
        $username = $user->name;
        $data = User::where('email', $email)->first();
        //$posts = auth()->user()->postes()->get();
        //$posts = Poste::where('username', $username)->first();
        //$posts = Poste::where('user_id', $userId)->with('user')->get();
        $posts = Poste::with('user')->get();
        // Save the new User instance to the database

        // var_dump($posts->image);
        // exit();
        //$data = User::all();

        $numberposts = count($posts);
        return view("profile.partials.profile", [
            'data' => $data,
            'posts' => $posts, 'numberposts' => $numberposts
        ]);
    }
    public function download()

    {
        //$data = User::all();
        $user = Auth::user();
        $email = $user->email; // Access the email property
        $data = User::where('email', $email)->first();
        $pdf = PDF::loadView('downloads', array('data' =>  $data))
            ->setPaper('a4', 'portrait');
        return ($pdf->download('users-details.pdf'));
    }
    public function add(Request $request)
    {
        $user = Auth::user();
        $postsWithcomments = Poste::with('commentaires')->get();
        $likes = Like::all();
        if ($user) {
            $data = new Poste();
            $current = Carbon::now();
            $data->commentaire = $request->commentaire;
            $data->username = $user->name;
            $data->date = $current;
            $image = $request->image;
            if ($image) {
                $imagename = time() . '.' . $image->getClientOriginalExtension();
                $request->image->move('poste', $imagename);
                $data->image = $imagename;
            }
            $data->save();
            $data = Poste::all();
            return redirect()->route('dashboard');
            // return view('dashboard', array(
            //     'data' =>  $data,
            //     'postsWithcomments' => $postsWithcomments, 'likes' => $likes
            // ));
        } else {
            return redirect()->route('dashboard');
            // return view('dashboard', [
            //     'postsWithcomments' => $postsWithcomments, 'likes' => $likes
            // ]);
        }
    }
    public function dashboard(): View
    {
        $user = Auth::user();
        //$post = Poste::find($postId);
        if ($user) {
            //$data = Poste::all();
            $postsWithcomments = Poste::with('commentaires')->get();
            $likes = Like::all();
            return view("dashboard",  [
                'postsWithcomments' => $postsWithcomments, 'likes' => $likes,
            ]);
        } else {
            //return redirect()->route('dashboard');
            return view("auth/login");
        }
    }
    public function adding(): View
    {
        $user = Auth::user();
        if ($user) {
            return view("head");
        } else {
            return redirect()->back();
        }
    }
    public function profile()
    {
        $user = Auth::user();
        if ($user) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->back();
        }
    }
    public function addcomment(Request $request)
    {
        $user = Auth::user();
        $postsWithcomments = Poste::with('commentaires')->get();
        $likes = Like::all();
        //$username=$user->name;
        if ($user) {
            $comment = new Commentaire();
            //$poste = new Poste();
            $comment->content = $request->input('content');
            $comment->user_id = $user->id;
            $comment->post_id = $request->input('post_id');
            //$pid=$poste->id = $request->input('post_id');
            $comment->save();
            $comment = Commentaire::all();
            //return response()->json(['message' => 'Comment added successfully.']);
            return redirect()->back();
            // return view('dashboard', [
            //     'comment' => $comment,
            //     'postsWithcomments'=>$postsWithcomments,'likes'=>$likes,
            // ]);
        } else {
            return redirect()->back();
        }
    }
    public function like($post_id)
    {
        // Check if the user already liked the post
        $existingLike = Like::where('user_id', auth()->id())
            ->where('post_id', $post_id)
            ->first();
        $postsWithcomments = Poste::with('commentaires')->get();
        $likes = Like::all();

        // If the user already liked the post, return a response (you can handle this based on your application logic)
        // if ($existingLike) {
        //     return view('dashboard', ['postsWithcomments' => $postsWithcomments, 'likes' => $likes]);
        // }

        // Create a new like
        $like = new Like();
        $like->user_id = auth()->id();
        $like->post_id = $post_id;
        $like->save();
        return response()->json(['success' => true, 'message' => 'Post liked successfully.']);
        // You can also return a response if the like is successfully added
        // return view('dashboard', ['postsWithcomments' => $postsWithcomments, 'likes' => $likes]);
    }
    public function unlike($postId)
    {
        $like = Like::where('user_id', auth()->id())->where('post_id', $postId)->first();

        // If the like entry exists, delete it
        if ($like) {
            $like->delete();
        }
        // Optionally, you can update the number of likes for the post
        $likesCount = Like::where('post_id', $postId)->count();
        return redirect()->back();
        // return response()->json(['success' => true, 'likes_count' => $likesCount]);
    }

    public function getOtherLikes($post_id)
    {
        //dd($post_id);
        try {
            $post = Poste::findOrFail($post_id);
            dd($post);
            return response()->json(['success' => true, 'message' => 'Post liked successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Post not found'], 404);
        }
    }
    public function registUser(Request $request): View
    {
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = $request->password;
        //trying to upload images
        $image = $request->image;
        if ($image) {
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->image->move('user', $imagename);
            $data->image = $imagename;
        }
        $data->save();

        return view('auth/login');
    }
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }
}
?>