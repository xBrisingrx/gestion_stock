<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
  function index(Request $request)
  {
    $search = $request->search;
    $criteria = $request->criteria;

    if ($search == '') {
      $articles = Article::join('categories', 'articles.category_id', '=', 'categories.id')
      											->select('articles.id', 'articles.category_id', 'articles.code', 'articles.name',
      																'articles.sale_price', 'articles.stock', 'articles.description', 'articles.active',
      																'categories.name as category_name')
      												->orderBy('articles.id', 'desc')->paginate(3);
    } else {
      // El % $var % son comodien que indican que el texto puede estar al inicio, centro o al final
      $articles = Article::join('categories', 'articles.category_id', '=', 'categories.id')
      											->select('articles.id', 'articles.category_id', 'articles.code', 'articles.name',
      																'articles.sale_price', 'articles.stock', 'articles.description', 'articles.active',
      																'categories.name as category_name')
      												->where('articles.'.$criteria, 'like', '%'. $search .'%')
      													->orderBy('articles.id', 'desc')->paginate(3);
    }

    return [
      'pagination' => [
        'total'        => $articles->total(),
        'current_page' => $articles->currentPage(),
        'per_page'     => $articles->perPage(),
        'last_page'    => $articles->lastPage(),
        'from'         => $articles->firstItem(),
        'to'           => $articles->lastItem(),
      ],
      'articles' => $articles
    ];
  }

  function store(Request $request)
  {
      $article = new Article();
      $article->category_id = $request->category_id;
      $article->code = $request->code;
      $article->name = $request->name;
      $article->sale_price = $request->sale_price;
      $article->stock = $request->stock;
      $article->description = $request->description;
      $article->active = '1';
      $article->save();
  }

  function update(Request $request)
  {
    $article = Article::findOrFail($request->id);
      $article->category_id = $request->category_id;
      $article->code = $request->code;
      $article->name = $request->name;
      $article->sale_price = $request->sale_price;
      $article->stock = $request->stock;
      $article->description = $request->description;
      $article->active = '1';
    $article->save();
  }

  function activate(Request $request)
  {
    $article = Article::findOrFail($request->id);
    $article->active = '1';
    $article->save();
  }

  function deactivate(Request $request)
  {
    $article = Article::findOrFail($request->id);
    $article->active = '0';
    $article->save();
  }
}
