<x-app-layout>
  <x-slot name="header">
    <div>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Postes') }}
      </h2>

    </div>
  </x-slot>
  <center>

    <div class="container">
      @if(empty($postsWithComments))
      @foreach($postsWithcomments as $post)
      
      <div class="bg-white shadow-md rounded-lg p-6" style="width:700px;margin:20px;">

        <div class="flex items-center mb-4">
          <img src="{{ asset('user/'.Auth::user()->image) }}" alt="User Profile Image" class="h-8 w-8 rounded-full mr-2">
          <div class="text-sm font-semibold text-gray-700" style='margin:6px;'>{{ $post->username }}</div>
          <br> <br>
            <p>{{ $post->date }}</p>
        </div>



        <div class="sm:flex sm:justify-center">
          <div class="flex flex-col rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 sm:shrink-0 sm:grow sm:basis-0 sm:rounded-r-none">
            <a href="#!">
              <img class="rounded-t-lg sm:rounded-tr-none" src="{{ asset('poste/'.$post->image) }}" alt="Hollywood Sign on The Hill" />
            </a>
            <div class="p-6">
              <h5 class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                Card title
              </h5>
              <p class="mb-4 text-base text-neutral-600 dark:text-neutral-200">
                {{ $post->commentaire }}
              </p>
              <p class="mb-4 text-base text-neutral-600 dark:text-neutral-200">

              </p>
            </div>
          </div>

        </div>


        <form id="add-comment" method="post" action="{{ route('addcomment') }}" enctype="multipart/form-data">
          @csrf

          <input type="hidden" name="post_id" value="{{ $post->id }}">

          <div class="form-group" style="margin:20px;">
            <textarea id="content" name="content" class="resize-none border rounded-md p-2 w-full
             h-32 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your text here..." required></textarea>
          </div>
          <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
        </form>


        <form id="like-form-{{ $post->id }}" action="{{ route('like', ['post_id' => $post->id]) }}" method="POST">

          <button type="button" class="like-button" data-post-id="{{ $post->id }}" data-liked="{{ $post->isLikedByUser(auth()->id()) ? 'true' : 'false' }}">
            <i class="far fa-heart{{ $post->isLikedByUser(auth()->id()) ? ' text-red-500' : '' }}"></i>
            </button>
            </form>
            <span >
              @foreach ($likes as $index => $like)
              @if ($index < 1) 
             <span class="like-text" data-post-id="{{ $post->id }}" >{{$post->like()->count()}} </span>
               <p>{{ $like->user->name }}</p> 
             @if ($index === 0 && $post->like()->count() > 1)
             <button class="like-other-button" data-post-id="{{ $post->id }}">
            <span class="show-others" data-post-id="{{ $post->id }}">and {{ $post->like()->count() - 1 }} others</span>
            </button> 
            @endif

            @if ($index < 1 && $index + 1 !==count($likes)) <span>
              </span>
              @endif 
              @endif
              
              @endforeach
              
              </span>
        <div id="comment-section" class="bg-white shadow-md rounded-lg p-4 mb-4">
          @foreach ($post->commentaires as $comment)
          <div class="flex items-center mb-2">
            <img class="h-8 w-8 rounded-full" src="{{ asset('user/'.$comment->user->image) }}" alt="User Avatar">
            <div class="ml-2">
              <p class="font-semibold">{{$comment->user->name}}</p>
              <p class="text-gray-500 text-sm">{{$comment->content}}</p>
            </div>
          </div>
          @endforeach
        </div>

      </div>

      @endforeach
      @endif

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script>
// $(document).ready(function() {
//     $('#add-comment').submit(function(event) {
//         // Prevent the default form submission
//         event.preventDefault();

//         // Collect comment data
//         var formData = $(this).serialize();

//         // Send AJAX request
//         $.ajax({
//             url: '/addcomment', // URL to your server-side route
//             type: 'POST',
//             data: formData,
//             success: function(response) {
//                 // Update the page with the new comment data
//                 // (e.g., append the new comment to the comment section)
//                 $('#comment-section').append(response);
//             },
//             error: function(xhr, status, error) {
//                 // Handle error
//                 console.error('Error adding comment:', error);
//             }
//         });
//     });
// });





        $(document).ready(function() {
          //$('.like-button','.unlike-button').click(function() {

          var csrfToken = $('meta[name="csrf-token"]').attr('content');
          $('.like-button').click(function() {


            var $button = $(this);
            var postId = $button.data('post-id'); // Retrieve postId or any necessary data
            var likeRoute = "{{ route('like', ['post_id'  =>':postId']) }}";
            var isLiked = $button.hasClass('liked');
            var likesCount = $('.like-text[data-post-id="' + postId + '"]');
            likeRoute = likeRoute.replace(':postId', postId);
            console.log(likeRoute);
            // Perform AJAX request for liking the post
            $.ajax({
              url: likeRoute,
              type: 'POST',
              headers: {
                'X-CSRF-TOKEN': csrfToken // Include CSRF token in the request headers
              },

              data: {
                post_id: postId,
                csrfToken: csrfToken,
                liked: !isLiked

                // Add any other data you need to send
              },
              success: function(response) {
                console.log('Response:', response.success);
                if (response.success) {
                  
                  if (isLiked) {
                    $button.removeClass('liked');
                    $button.find('i').removeClass('fas').addClass('far');
                     // Change heart icon
                     //var likesCount = document.getElementById('likes-count-' + postId);
                //likesCount.textContent = parseInt(likesCount.textContent) - 1;
                likesCount.text(parseInt(likesCount.text()) - 1);
                  } else {
                    $button.addClass('liked');
                    $button.find('i').removeClass('far').addClass('fas'); 
                    //likesCount.textContent = parseInt(likesCount.textContent)+1;
                    likesCount.text(parseInt(likesCount.text()) + 1);
                    // Change heart icon
                }

                  
                } else {
                  console.error('Failed to like/unlike the post.');
                }

              },
              error: function(xhr) {
                // Handle error response
              }
            });

          });
        });

        $(document).ready(function() {
      var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $('.like-other-button').click(function() {
          var $button = $(this);
            var postId = $button.data('post-id');
            var likeRoute = "{{ route('get-other-likes', ['post_id'  =>':postId']) }}";
            likeRoute = likeRoute.replace(':postId', postId);
            console.log(likeRoute);
            console.log(postId);
            // Send AJAX request to fetch remaining users
            $.ajax({
                url: likeRoute,
                type: 'GET',
                headers: {
                'X-CSRF-TOKEN': csrfToken, // Include CSRF token in the request headers
                
              },
              data: {
                post_id: postId,
                csrfToken: csrfToken,
                // Add any other data you need to send
                
              },
              
                success: function(response) {
                  console.log(response.success);
                    // Append the fetched users to the DOM
                    // response.forEach(function(user) {
                    //     $('<p>').text(user.name).appendTo('.like-users-' + postId);
                    // });
                },
                error: function(xhr) {
                    console.error('Failed to fetch other likes.');
                }
            });
        });
    });
      </script>
      
  </center>
</x-app-layout>