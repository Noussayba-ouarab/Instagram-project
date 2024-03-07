<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>user profil</title>
</head>
<body>
<body>

<center>
    <div class="container" style="margin:90px;margin-right:90px;"> 
        
        

        <div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">My Profile</h5>
        <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #333;
        }
        /* Add your styles here */
    </style>
        <!-- <img class="h-8 w-8 rounded-full" 
      src="{{ asset('user/'.Auth::user()->image) }}" style="width:100px;"/> -->
      <div class="ml-3 ">
        <div class="ml-3 "> Hello, this is my instagram account, i share my ideas, my passion,
        all about what i really love doing and exploring around this tiny device...
      </div>
        <p class="card-text">{{$data->name}}</p>
        <p class="card-text">{{$data->email}}</p>
        
      </div>
    </div>
  </div>
  
</div>
            
        
       
    </div>
    </center>
</body>
</body>
</html>