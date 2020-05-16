<?php

Laravel 5.4 Crud Example From Scratch
https://appdividend.com/2017/05/02/laravel-5-4-crud-example-scratch/

Welcome, Web Artisans,  In today’s AppDividend Tutorial, I have shown the code of Laravel 5.4 Crud Example From Scratch. It is simple Laravel 5.4 tutorial for beginners.

  Laravel
  For creating Laravel Project, you need to have following things.

  PHP >= 5.6.4
  OpenSSL PHP Extension
  PDO PHP Extension
  Mbstring PHP Extension
  Tokenizer PHP Extension
  XML PHP Extension
  Composer



Step 1: Create a Laravel project
  Type following command in your terminal to create a project.
  composer create-project --prefer-dist laravel/laravel Laravel54
  It will take 2 minutes to install a brand new Laravel Framework

  After installing, go to your project root folder and type php artisan serve in terminal, your project URL might look like this
  http://localhost:8000
  Now open that laravel project folder in your favorite editor.


Step 2: Edit .env file for database configuration
  In your project root folder, there is a file called .env, which we need to edit to set up a database configuration. I am using MySQL database.

  // .env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=laravel54
  DB_USERNAME=root
  DB_PASSWORD=mysql


Step 3: Use migrations provided by laravel to create users and password_resets table
  Now go to terminal, and run following command
  php artisan migrate
  If you have created the database in MySQL and you have provided correct credentials to the .env file, then you will see there are three tables generated in MySQL database.



Step 4: Create a controller file for our CRUD operations.
  Go to terminal, type the following command in your root project.

  php artisan make:controller CRUDController --resource
  Now go to laravel54 > app > Http > Controllers, you will see CRUDController file in that folder.

  This file has all the boilerplate needed for our Crud operations.


Step 5: Create a model file for CRUD operations.
  In the terminal, type the following command in your root project.

  php artisan make:model Crud -m
  This command will also create a migration file for our CRUD operations.


Step 6: Edit crud migration file and create the required fields for the database.
  Migration file is located in laravel54 > database > migrations folder.



      <?php
      // create_cruds_table

      use Illuminate\Support\Facades\Schema;
      use Illuminate\Database\Schema\Blueprint;
      use Illuminate\Database\Migrations\Migration;

      class CreateCrudsTable extends Migration
      {
          /**
          * Run the migrations.
          *
          * @return void
          */
          public function up()
          {
              Schema::create('cruds', function (Blueprint $table) {
                  $table->increments('id');
                  $table->string('title');
                  $table->string('post');
                  $table->timestamps();
              });
          }

          /**
          * Reverse the migrations.
          *
          * @return void
          */
          public function down()
          {
              Schema::dropIfExists('cruds');
          }
      }
     

    Run the following command in your terminal.
    php artisan migrate



Step 7: Create views for set up a form
    Go to laravel54 > resources > views . Locate into that folder and then create a master view called master.blade.php. A blade is templating engine used by laravel.

    <!-- master.blade.php -->

    <!doctype html>
    <html lang="{{ config('app.locale') }}">
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title>CRUD Operations</title>

            <!-- Fonts -->
            <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css">
        </head>
        <body>
            <br><br>
            @yield('content')
        </body>
    </html>


 Now create a new folder inside views directory called crud. Go inside that folder and create following files

    // index.blade.php
    // create.blade.php
    // edit.blade.php


Step 8: Create a form in create.blade.php

      <!-- create.blade.php -->

      @extends('master')
      @section('content')
      <div class="container">
        <form>
          <div class="form-group row">
            <label for="lgFormGroupInput" class="col-sm-2 col-form-label col-form-label-lg">Title</label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-lg" id="lgFormGroupInput" placeholder="title" name="title">
            </div>
          </div>
          <div class="form-group row">
            <label for="smFormGroupInput" class="col-sm-2 col-form-label col-form-label-sm">Post</label>
            <div class="col-sm-10">
              <textarea name="post" rows="8" cols="80"></textarea>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-2"></div>
            <input type="submit" class="btn btn-primary">
          </div>
        </form>
      </div>
      @endsection


Step 9: Setup a route for the request handling.
Go to the routes > web.php

    <?php

    Route::get('/', function () {
        return view('welcome');
    });
    Route::resource('crud', 'CRUDController');
    Here we have added resource route, and all the functions reside in app > Http > controllers > CRUDController


Step 10: Edit CRUDController File
    // CRUDController.php

      /**
        * Show the form for creating a new resource.
        *
        * @return \Illuminate\Http\Response
        */
        public function create()
        {
            return view('crud.create');
        }


  Here we have to return the view when the request hits the route; it will be redirected to this controller’s create method. Our view should be accessible via following URL the form.

  http://localhost:8000/crud/create
  We are now able to see the form with the two fields.

