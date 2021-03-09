<?php

namespace App\Http\Controllers;

use App\Poste;
use App\Type;
use App\Categorie;
use Auth;
use Illuminate\Http\Request;
use Validator;
use Storage;
use Carbon\Carbon;

class PosteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tab = Poste::all();
        return view('accueil', compact('tab'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tab = Type::all();
        $categorie = Categorie::all();
        return view('PosteCreate', compact('tab', 'categorie'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->hasFile('profile_image'))
        {
            $filenamewithextension = $request->file('profile_image')->getClientOriginalName();

            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
      
            //get file extension
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $time = time();
            //filename to store
            $filenametostore = $filename.'_'.$time.'.'.$extension;

            $request->file('profile_image')->storeAs('public/profile_images', $filenametostore);

            //$path = Storage::putFile('public/profile_images', $request->file('profile_image'));
            //$path = $request->file('profile_image')->getClientOriginalName();


            $p = new Poste;
            $p->intitule = $request->input('intitule');
            $p->description = $request->input('description');
            $p->ville = $request->input('ville');
            $p->nomEntreprise = $request->input('nomEntreprise');
            $p->pdf = $filenametostore;
            $p->isValide = 1;
            $p->type_id = $request->input('type');
            $p->categorie_id = $request->input('categorie');
            $p->user_id = Auth::user()->id;
            $p->save();
      
            return redirect()->route('poste.index')->with('success', "Poste publié.");
        }
        else
        {


            $p = new Poste;
            $p->intitule = $request->input('intitule');
            $p->description = $request->input('description');
            $p->ville = $request->input('ville');
            $p->nomEntreprise = $request->input('nomEntreprise');
            $p->pdf = "";
            $p->isValide = 1;
            $p->type_id = $request->input('type');
            $p->categorie_id = $request->input('categorie');
            $p->user_id = Auth::user()->id;
            $p->save();
      
            return redirect()->route('poste.index')->with('success', "Poste publié.");
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function show(Poste $poste)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function edit(Poste $poste)
    {
        $p = Poste::find($poste->id);
        $tab = Type::all();
        $categorie = Categorie::all();
        return view('PosteEdit', compact('p', 'tab', 'categorie'));
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Poste $poste)
    {
        if($request->hasFile('profile_image'))
        {
            
            $filenamewithextension = $request->file('profile_image')->getClientOriginalName();

            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
      
            //get file extension
            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $time = time();
            //filename to store
            $filenametostore = $filename.'_'.$time.'.'.$extension;

            $request->file('profile_image')->storeAs('public/profile_images', $filenametostore);
            
        $p = Poste::find($poste->id);
        
        $p->intitule = $request->input('intitule');
        $p->description = $request->input('description');
        $p->nomEntreprise = $request->input('nomEntreprise');
        $p->pdf = $filenametostore;
        $p->ville = $request->input('ville');
        $p->type_id = $request->input('type');
        $p->categorie_id = $request->input('categorie');
        
        $p->save();
        return redirect()->route('poste.index');
        }
        else
        {
           
            $p = Poste::find($poste->id);
            $p->intitule = $request->input('intitule');
            $p->description = $request->input('description');
            $p->nomEntreprise = $request->input('nomEntreprise');
            $p->pdf = "";
            $p->ville = $request->input('ville');
            $p->type_id = $request->input('type');
            $p->categorie_id = $request->input('categorie');
            $p->save();
            return redirect()->route('poste.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Poste  $poste
     * @return \Illuminate\Http\Response
     */
    public function destroy(Poste $poste)
    {
        $p = Poste::find($poste->id);
        Storage::delete('public/profile_images/'.$p->pdf);
        $p->delete($p);
        return redirect()->route('poste.index');
    }
}
