<?php

namespace Dayscore\Http\Controllers;

use Dayscore\Optafeed;
use Illuminate\Http\Request;
use Dayscore\Http\Requests;
use Dayscore\Http\Controllers\Controller;

class OptafeedsController extends Controller
{

    public function __construct()
    {
        $this->middleware( 'auth', ['except' => ['store']] );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $optafeeds = Optafeed::all();
        return view('optafeeds.index', compact('optafeeds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('optafeeds.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $headers = apache_request_headers();
//        dd($request->input());
        $post_data = file_get_contents('php://input');
        $posts = array(
            'feedType' => isset($headers['x-meta-feed-type']) ? $headers['x-meta-feed-type'] : '',
            'feedParameters' => isset($headers['x-meta-feed-parameters']) ? $headers['x-meta-feed-parameters'] : '',
            'defaultFilename' => isset($headers['x-meta-default-filename']) ? $headers['x-meta-default-filename'] : '',
            'deliveryType' => isset($headers['x-meta-game-id']) ? $headers['x-meta-game-id'] : '',
            'messageMD5' => md5($post_data),
            'competitionId' => isset($headers['x-meta-competition-id']) ? $headers['x-meta-competition-id'] : '',
            'seasonId' => isset($headers['x-meta-season-id']) ? $headers['x-meta-season-id'] : '',
            'gameId' => isset($headers['x-meta-game-id']) ? $headers['x-meta-game-id'] : '',
            'gameSystemId' => isset($headers['x-meta-gamesystem-id']) ? $headers['x-meta-gamesystem-id'] : '',
            'matchday' => isset($headers['x-meta-matchday']) ? $headers['x-meta-matchday'] : '',
            'awayTeamId' => isset($headers['x-meta-away-team-id']) ? $headers['x-meta-away-team-id'] : '',
            'homeTeamId' => isset($headers['x-meta-home-team-id']) ? $headers['x-meta-home-team-id'] : '',
            'gameStatus' => isset($headers['x-meta-game-status']) ? $headers['x-meta-game-status'] : '',
            'language' => isset($headers['x-meta-language']) ? $headers['x-meta-language'] : '',
            'productionServer' => isset($headers['x-meta-production-server']) ? $headers['x-meta-production-server'] : '',
            'productionServerTimeStamp' => isset($headers['x-meta-production-server-timestamp']) ? $headers['x-meta-production-server-timestamp'] : '',
            'productionServerModule' => isset($headers['x-meta-production-server-module']) ? $headers['x-meta-production-server-module'] : '',
            'mimeType' => isset($headers['x-meta-mime-type']) ? $headers['x-meta-mime-type'] : 'text/xml',
            'encoding' => isset($headers['x-meta-encoding']) ? $headers['x-meta-encoding'] : '',
            'sportId' => isset($headers['x-meta-sport-id']) ? $headers['x-meta-sport-id'] : '',
            'contentLength' => isset($headers['x-content-length']) ? $headers['x-content-length'] : '',
            'metaId' => isset($headers['x-meta-id']) ? $headers['x-meta-id'] : '',
            'feedId' => isset($headers['x-feed_id']) ? $headers['x-feed_id'] : '',
            'dateCreated' => isset($headers['x-meta-date-created']) ? $headers['x-meta-date-created'] : '',
            'messageDigest' => isset($headers['x-meta-message-digest']) ? $headers['x-meta-message-digest'] : '',
            'content' => $post_data
        );
        Optafeed::create($posts);
        return ["success"];
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
