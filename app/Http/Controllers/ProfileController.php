<?php

namespace App\Http\Controllers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Poste;
use App\Models\Like;
use App\Models\commentaire;
/**
 * controlleur de l application
 * 
 */
class ProfileController extends Controller
{
    /**
     *
     *
     * @return View
     */
    public function edit(): View
    {
        $user = Auth::user();
    

        $email = $user->email; // Access the email property
        
        $data = User::where('email', $email)->first();
        $posts = Poste::with('user')->get();

        $numberposts = count($posts);
        return view("profile.partials.profile", [
            'data' => $data,
            'posts' => $posts, 'numberposts' => $numberposts
        ]);
    }
    /**
     * download a file
     *
     * @return void
     */
    public function download()

    {
        $user = Auth::user();
        $email = $user->email; // Access the email property
        $data = User::where('email', $email)->first();
        $pdf = PDF::loadView('downloads', array('data' =>  $data))
            ->setPaper('a4', 'portrait');
        return ($pdf->download('users-details.pdf'));
    }
    /**
     * 
     *insert poste
     * @param Request $request
     * @return void
     */
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
        } else {
            return redirect()->route('dashboard');
            
        }
    }
    /**
     * page home
     *
     * @return View
     */
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
    /**
     * redirect to add poste page
     *
     * @return View
     */
    public function adding(): View
    {
        $user = Auth::user();
        if ($user) {
            return view("head");
        } else {
            return redirect()->back();
        }
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function profile()
    {
        $user = Auth::user();
        if ($user) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->back();
        }
    }
    /**
     * insert comment
     *
     * @param Request $request
     * @return void
     */
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
        } else {
            return redirect()->back();
        }
    }
    /**
     * insert like or dislike to a post
     *
     * @param [type] $post_id
     * @return void
     */
    public function like($post_id)
    {
        // Check if the user already liked the post
        $existingLike = Like::where('user_id', auth()->id())
            ->where('post_id', $post_id)
            ->first();
        $postsWithcomments = Poste::with('commentaires')->get();
        $likes = Like::all();
        // Create a new like
        $like = new Like();
        $like->user_id = auth()->id();
        $like->post_id = $post_id;
        $like->save();
        return response()->json(['success' => true, 'message' => 'Post liked successfully.']);
        
    }
    
    /**
     * Delete the user's account.
     * @param mixed $name
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
