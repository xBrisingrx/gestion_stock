<?php

namespace App\Http\Controllers;

use App\User;
use App\Person;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class UserController extends Controller
{
  function index(Request $request)
  {
    $search = $request->search;
    $criteria = $request->criteria;

    if ($search == '') {
      $persons = User::join('persons', 'users.id', '=', 'persons.id')
                        ->join('roles', 'users.id', '=', 'roles.id')
                            ->select('persons.id', 'persons.name', 'persons.document_type', 'persons.document_num',
                                     'persons.direction', 'persons.phone', 'persons.email',
                                     'users.user_name','users.password','users.rol_id', 'users.active',
                                     'roles.name as rol_name')
                              ->orderBy('persons.id', 'desc')->paginate(3);
    } else {
      // El % $var % son comodines que indican que el texto puede estar al inicio, centro o al final
      $persons = User::join('persons', 'users.id', '=', 'persons.id')
                        ->join('roles', 'users.rol_id', '=', 'roles.id')
                            ->select('persons.id', 'persons.name', 'persons.document_type', 'persons.document_num',
                                     'persons.direction', 'persons.phone', 'persons.email',
                                     'users.user_name','users.password','users.rol_id', 'users.active',
                                     'roles.name as rol_name')
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

      $user = new User();
      $user->user_name = $request->user_name;
      $user->password = bcrypt($request->password);
      $user->id = $person->id;
      $user->active = '1';
      $user->rol_id = $request->rol_id;
      $user->save();

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
      $user = User::findOrFail($request->id);
      $person = Person::findOrFail($user->id);

      $person->name = $request->name;
      $person->document_type = $request->document_type;
      $person->document_num = $request->document_num;
      $person->direction = $request->direction;
      $person->phone = $request->phone;
      $person->email = $request->email;
      $person->active = '1';
      $person->save();

      $user->user_name = $request->user_name;
      $user->password = bcrypt($request->password);
      $user->id = $person->id;
      $user->rol_id = $request->rol_id;

      DB::commit();
    } catch(Exception $e){
      BD::rollBack();
    }
  }

  function activate(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $user = User::findOrFail($request->id);
    $user->active = '1';
    $user->save();
  }

  function deactivate(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $user = User::findOrFail($request->id);
    $user->active = '0';
    $user->save();
  }
}
