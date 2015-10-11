<?php

namespace Dayscore\Http\Controllers;

use Dayscore\FixtureTest;
use Illuminate\Http\Request;
use Dayscore\Http\Requests;
use Dayscore\Http\Controllers\Controller;
use Kamaln7\Toastr\Facades\Toastr;

class FixtureTestsController extends Controller
{
    public function __construct()
    {
        $this->middleware( 'auth' );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fixtures = $request->user()->fixtureTests;
        return view('fixturetests.index',compact('fixtures'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fixturetests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $request->user()->fixtureTests()->create($data);
        Toastr::success("Fixture creado correctamente!");
        return redirect('fixturetests');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(  FixtureTest $fixture, $option = "")
    {
//        if ( $id ) $fixtureTest = FixtureTest::find( $id );
//        $option = "teams";
        return view('fixturetests.show',compact('fixture','option','id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FixtureTest $fixture)
    {
        return view('fixturetests.edit',compact('fixture'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FixtureTest $fixture)
    {
        $data = $request->all();
        if(!isset($data['classicsRound']))$data['classicsRound'] = 0;
        if($fixture->size != $data['size'])Toastr::info("Ha cambiado el número de equipos.");
        $fixture->update($data);
        $teams = $fixture->teams();
        if($teams->count() < $fixture->size){
            for($i=$teams->count()+1; $i<=$fixture->size;$i++){
                $fixture->teams()->create(["name"=>"Equipo ".$i,"order"=>$i]);
            }
        }
        Toastr::success("Fixture actualizado correctamente!");
        return redirect('fixturetests');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FixtureTest $fixture)
    {
        $id = $fixture->id;
        $fixture->delete();
        return ['id'=>$id];
    }
}
