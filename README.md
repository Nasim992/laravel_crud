## Laravel Basic Crud Application

###### 1. Installing Laravel via composer

###### 2. Creating database on mysql 

###### 3. Changing .env file on database name

###### 4. make model by command and migration(php artisan make:model modelName -m)

###### 5. inside app/providers/appserviceprovider.php (https://laravel.com/docs/8.x/migrations#index-lengths-mysql-mariadb copy index length and paste on the boot() and alson add schema header.

```javascript 
        use Illuminate\Support\Facades\Schema;
        /**
         * Bootstrap any application services.
         *
         * @return void
         */
        public function boot()
        {
            Schema::defaultStringLength(191); // For making Schema
        }
        )
```

###### 6. Create tables inside database  database/migrations/2021_09_17_021417_create_cruds_table.php
  inside up() function create table name with datatype.

```javascript 
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->timestamps();
```

###### 7. Migrate the table (php artisan migrate)

###### 8. Create Controller (php artisan make:controller controllerName)
          This Controller file available on app/Http/Controllers/ControllerName.php

###### 9. routes/web.php  (Showing Data form database make a Routes called showing data)
```javascript 
            For Showing Data
            Route::get('/',[CrudController::class,'showData']);
                Now, Inside App/Http/Controllers/CrudController.php
                Make Functions 
            public function showData(){
                    return view('show_data');
    }

    Now, Create View File inside --> resources/views/show_data.blade.php
        Now create , another routes for adding data to the table 

            Route::get('/addData',[CrudController::class,'addData']);
        Inside CrudControllers make a functions about addData();

        Next,Create a page inside resources/viewes/add_data.blade.php

```
###### 10. routes/web.php  (Adding Data form database make a Routes called adding data)
```javascript 
                Now create , another routes for adding data to the table

                    Route::get('/addData',[CrudController::class,'addData']);

                Inside CrudControllers make a functions about addData();

                Next,Create a page inside resources/viewes/add_data.blade.php

                Inside form we have to use csrf token   @csrf

                    For Storing Data to database we make another routes 
                    inside->routes/web.php Route::post('/store-data',[CrudController::class,'storeData']);
                    and, 
                    inside App/Http/Controllers/CrudController.php
                    public function storeData(Request $request){
                        return $request->all;
                    }
```
###### 11. For linking one page to another page {{url('/(name if available)')}}

###### 12. For Validation of input field 

```javascript 
            [Link](https://laravel.com/docs/8.x/validation#introduction
            )

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            or,
            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            For Customizing Error Message 
            [Error Link](https://laravel.com/docs/8.x/validation#introduction)
                
                Add this to the top ---> use Illuminate\Http\Request;
                
                public function storeData(Request $request){
                    $rules =[
                        'name'=>'required|max:10',
                        'email'=>'required|email',
                    ];
                    $cm = [
                        'name.required'=>"Enter Your Name",
                        'name.max'=>'Your name cannot contain more than 10 characters',
                        'email.required'=>"Email is required",
                        'email.email'=>'Email must be a valid email',
                    ];
                    $this->validate($request,$rules,$cm);
                    return $request->all;
                }
```
###### 13. For Inserting Data to Mysql database

```javascript 

     For Inserting Data Inside the Crud Controller

    Add to the top ----> use App\Models\Crud;

        public function storeData(Request $request){
            $rules =[
                'name'=>'required|max:10',
                'email'=>'required|email',
            ];
            $cm = [
                'name.required'=>"Enter Your Name",
                'name.max'=>'Your name cannot contain more than 10 characters',
                'email.required'=>"Email is required",
                'email.email'=>'Email must be a valid email',
            ];
            $this->validate($request,$rules,$cm);

        //    Inseritng Data On Database
            $crud = new Crud();
            $crud->name = $request->name;
            $crud->email = $request->email;
            $crud->save();
            $request->session()->flash('msg', 'Data Added Successfully'); // Add Success Message To the Session Variable
            return redirect()->back(); // Back to the previous url After Insertion
            return redirect('/'); // Return back to the main url
        }

    For Printing Session Variable ->

    [Session Link](https://laravel.com/docs/8.x/responses#redirecting-with-flashed-session-data)
                @if (session('msg'))
                    <div class="alert alert-success">
                        {{ session('msg') }}
                    </div>
                @endif
```

###### 13. For Showing All data form  Mysql database into table

```javascript 

  1. inside --> app/Http/Controller/CrudController.php   
    public function showData(){
        $showData = Crud::all();
        return view('show_data',compact('showData'));
    }

    For Displaying on the table view --> inside resources/view/show_data.blade.php
 2.     @foreach($showData as $key=>$data)
            <tr class="table-active">
              <td>{{ $key+1 }}</td>
              <td>{{ $data->name }}</td>
              <td>{{ $data->email }}</td>
              <td>
              edit | delete
              </td>
           </tr>
       @endforeach
```

###### 14. For Pagination on Table

```javascript 

    1. inside app/Http/Controllers/CrudController.php 
        public function showData(){
            // $showData = Crud::all();
            $showData = Crud::paginate(1);
            //$showData = Crud::simplepaginate(1);
            return view('show_data',compact('showData'));
        }
    

   [pagination links](https://laravel.com/docs/8.x/pagination#simple-pagination)

   2. inside app/Providers/AppServiceProvider.php
   [Paginator Using Bootstrap](https://laravel.com/docs/8.x/pagination#using-bootstrap)

   use Illuminate\Pagination\Paginator;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
```
###### 14. For Edit Data on the table find()

```javascript 

0. <a href="{{ url('/edit-data/'.$data->id) }}" class="btn btn-info btn-sm">Edit</a>
 
1. Routes/web.php 
   Route::get('/edit-data/{id}',[CrudController::class,'editData']);

2. app/Http/Controllers/CrudController.php 
    public function editData($id=null){
        $crud_edit = Crud::find($id);
        return view('edit_data',compact('crud_edit'));
    }
3. resources/views/edit_data.blade.php  showing on edited value value="{{$crud_edit->name}}"

4. Routes/web.app routes
   Route::post('/update-data/{id}',[CrudController::class,'updateData']);

5. Inside CrudController --> 
     public function updateData(Request $request,$id){
        $rules =[
            'name'=>'required|max:10',
            'email'=>'required|email',
        ];
        $cm = [
            'name.required'=>"Enter Your Name",
            'name.max'=>'Your name cannot contain more than 10 characters',
            'email.required'=>"Email is required",
            'email.email'=>'Email must be a valid email',
        ];
        $this->validate($request,$rules,$cm);

        $crud = Crud::find($id);
        $crud->name = $request->name;
        $crud->email = $request->email;
        $crud->save();

        $request->session()->flash('msg', 'Data Updated Successfully');
        return redirect('/');
        //return redirect()->back();
    }
```

###### 14. For Delete Data on the table delete()

```javascript 

<a href="{{ url('/delete-data/'.$data->id) }}" onclick="return confirm('Are you sure you want to delete this?')" class="btn btn-danger btn-sm">Delete</a>

1. Routes/web.php:
    Route::get('/delete-data/{id}',[CrudController::class,'deleteData']);
2. CrudController.php :
    public function deleteData($id=null){
        $deleteData = Crud::find($id);
        $deleteData->delete();
        session()->flash('msg', 'Data Deleted Successfully');
        return redirect('/');
    }
```