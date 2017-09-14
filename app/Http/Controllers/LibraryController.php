<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Books;
use App\Payments;
use App\User;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array(
            'books' => Books::get()->count(),
            'payments' => Payments::get()->sum('amount'),
            'registration'=> User::get()->count()
        );
        $books = Books::get()->count();
        

        return view('dashboard')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=> 'required',
            'author' => 'required',
            'isbn'=>'required'

        ]);

       $book = new Books;
       $book->title = $request->input('title');
       $book->author = $request->input('author');
       $book->isbn = $request->input('isbn'); 
       $book->save();

       return redirect('dashboard/create')->with('success', 'Book successfuly added'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $books = Books::orderBy('title','desc')->paginate(10);
        
        return view('show')->with('books', $books);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $books = Books::find($id);
       return view('payment')->with('books', $books);
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
        $this->validate($request,[
            'book_id'=> 'required',
            'amount' => 'required'
            

        ]);

       $payment = new Payments;
       
       $payment->book_id = $request->input('book_id');
       
       $payment->amount = $request->input('amount'); 
       $payment->save();

       return redirect('dashboard/payment')->with('success', 'Successfuly borrowed'); 
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $books = Books::find($id);
        $books->delete();

        return redirect('dashboard/show')->with('success', 'Book successfuly deleted'); 
    }
}
