<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Category;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $search = $request->search;
    $criteria = $request->criteria;

    if ($search == '') {
      $categories = Category::orderBy('id', 'desc')->paginate(3);
    } else {
      // El % $var % son comodien que indican que el texto puede estar al inicio, centro o al final
      $categories = Category::where($criteria, 'like', '%'. $search .'%')->orderBy('id', 'desc')->paginate(3);
    }

    return [
      'pagination' => [
        'total'        => $categories->total(),
        'current_page' => $categories->currentPage(),
        'per_page'     => $categories->perPage(),
        'last_page'    => $categories->lastPage(),
        'from'         => $categories->firstItem(),
        'to'           => $categories->lastItem(),
      ],
      'categories' => $categories
    ];
  }

  // Obtengo las categorias activas
  function getCategories(Request $request)
  {
    $categories = Category::where('active' , '1')
                              ->select('id', 'name')
                                ->orderBy('name', 'desc')->get();
    return ['categories' => $categories];
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $category = new Category();
    $category->name = $request->name;
    $category->description = $request->description;
    $category->active = '1';
    $category->save();
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
  public function update(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $category = Category::findOrFail($request->id);
    $category->name = $request->name;
    $category->description = $request->description;
    $category->active = '1';
    $category->save();
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

  public function activate(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $category = Category::findOrFail($request->id);
    $category->active = '1';
    $category->save();
  }

  public function deactivate(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $category = Category::findOrFail($request->id);
    $category->active = '0';
    $category->save();
  }
}
