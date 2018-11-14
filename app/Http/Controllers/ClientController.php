<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;

class ClientController extends Controller
{
  function index(Request $request)
  {
    $search = $request->search;
    $criteria = $request->criteria;

    if ($search == '') {
      $persons = Person::orderBy('id', 'desc')->paginate(3);
    } else {
      // El % $var % son comodines que indican que el texto puede estar al inicio, centro o al final
      $persons = Person::where($criteria, 'like', '%'. $search .'%')->orderBy('id', 'desc')->paginate(3);
    }

    return [
      'pagination' => [
        'total'        => $persons->total(),
        'current_page' => $persons->currentPage(),
        'per_page'     => $persons->perPage(),
        'last_page'    => $persons->lastPage(),
        'from'         => $persons->firstItem(),
        'to'           => $persons->lastItem(),
      ],
      'persons' => $persons
    ];
  }

  public function store(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $person = new Person();
    $person->name = $request->name;
    $person->document_type = $request->document_type;
    $person->document_num = $request->document_num;
    $person->direction = $request->direction;
    $person->phone = $request->phone;
    $person->email = $request->email;
    $person->active = '1';
    $person->save();
  }

  public function update(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $person = Person::findOrFail($request->id);
    $person->name = $request->name;
    $person->document_type = $request->document_type;
    $person->document_num = $request->document_num;
    $person->direction = $request->direction;
    $person->phone = $request->phone;
    $person->email = $request->email;
    $person->save();
  }

  public function activate(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $person = Person::findOrFail($request->id);
    $person->active = '1';
    $person->save();
  }

  public function deactivate(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $person = Person::findOrFail($request->id);
    $person->active = '0';
    $person->save();
  }

}
