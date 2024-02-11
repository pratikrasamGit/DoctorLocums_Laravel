<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;
use App\Models\NuRole;
use App\Models\NuPermission;
use Illuminate\Support\Facades\Auth;

class KeywordController extends Controller
{
    public function __construct()
    {
        $this->pageSet = [
            'pagename'=>'Keywords',
            'menuTag'=>'Keywords',
            'menuHead'=>'',
            'actionHed'=>'keywords',
            'actionTyp'=>'List',
            'actionID'=>0
        ];

        // $this->middleware('permission:admin-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:admin-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:admin-show', ['only' => ['index']]);
        // $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('permission:admin-delete', ['only' => ['destroy']]);
        $this->middleware('role:Administrator|Admin');
        $this->roles = NuRole::all();
        $this->user = Auth::user();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keys = Keyword::all();
    
        return view('admin.keywords.index',['ps'=>$this->pageSet,'keys'=>$keys,'user'=>$this->user]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $keywordFilters = $this->getKeywordOptions();
        return view('admin.keywords.create',['ps'=>$this->pageSet,'user'=>$this->user,'keywordFilters' => $keywordFilters]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'filter'=>'required',
            'title'=>'required'
        ]);

        $key = new Keyword($request->toArray());
        $key->__set('created_by', Auth::user()->id);    
        $key->save();
        if($request->has('keywordpic')){
            $key
                ->addMediaFromRequest('keywordpic')
                ->toMediaCollection('keywordpic');
        }

        return redirect()->route('keywords.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Keyword  $keyword
     * @return \Illuminate\Http\Response
     */
    public function show(Keyword $keyword)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Keyword  $keyword
     * @return \Illuminate\Http\Response
     */
    public function edit(Keyword $keyword)
    {   
        $keywordFilters = $this->getKeywordOptions();
        return view('admin.keywords.edit',['ps'=>$this->pageSet,'key'=>$keyword,'keywordFilters' => $keywordFilters]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Keyword  $keyword
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Keyword $keyword)
    {
        $validated = $request->validate([
            'filter'=>'required',
            'title'=>'required'
        ]);

        $keyword->update($request->all());

        if($request->has('keywordpic')){
            $keyword
                ->addMediaFromRequest('keywordpic')
                ->toMediaCollection('keywordpic');
        }

        return redirect()->route('keywords.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Keyword  $keyword
     * @return \Illuminate\Http\Response
     */
    public function destroy(Keyword $keyword)
    {
        //
    }
}
