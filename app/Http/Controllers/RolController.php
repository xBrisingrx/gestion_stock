<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{
  function index(Request $request)
  {
    $search   = $request->search;
    $criteria = $request->criteria;

    if ($search == '') {
      $roles = Rol::orderBy('id', 'desc')->paginate(3);
    } else {
      // El % $var % son comodien que indican que el texto puede estar al inicio, centro o al final
      $roles = Rol::where($criteria, 'like', '%'. $search .'%')->orderBy('id', 'desc')->paginate(3);
    }

    return [
      'pagination' => [
        'total'        => $roles->total(),
        'current_page' => $roles->currentPage(),
        'per_page'     => $roles->perPage(),
        'last_page'    => $roles->lastPage(),
        'from'         => $roles->firstItem(),
        'to'           => $roles->lastItem(),
      ],
      'roles' => $roles
    ];
  }

  function store(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $rol = new Rol();
    $rol->name = $request->name;
    $rol->description = $request->description;
    $rol->active = '1';
    $rol->save();
  }

  function update(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $rol = Rol::findOrFail($request->id);
    $rol->name = $request->name;
    $rol->description = $request->description;
    $rol->save();
  }

  function activate(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $rol = Rol::findOrFail($request->id);
    $rol->active = '1';
    $rol->save();
  }

  function deactivate(Request $request)
  {
    if ( !$request->ajax() ) return redirect('/');
    $rol = Rol::findOrFail($request->id);
    $rol->active = '0';
    $rol->save();
  }

  function selectRol(Request $request)
  {
    $roles = Rol::where('active', '=', '1')
                      ->select('id', 'name')
                        ->orderBy('name', 'asc')->get();
    return ['roles' => $roles];
  }
}
