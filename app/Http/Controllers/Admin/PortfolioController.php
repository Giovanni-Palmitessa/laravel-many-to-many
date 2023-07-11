<?php

namespace App\Http\Controllers\Admin;

use App\Models\Type;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PortfolioController extends Controller
{
    private $validations = [
        'name' => 'required|string|max:100|min:5',
        'client_name' => 'required|string|max:100|min:5',
        'type_id' => 'required|integer|exists:types,id',
        'url_image' => 'required|url|max:400',
        'pickup_date' => 'required|date',
        'deploy_date' => 'required|date',
        'description' => 'required|string',
    ];

    private $validations_messages = [
        'required' => 'Il campo :attribute è richiesto',
        'min' => 'Il campo :attribute deve avere almeno :min caratteri',
        'max' => 'Il campo :attribute deve avere massimo :max caratteri',
        'url' => 'Il campo :attribute deve essere un URL valido',
        'date' => 'Il campo :attribute deve essere una data in formato valido',
        'exists' => 'Il campo :attribute non è valido',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $portfolios = Portfolio::paginate(10);

        return view('admin.portfolios.index', compact('portfolios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        return view('admin.portfolios.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validare i dati del form
        $request->validate($this->validations, $this->validations_messages);

        $data = $request->all();


        // salvare i dati nel db se validi
        $newPortfolio = new Portfolio();
        $newPortfolio->name = $data['name'];
        $newPortfolio->client_name = $data['client_name'];
        $newPortfolio->type_id = $data['type_id'];
        $newPortfolio->url_image = $data['url_image'];
        $newPortfolio->pickup_date = $data['pickup_date'];
        $newPortfolio->deploy_date = $data['deploy_date'];
        $newPortfolio->description = $data['description'];

        $newPortfolio->save();

        // reindirizzare su una rotta di tipo get

        return to_route('admin.portfolios.show', ['portfolio' => $newPortfolio]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Portfolio  $portfolio
     * @return \Illuminate\Http\Response
     */
    public function show(Portfolio $portfolio)
    {
        return view('admin.portfolios.show', compact('portfolio'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Portfolio  $portfolio
     * @return \Illuminate\Http\Response
     */
    public function edit(Portfolio $portfolio)
    {
        $types = Type::all();
        return view('admin.portfolios.edit', compact('portfolio', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Portfolio  $portfolio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        // validare i dati del form
        $request->validate($this->validations, $this->validations_messages);

        $data = $request->all();


        // salvare i dati nel db se validi
        $portfolio->name = $data['name'];
        $portfolio->client_name = $data['client_name'];
        $portfolio->type_id = $data['type_id'];
        $portfolio->url_image = $data['url_image'];
        $portfolio->pickup_date = $data['pickup_date'];
        $portfolio->deploy_date = $data['deploy_date'];
        $portfolio->description = $data['description'];

        $portfolio->update();

        // reindirizzare su una rotta di tipo get

        return to_route('admin.portfolios.show', ['portfolio' => $portfolio]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Portfolio  $portfolio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();

        return to_route('admin.portfolios.index')->with('delete_success', $portfolio);
    }
}