Step 11: Add CSRF token and set the post route of the form.
<!-- create.blade.php -->

    @extends('master')
    @section('content')
    <div class="container">
      <form method="post" action="{{url('crud')}}">
        <div class="form-group row">
          {{csrf_field()}}
          <label for="lgFormGroupInput" class="col-sm-2 col-form-label col-form-label-lg">Title</label>
          <div class="col-sm-10">
            <input type="text" class="form-control form-control-lg" id="lgFormGroupInput" placeholder="title" name="title">
          </div>
        </div>
        <div class="form-group row">
          <label for="smFormGroupInput" class="col-sm-2 col-form-label col-form-label-sm">Post</label>
          <div class="col-sm-10">
            <textarea name="post" rows="8" cols="80"></textarea>
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-2"></div>
          <input type="submit" class="btn btn-primary">
        </div>
      </form>
    </div>
    @endsection
 
  We have put the {{csrf_field()}} in the form so that malicious attack can not harm our web application.




Step 12: Code the store function and use Crud model to insert the data

    <?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Crud;

    class CRUDController extends Controller
    {
        /**
        * Display a listing of the resource.
        *
        * @return \Illuminate\Http\Response
        */
        public function index()
        {
            
        }

        /**
        * Show the form for creating a new resource.
        *
        * @return \Illuminate\Http\Response
        */
        public function create()
        {
            return view('crud.create');
        }

        /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
        */
        public function store(Request $request)
        {
            $crud = new Crud([
              'title' => $request->get('title'),
              'post' => $request->get('post')
            ]);

            $crud->save();
            return redirect('/crud');
        }

        /**
        * Display the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function show($id)
        {
            //
        }

        /**
        * Show the form for editing the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function edit($id)
        {
            //
        }

        /**
        * Update the specified resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function update(Request $request, $id)
        {
            //
        }

        /**
        * Remove the specified resource from storage.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function destroy($id)
        {
            //
        }
    }

Here we need to create a protected field called $fillable in the Crud model. Otherwise, it will throw a mass assignment exception.

    // Crud.php
    <?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Crud extends Model
    {
        protected $fillable = ['title','post'];
    }
    Now, when you fill the title and post field in our form and submit the form, the new entry will be added to the database. We can confirm it by following steps.



Step 13: Code index() function in the CRUDController File.
//CrudController.php

    <?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\Crud;

    class CRUDController extends Controller
    {
        /**
        * Display a listing of the resource.
        *
        * @return \Illuminate\Http\Response
        */
        public function index()
        {
            $cruds = Crud::all()->toArray();
            
            return view('crud.index', compact('cruds'));
        }

        /**
        * Show the form for creating a new resource.
        *
        * @return \Illuminate\Http\Response
        */
        public function create()
        {
            return view('crud.create');
        }

        /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
        */
        public function store(Request $request)
        {
            $crud = new Crud([
              'title' => $request->get('title'),
              'post' => $request->get('post')
            ]);

            $crud->save();
            return redirect('/crud');
        }

        /**
        * Display the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function show($id)
        {
            //
        }

        /**
        * Show the form for editing the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function edit($id)
        {
            //
        }

        /**
        * Update the specified resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function update(Request $request, $id)
        {
            //
        }

        /**
        * Remove the specified resource from storage.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function destroy($id)
        {
            //
        }
    }
    
Step 14: Need to update index.blade.php
    <!-- index.blade.php -->
    @extends('master')
    @section('content')
      <div class="container">
        <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Post</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cruds as $post)
          <tr>
            <td>{{$post['id']}}</td>
            <td>{{$post['title']}}</td>
            <td>{{$post['post']}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      </div>
    @endsection
  Next, shoot the following URL.

    http://localhost:8000/crud
    You will see a table which has the id, title and post column with their respective data.


Step 15: Add Edit and Delete Button in the index.blade.php
    <!-- index.blade.php -->

    @extends('master')
    @section('content')
      <div class="container">
        <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Post</th>
            <th colspan="2">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($cruds as $post)
          <tr>
            <td>{{$post['id']}}</td>
            <td>{{$post['title']}}</td>
            <td>{{$post['post']}}</td>
            <td><a href="{{action('CRUDController@edit', $post['id'])}}" class="btn btn-warning">Edit</a></td>
            <td><a href="{{action('CRUDController@destroy', $post['id'])}}" class="btn btn-danger">Delete</a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
      </div>
    @endsection


Step 16: Create an edit function to pass the data to the edit view.
    <?php

    // CRUDController.php  
    /**
        * Show the form for editing the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function edit($id)
        {
            $crud = Crud::find($id);
            
            return view('crud.edit', compact('crud','id'));

        }
Step 17: Create an edit view.
      <!-- edit.blade.php -->

      @extends('master')
      @section('content')
      <div class="container">
        <form method="post" action="{{action('CRUDController@update', $id)}}">
          <div class="form-group row">
            {{csrf_field()}}
            <input name="_method" type="hidden" value="PATCH">
            <label for="lgFormGroupInput" class="col-sm-2 col-form-label col-form-label-lg">Title</label>
            <div class="col-sm-10">
              <input type="text" class="form-control form-control-lg" id="lgFormGroupInput" placeholder="title" name="title" value="{{$crud->title}}">
            </div>
          </div>
          <div class="form-group row">
            <label for="smFormGroupInput" class="col-sm-2 col-form-label col-form-label-sm">Post</label>
            <div class="col-sm-10">
              <textarea name="post" rows="8" cols="80">{{$crud->post}}</textarea>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-2"></div>
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
      @endsection
    Here we have used one more hidden field called _method, which will be a PATCH request to the server so that we can update the data very quickly.

Step 18: Code update() in the CRUDController.
    <?php
    // CRUDController.php

    /**
        * Update the specified resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
        public function update(Request $request, $id)
        {
            $crud = Crud::find($id);
            $crud->title = $request->get('title');
            $crud->post = $request->get('post');
            $crud->save();
            return redirect('/crud');
        }
      After filling the update form, we can see the index page that our data is updated. So now only Delete functionality is remaining.

Step 19: Create a delete form to delete the data.
      <!-- index.blade.php -->

      @extends('master')
      @section('content')
        <div class="container">
          <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Post</th>
              <th colspan="2">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($cruds as $post)
            <tr>
              <td>{{$post['id']}}</td>
              <td>{{$post['title']}}</td>
              <td>{{$post['post']}}</td>
              <td><a href="{{action('CRUDController@edit', $post['id'])}}" class="btn btn-warning">Edit</a></td>
              <td>
                <form action="{{action('CRUDController@destroy', $post['id'])}}" method="post">
                  {{csrf_field()}}
                  <input name="_method" type="hidden" value="DELETE">
                  <button class="btn btn-danger" type="submit">Delete</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        </div>
      @endsection


Step 20: Code the destroy() method in the CRUDController.

      <?php
      // CRUDController.php

      /**
          * Remove the specified resource from storage.
          *
          * @param  int  $id
          * @return \Illuminate\Http\Response
          */
          public function destroy($id)
          {
            $crud = Crud::find($id);
            $crud->delete();

            return redirect('/crud');
          }
      Here in delete functionality, we have not put the any confirm box, but it is okay for the demo. I just want you to take this demo and see how the laravel’ flow is working.

      Output
      Laravel 5.4 Crud Example From Scratch
      Laravel 5.4 Crud Example From Scratch
      There is some routes list in resource controller. Type in the terminal: php artisan route:list

$ php artisan route:list
+--------+-----------+------------------+--------------+---------------------------------------------+--------------+
| Domain | Method    | URI              | Name         | Action                                      | Middleware   |
+--------+-----------+------------------+--------------+---------------------------------------------+--------------+
|        | GET|HEAD  | /                |              | Closure                                     | web          |
|        | GET|HEAD  | api/user         |              | Closure                                     | api,auth:api |
|        | GET|HEAD  | crud             | crud.index   | App\Http\Controllers\CRUDController@index   | web          |
|        | POST      | crud             | crud.store   | App\Http\Controllers\CRUDController@store   | web          |
|        | GET|HEAD  | crud/create      | crud.create  | App\Http\Controllers\CRUDController@create  | web          |
|        | GET|HEAD  | crud/{crud}      | crud.show    | App\Http\Controllers\CRUDController@show    | web          |
|        | PUT|PATCH | crud/{crud}      | crud.update  | App\Http\Controllers\CRUDController@update  | web          |
|        | DELETE    | crud/{crud}      | crud.destroy | App\Http\Controllers\CRUDController@destroy | web          |
|        | GET|HEAD  | crud/{crud}/edit | crud.edit    | App\Http\Controllers\CRUDController@edit    | web          |
If you are curious how resource controller works behind the scenes, then this is your answer.

Github: https://github.com/KrunalLathiya/Laravel54
 

Steps to use Github code
Clone the repo in your local.
Go to root of the project and run command “composer update“
Edit .env file and use your MySQL database credentials.
Run the command “php artisan migrate“
Now, we need to bootstrap Laravel server so run “php artisan serve“
If now go to this URL: http://localhost:8000/crud/create