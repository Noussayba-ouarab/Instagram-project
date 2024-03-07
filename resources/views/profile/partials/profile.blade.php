
<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
<section>
<center>
   

<div class="py-12" >
    
        
        <div class="bg-gray-100 p-4">
  <div class="bg-white border rounded-sm max-w-md" >
    <div class="flex items-center px-4 py-3">
      <img class="h-8 w-8 rounded-full" 
      src="{{ asset('user/'.Auth::user()->image) }}" style="width:100px;"/>
      <div class="ml-3 ">
        <span class="text-sm font-semibold antialiased block leading-tight">{{ $data->name }}</span>
        <span class="text-gray-600 text-xs block">{{ $data->email }}</span>
      </div>
      
      <div class="ml-3 "> Hello, this is my instagram account, i share my ideas, my passion,
        all about what i really love doing and exploring around this tiny device...
      </div>
    </div>
    <form action="{{route('download')}}" method="get">
        <input type="submit"  value="Download"class="btn btn-dark btn-lg">
           
            </form>
            <p>My postes: {{ $numberposts }}</p>
  </div>
        </div> 



            
                <div class="container">
                <h2 >Mes postes</h2>
            
    <div class="container mx-auto mt-8 px-4" >
    <center>
        <div class="grid grid-cols-3 gap-4" style="margin:20px;">
            @foreach($posts as $post)
            <div class="bg-white border border-gray-200 rounded-md shadow-sm" 
            style="margin:20px;margin-left:12px;">
                <img src="{{ asset('poste/'.$post->image) }}" alt="Post 1" 
                class="w-full h-64 object-cover rounded-t-md" >
                <div class="p-4">
                
                    <h2 class="text-lg font-semibold">Post Title </h2>
                    <p class="text-gray-600">{{ $post->commentaire }}</p>
                    
                </div>
            </div>
            
   @endforeach
  </div>
  
</center>
</div>
            </div>
        </div>
        
    
    
    </div>
    </div>
    </div>
    
</div>
    
    </center>
    </section>

</x-app-layout>
