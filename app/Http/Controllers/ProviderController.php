<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Provider;
use App\Person;

class ProviderController extends Controller
{

  function index(Request $request)
  {
    $search = $request->search;
    $criteria = $request->criteria;

    if ($search == '') {
      $persons = Provider::join('persons', 'providers.id', '=', 'persons.id')
                            ->select('persons.id', 'persons.name', 'persons.document_type', 'persons.document_num',
                                     'persons.direction', 'persons.phone', 'persons.email', 'persons.active',
                                     'providers.contact', 'providers.contact_phone')
                              ->orderBy('persons.id', 'desc')->paginate(3);
    } else {
      // El % $var % son comodines que indican que el texto puede estar al inicio, centro o al final
      $persons = Provider::join('persons', 'providers.id', '=', 'persons.id')
                            ->select('persons.id', 'persons.name', 'persons.document_type', 'persons.document_num',
                                     'persons.direction', 'persons_phone', 'persons.email', 'providers.contact', 'persons.active',
                                     'providers.contact_phone')
                              ->where('persons.'.$criteria, 'like', '%'. $search .'%')
                                ->orderBy('persons.id', 'desc')->paginate(3);
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

  function store(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');

    try {
      DB::beginTransaction();
      $person = new Person();
      $person->name = $request->name;
      $person->document_type = $request->document_type;
      $person->document_num = $request->document_num;
      $person->direction = $request->direction;
      $person->phone = $request->phone;
      $person->email = $request->email;
      $person->active = '1';
      $person->save();

      $provider = new Provider();
      $provider->contact = $request->contact;
      $provider->contact = $request->contact_phone;
      $provider->id = $person->id;
      $provider->save();

      DB::commit();
    } catch(Exception $e){
      BD::rollBack();
    }
  }

  function update(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');

    try {
      DB::beginTransaction();
      // Buscamos el proveedor a modificar
      $provider = Provider::findOrFail($request->id);
      $person = Person::findOrFail($provider->id);

      $person->name = $request->name;
      $person->document_type = $request->document_type;
      $person->document_num = $request->document_num;
      $person->direction = $request->direction;
      $person->phone = $request->phone;
      $person->email = $request->email;
      $person->active = '1';
      $person->save();

      $provider->contact = $request->contact;
      $provider->contact = $request->contact_phone;
      $provider->save();

      DB::commit();
    } catch(Exception $e){
      BD::rollBack();
    }
  }

  function activate(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $person = Person::findOrFail($request->id);
    $person->active = '1';
    $person->save();
  }

  function deactivate(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $person = Person::findOrFail($request->id);
    $person->active = '0';
    $person->save();
  }

}
