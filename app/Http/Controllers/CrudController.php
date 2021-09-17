<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crud;

class CrudController extends Controller
{

    public function showData(){
        // $showData = Crud::all();
        $showData = Crud::paginate(5);
        //$showData = Crud::simplepaginate(1);
        return view('show_data',compact('showData'));
    }


    public function addData(){
        return view('add_data');
    }


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

        $crud = new Crud();
        $crud->name = $request->name;
        $crud->email = $request->email;
        $crud->save();
        //Session::flash('message','This is a message!'); 
        $request->session()->flash('msg', 'Data Added Successfully');
        return redirect('/');
        //return redirect()->back();
    }


    public function editData($id=null){
        $crud_edit = Crud::find($id);
        return view('edit_data',compact('crud_edit'));
    }
    

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

    public function deleteData($id=null){
        $deleteData = Crud::find($id);
        $deleteData->delete();
        session()->flash('msg', 'Data Deleted Successfully');
        return redirect('/');
    }

}
