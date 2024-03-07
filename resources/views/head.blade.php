<x-app-layout>
    <x-slot name="header">
        <div>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Postes') }}
        </h2>
         </div>
    </x-slot>
    <div>
<div class="container" style="margin:20px;">
<div class="max-w-md mx-auto">
  <div class="bg-white shadow-md rounded-lg p-6">
    <div class="flex items-center mb-4">
      <img src="{{ asset('user/'.Auth::user()->image) }}" alt="User Profile Image" class="h-8 w-8 rounded-full mr-2">
      <div class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</div>
    </div>
<div class="max-w-md mx-auto">
  <div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-semibold mb-4">Add Poste</h2>
    <form id="add-poste" method="post" action="{{ route('addposte') }}" enctype="multipart/form-data" >
    @csrf
      <div class="mb-4">
        <label for="comment" class="block text-sm font-medium text-gray-700">Comment</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="commentaire" 
        placeholder="add comment...." >
  </textarea>
      </div>
      <div class="mb-4">
        <label for="file" class="block text-sm font-medium text-gray-700">Upload File</label>
        <input type="file"  name="image" class="mt-1 block w-full rounded-md border-gray-300">
      </div>
      <div class="flex items-center justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-black font-semibold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">
          Add Poste
        </button>
      </div>
    </form>
  </div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var addPostForm = document.getElementById('add-poste');
    addPostForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        var formData = new FormData(this); // Create FormData object from form data

        // Send a POST request to the server
        fetch(this.action, {
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to add post');
            }
            return response.text(); // Assuming the server returns HTML for the new post
        })
        .then(data => {
            // Update UI to add the new post
            document.getElementById('post-container').insertAdjacentHTML('beforeend', data);
        })
        .catch(error => {
            console.error(error);
            // Handle error if necessary
        });
    });
});
</script>
</div>
</x-app-layout>