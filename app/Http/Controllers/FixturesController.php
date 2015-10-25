<?php

namespace Dayscore\Http\Controllers;

use Dayscore\Fixtures\Team;
use Dayscore\Http\Requests;
use Illuminate\Http\Request;
use Dayscore\Fixtures\Fixture;
use Kamaln7\Toastr\Facades\Toastr;

class FixturesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fixtures = $request->user()->fixtures;
        return view('fixtures.index', compact('fixtures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fixtures.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if (!isset($data['classicsRound'])) $data['classicsRound'] = 0;
        $fixture = $request->user()->fixtures()->create($data);
        for ($i = 1; $i <= $fixture->size; $i++) {
            $fixture->teams()->create(["name" => "Equipo " . $i, "order" => $i]);
        }
        Toastr::success("Fixture y equipos creados correctamente!");
        return redirect('fixtures');
    }

    /**
     * Display the specified resource.
     *
     * @param Fixture $fixture
     * @param string $option
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Fixture $fixture, $option = "")
    {
        return view('fixtures.show', compact('fixture', 'option'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Fixture $fixture
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit(Fixture $fixture)
    {
        return view('fixtures.edit', compact('fixture'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Fixture $fixture
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, Fixture $fixture)
    {
        $data = $request->all();
        if (!isset($data['classicsRound'])) $data['classicsRound'] = 0;
        if ($fixture->size != $data['size']) Toastr::info("Ha cambiado el número de equipos.");
        $fixture->update($data);
        $teams = $fixture->teams;
        for ($i = $teams->count() + 1; $i <= $fixture->size; $i++) {
            $fixture->teams()->create(["name" => "Equipo " . $i, "order" => $i]);
        }
        while($fixture->size < $teams->count()) {
            Toastr::info("Eliminar Equipo");
            $team = $teams->pop();
            $team->delete();
        }
        Toastr::success("Fixture actualizado correctamente!");
        return redirect('fixtures');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Fixture $fixture
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @internal param int $id
     */
    public function destroy(Fixture $fixture)
    {
        $id = $fixture->id;
        $fixture->delete();
        return ['id' => $id];
    }
}
