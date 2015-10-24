<?php

namespace Dayscore;

use Carbon\Carbon;
use Dayscore\Opta\Country;
use Dayscore\Opta\Event;
use Dayscore\Opta\Game;
use Dayscore\Opta\Player;
use Dayscore\Opta\Region;
use Dayscore\Opta\Team;
use Dayscore\Opta\Venue;
use Illuminate\Database\Eloquent\Model;
use Kamaln7\Toastr\Facades\Toastr;
use Nathanmac\Utilities\Parser\Parser;

class Optafeed extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'feedType',
        'feedParameters',
        'defaultFilename',
        'deliveryType',
        'messageMD5',
        'competitionId',
        'seasonId',
        'gameId',
        'gameSystemId',
        'matchday',
        'awayTeamId',
        'homeTeamId',
        'gameStatus',
        'language',
        'productionServer',
        'productionServerTimeStamp',
        'productionServerModule',
        'mimeType',
        'encoding',
        'sportId',
        'contentLength',
        'metaId',
        'feedId',
        'dateCreated',
        'messageDigest',
        'content',
        'processed'
    ];
    /**
     * The attributes that should be treated as Carbon dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at','processed'];

    /**
     * Format date attribute more human friendly
     *
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        Carbon::setLocale('es');
        $result = Carbon::createFromFormat("Y-m-d H:i:s", $date, "America/Bogota");
        $result->timezone = 'America/Bogota';
        return Carbon::parse($result);
    }
    /**
     * Format date attribute more human friendly
     *
     * @param $date
     * @return string
     */
    public function getProcessedAttribute($date)
    {
        if($date){
            Carbon::setLocale('es');
            $result = Carbon::createFromFormat("Y-m-d H:i:s", $date, "America/Bogota");
            $result->timezone = 'America/Bogota';
            return Carbon::parse($result);
        } else {
            return "Sin  procesar";
        }
    }
    /**
     * Format date attribute more human friendly
     *
     * @param $date
     * @return string
     */
    public function getUpdatedAtAttribute($date)
    {
        Carbon::setLocale('es');
        return Carbon::parse($date)->diffForHumans();
    }

    public function type()
    {
        if($this->feedType == "F26") {
            return "Live Scores";
        } else if($this->feedType == "F40"){
            return "Squads Feed";
        } else if($this->feedType == "F1"){
            return "Fixtures & results";
        } else if($this->feedType == "F13"){
            return "Commentary Feed";
        } else {
            return $this->feedType;
        }
    }

    public function tournament()
    {
        $tournament = Tournament::where('opta_id', $this->competitionId)->where('opta_season', $this->seasonId)->first();
        if (!$tournament) {
            $tournament = new Tournament();
            $tournament->opta_id = $this->competitionId;
            $tournament->opta_season = $this->seasonId;
            $url = "http://futbol.dayscript.com/minamin/tournaments/tournamentopta/" . $this->competitionId . "/" . $this->seasonId;
            $json = json_decode(file_get_contents($url));
            if ($json->status == "success") {
                $tournament->id = $json->data->id;
                $tournament->name = $json->data->name;
                Toastr::success("Se ha sincronizado el torneo correctamente!");
            } else {
                Toastr::error("Ha ocurrido un error al sincronizar el torneo.");
            }
            Toastr::success($tournament->name, "Torneo creado");
        }
        $tournament->save();
        return $tournament;
    }

    public function updatePlayer($playerid, $options = [])
    {
        $player = Player::findOrNew($playerid);
        if (!$player->id) {
            $player->id = $playerid;
            $player->save();
            Toastr::success($playerid, "Jugador Creado");
        }
        $player->update($options);
    }
    public function updatePlayerTournament($playerid, $options = [])
    {
        $tournament = $this->tournament();
        if ($tournament) {
            $tournament->optaplayers()->detach($playerid);
            $tournament->optaplayers()->attach($playerid,$options);
        }

    }


    public function process()
    {
        $tournament = $this->tournament();
        $parser = new Parser();
        $content = $parser->xml($this->content);
        if ($this->feedType == "F1") {
            $this->processF1($tournament, $content["SoccerDocument"]);
            $this->processed = date("Y-m-d H:i:s");
            $this->save();
        } else if ($this->feedType == "F26") {
            $this->processF26($tournament, $content);
            $this->processed = date("Y-m-d H:i:s");
            $this->save();
            if($tournament->id == 150946 || $tournament->id == 150945 || $tournament->id == 150942
                || $tournament->id == 150944){
                $tournament->updatewidget();
            }
        } else if ($this->feedType == "F13") {
            $this->processF13($tournament, $content);
            $this->processed = date("Y-m-d H:i:s");
            $this->save();
        } else if ($this->feedType == "F40") {
            $pattern = '/\<Stat Type="([\w]+)"\>([\w- áéíóúñ]+)\<\/Stat\>/';
            $temp = preg_replace($pattern,'<$1>$2</$1>',$this->content);
            $content = $parser->xml($temp);
            $this->processF40($tournament, $content["SoccerDocument"]);
            $this->processed = date("Y-m-d H:i:s");
            $this->save();
        }

    }

    public function processF40($tournament, $content){
        foreach($content["Team"] as $team){
            $teamid = str_replace("t","",$team["@attributes"]["uID"]);
            $options =[];
            if(isset($team["Name"]))$options["name"] = $team["Name"];
            if(isset($team["FifaRank"]))$options["fifa_rank"] = $team["FifaRank"];
            if(isset($team["Founded"]))$options["founded"] = $team["Founded"];
            if(isset($team["Nickname"]))$options["nickname"] = $team["Nickname"];
            if(isset($team["SYMID"]))$options["code"] = $team["SYMID"];
            if(isset($team["@attributes"]["city"]))$options["city"] = $team["@attributes"]["city"];
            if(isset($team["@attributes"]["postal_code"]))$options["postal_code"] = $team["@attributes"]["postal_code"];
            if(isset($team["@attributes"]["short_club_name"]))$options["short_name"] = $team["@attributes"]["short_club_name"];
            if(isset($team["@attributes"]["official_club_name"]))$options["official_name"] = $team["@attributes"]["official_club_name"];
            if(isset($team["@attributes"]["street"]))$options["street"] = $team["@attributes"]["street"];
            if(isset($team["@attributes"]["email"]))$options["email"] = $team["@attributes"]["email"];
            if(isset($team["@attributes"]["fax"]))$options["fax"] = $team["@attributes"]["fax"];
            if(isset($team["@attributes"]["phone"]))$options["phone"] = $team["@attributes"]["phone"];
            if(isset($team["@attributes"]["web_address"]))$options["web"] = $team["@attributes"]["web_address"];
            if(isset($team["@attributes"]["club_colour_one"]))$options["color1"] = $team["@attributes"]["club_colour_one"];
            if(isset($team["@attributes"]["club_colour_two"]))$options["color2"] = $team["@attributes"]["club_colour_two"];
            if(isset($team["Stadium"]["@attributes"]["uID"])){
                $this->updateVenue($team["Stadium"]["@attributes"]["uID"],[
                    "name"=>isset($team["Stadium"]["Name"])?$team["Stadium"]["Name"]:"",
                    "city"=>isset($team["@attributes"]["city"])?$team["@attributes"]["city"]:null,
                    "capacity"=>isset($team["Stadium"]["Capacity"])?$team["Stadium"]["Capacity"]:null
                ]);
                $options["venue_id"] = $team["Stadium"]["@attributes"]["uID"];
            }
            if(isset($team["@attributes"]["country_id"])){
                $this->updateCountry($team["@attributes"]["country_id"],["name"=>(isset($team["@attributes"]["country"])?$team["@attributes"]["country"]:""),"iso"=>(isset($team["@attributes"]["country_iso"])?$team["@attributes"]["country_iso"]:"")]);
                $options["country_id"] = $team["@attributes"]["country_id"];
            }
            if(isset($team["@attributes"]["region_id"])){
                $this->updateRegion($team["@attributes"]["region_id"],["name"=>(isset($team["@attributes"]["region_name"])?$team["@attributes"]["region_name"]:"")]);
                $options["region_id"] = $team["@attributes"]["region_id"];
            }
            $this->updateTeam($teamid,$options);
            foreach($team["Player"] as $player){
                $playerid = str_replace("p","",$player["@attributes"]["uID"]);
                $options = [];
                if(isset($player["Name"]))$options["name"] = $player["Name"];
                if(isset($player["Position"]))$options["position"] = $player["Position"];
                if(isset($player["first_name"]))$options["first_name"] = $player["first_name"];
                if(isset($player["last_name"]))$options["last_name"] = $player["last_name"];
                if(isset($player["birth_date"]))$options["birth_date"] = $player["birth_date"];
                if(isset($player["birth_place"]))$options["birth_place"] = $player["birth_place"];
                if(isset($player["first_nationality"]))$options["first_nationality"] = $player["first_nationality"];
                if(isset($player["preferred_foot"]))$options["preferred_foot"] = $player["preferred_foot"];
                if(isset($player["weight"]))$options["weight"] = $player["weight"];
                if(isset($player["height"]))$options["height"] = $player["height"];
                if(isset($player["country"]))$options["country"] = $player["country"];
                $this->updatePlayer($playerid,$options);
                $options = [];
                if(isset($player["Position"]))$options["position"] = $player["Position"];
                if(isset($player["jersey_num"]))$options["jersey_num"] = $player["jersey_num"];
                if(isset($player["real_position"]))$options["real_position"] = $player["real_position"];
                if(isset($player["real_position_side"]))$options["real_position_side"] = $player["real_position_side"];
                if(isset($player["join_date"]))$options["join_date"] = $player["join_date"];
                $options["team_id"] = $teamid;
                $this->updatePlayerTournament($playerid,$options);
            }
        }

    }


    public function processF13($tournament, $content)
    {
        $gameid = $content["@attributes"]["game_id"];
        $this->updateGame($gameid);
        foreach($content["message"] as $message){
            if(isset($message["@attributes"]["player_ref1"]))$this->updatePlayer($message["@attributes"]["player_ref1"]);
            if(isset($message["@attributes"]["player_ref2"]))$this->updatePlayer($message["@attributes"]["player_ref2"]);
            $this->updateEvent($message,$gameid);
        }


    }
    public function processF1($tournament, $content)
    {
        if($tournament->name == ""){
            $tournament->name = $content["@attributes"]["competition_name"];
            $tournament->save();
        }
        // Procesar equipos
        foreach ($content["Team"] as $row) {
            $teamid = str_replace("t", "", $row["@attributes"]["uID"]);
            $options = [];
            $options["name"] =$row["Name"];
            $this->updateTeam($teamid,$options);
        }
        // Procesar partidos
        foreach ($content["MatchData"] as $match) {

            if (isset($match["MatchInfo"]["@attributes"]["Venue_id"])) {
                $venueid = $match["MatchInfo"]["@attributes"]["Venue_id"];
                $options = [];
                if(isset($match["Stat"][0]))$options["name"] = $match["Stat"][0];
                if(isset($match["Stat"][1]))$options["city"] = $match["Stat"][1];
                $this->updateVenue($venueid,$options);
            }
            $gameid = str_replace("g", "", $match["@attributes"]["uID"]);
            $options = [];
            $options["date"] = $match["MatchInfo"]["Date"];
            $options["timezone"] = $match["MatchInfo"]["TZ"];
            $options["match_type"] = $match["MatchInfo"]["@attributes"]["MatchType"];
            $options["match_day"] = $match["MatchInfo"]["@attributes"]["MatchDay"];
            $options["period"] = $match["MatchInfo"]["@attributes"]["Period"];
            if(isset($match["MatchInfo"]["@attributes"]["RoundNumber"]))$options["round_number"] = $match["MatchInfo"]["@attributes"]["RoundNumber"];
            if(isset($match["MatchInfo"]["@attributes"]["RoundType"]))$options["round_type"] = $match["MatchInfo"]["@attributes"]["RoundType"];
            if(isset($match["MatchInfo"]["@attributes"]["GroupName"]))$options["group_name"] = $match["MatchInfo"]["@attributes"]["GroupName"];
            if(isset($match["MatchInfo"]["@attributes"]["NextMatch"]))$options["next_match"] = $match["MatchInfo"]["@attributes"]["NextMatch"];
            if(isset($match["MatchInfo"]["@attributes"]["NextMatchLoser"]))$options["next_match_loser"] = $match["MatchInfo"]["@attributes"]["NextMatchLoser"];
            if($options["period"] == "FullTime"){
                $options["status"] = "FULL";
            } else if($options["period"] == "Live"){
                $options["status"] = "LIVE";
            } else if($options["period"] == "PreMatch"){
                $options["status"] = "PRE-MATCH";
            }
            if (isset($match["Stat"]))$options["city"] = $match["Stat"][1];
            if (isset($match["MatchInfo"]["@attributes"]["Venue_id"]))$options["venue_id"] = $match["MatchInfo"]["@attributes"]["Venue_id"];
            if (isset($match["MatchInfo"]["@attributes"]["MatchWinner"]))$options["match_winner"] = str_replace("t", "", $match["MatchInfo"]["@attributes"]["MatchWinner"]);

            if ($match["TeamData"][0]["@attributes"]["Side"] == "Home") {
                $options["home_team"] = str_replace("t", "", $match["TeamData"][0]["@attributes"]["TeamRef"]);
                $options["home_halfscore"] = isset($match["TeamData"][0]["@attributes"]["HalfScore"]) ? $match["TeamData"][0]["@attributes"]["HalfScore"] : 0;
                $options["home_score"] = isset($match["TeamData"][0]["@attributes"]["Score"]) ? $match["TeamData"][0]["@attributes"]["Score"] : 0;
                $options["away_team"] = str_replace("t", "", $match["TeamData"][1]["@attributes"]["TeamRef"]);
                $options["away_halfscore"] = isset($match["TeamData"][1]["@attributes"]["HalfScore"]) ? $match["TeamData"][1]["@attributes"]["HalfScore"] : 0;
                $options["away_score"] = isset($match["TeamData"][1]["@attributes"]["Score"]) ? $match["TeamData"][1]["@attributes"]["Score"] : 0;
            } else {
                $options["home_team"] = str_replace("t", "", $match["TeamData"][1]["@attributes"]["TeamRef"]);
                $options["home_halfscore"] = isset($match["TeamData"][1]["@attributes"]["HalfScore"]) ? $match["TeamData"][1]["@attributes"]["HalfScore"] : 0;
                $options["home_score"] = isset($match["TeamData"][1]["@attributes"]["Score"]) ? $match["TeamData"][1]["@attributes"]["Score"] : 0;
                $options["away_team"] = str_replace("t", "", $match["TeamData"][0]["@attributes"]["TeamRef"]);
                $options["away_halfscore"] = isset($match["TeamData"][0]["@attributes"]["HalfScore"]) ? $match["TeamData"][0]["@attributes"]["HalfScore"] : 0;
                $options["away_score"] = isset($match["TeamData"][0]["@attributes"]["Score"]) ? $match["TeamData"][0]["@attributes"]["Score"] : 0;
            }
            $this->updateGame($gameid,$options);
        }
    }

    public function processF26($tournament, $content)
    {

        if (isset($content["content.item"]["content.body"]["results"]["result"])) {
            $res = $content["content.item"]["content.body"]["results"]["result"];
            if (isset($res["home-team"])) {
                if (isset($res["home-team"]["bookings"]["yellow-card"])) {
                    $this->updateBooking($res["home-team"]["bookings"]["yellow-card"],$res["@attributes"]["game-id"],$res["home-team"]["team-id"],"yellow-card");
                }
                if (isset($res["home-team"]["bookings"]["red-card"])) {
                    $this->updateBooking($res["home-team"]["bookings"]["red-card"],$res["@attributes"]["game-id"],$res["home-team"]["team-id"],"red-card");
                }
                if (isset($res["away-team"]["bookings"]["yellow-card"])) {
                    $this->updateBooking($res["away-team"]["bookings"]["yellow-card"],$res["@attributes"]["game-id"],$res["away-team"]["team-id"],"yellow-card");
                }
                if (isset($res["away-team"]["bookings"]["red-card"])) {
                    $this->updateBooking($res["away-team"]["bookings"]["red-card"],$res["@attributes"]["game-id"],$res["away-team"]["team-id"],"red-card");
                }
                $this->updateTeam($res["home-team"]["team-id"], ["name"=>isset($res["home-team"]["team-name"]) ? $res["home-team"]["team-name"] : "","code"=>isset($res["home-team"]["team-code"]) ? $res["home-team"]["team-code"] : ""]);
                $this->updateTeam($res["away-team"]["team-id"], ["name"=>isset($res["away-team"]["team-name"]) ? $res["away-team"]["team-name"] : "","code"=>isset($res["away-team"]["team-code"]) ? $res["away-team"]["team-code"] : ""]);
                $options = [];
                if(isset($res["@attributes"]["match-status"]))$options["status"] = $res["@attributes"]["match-status"];
                if(isset($res["@attributes"]["period"]))$options["period"] = $res["@attributes"]["period"];
                if(isset($res["home-team"]["score"]))$options["home_score"] = $res["home-team"]["score"];
                if(isset($res["away-team"]["score"]))$options["away_score"] = $res["away-team"]["score"];

                $this->updateGame($res["@attributes"]["game-id"],$options);
                if (isset($res["home-team"]["scorers"])) {
                    if (isset($res["home-team"]["scorers"]["scorer"]["player-code"])) {
                        $scorer = $res["home-team"]["scorers"]["scorer"];
                        $this->updatePlayer($scorer["player-code"],
                            ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                "last_name"=>$scorer["player-name"],
                                "name"=>$scorer["player-name"]
                            ]);
                        $this->updateEvent($scorer,$res["@attributes"]["game-id"],$res["home-team"]["team-id"]);
                    } else {
                        foreach ($res["home-team"]["scorers"]["scorer"] as $scorer) {
                            $this->updatePlayer($scorer["player-code"],
                                ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                    "last_name"=>$scorer["player-name"],
                                    "name"=>$scorer["player-name"]
                                ]);
                            $this->updateEvent($scorer,$res["@attributes"]["game-id"],$res["home-team"]["team-id"]);
                        }
                    }
                }
                if (isset($res["away-team"]["scorers"])) {
                    if (isset($res["away-team"]["scorers"]["scorer"]["player-code"])) {
                        $scorer = $res["away-team"]["scorers"]["scorer"];
                        $this->updatePlayer($scorer["player-code"],
                            ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                "last_name"=>$scorer["player-name"],
                                "name"=>$scorer["player-name"]
                            ]);

                        $this->updateEvent($scorer,$res["@attributes"]["game-id"],$res["away-team"]["team-id"]);
                    } else {
                        foreach ($res["away-team"]["scorers"]["scorer"] as $scorer) {
                            $this->updatePlayer($scorer["player-code"],
                                ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                    "last_name"=>$scorer["player-name"],
                                    "name"=>$scorer["player-name"]
                                ]);
                            $this->updateEvent($scorer,$res["@attributes"]["game-id"],$res["away-team"]["team-id"]);
                        }
                    }
                }
                if (isset($res["home-team"]["substitutions"]["substitution"])) {
                    if(isset($res["home-team"]["substitutions"]["substitution"]["sub-off"])){
                        $subs = $res["home-team"]["substitutions"]["substitution"];
                        $this->updatePlayer($subs["sub-off"]["player-code"],
                            ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                "last_name"=>$subs["sub-off"]["player-name"],
                                "name"=>$subs["sub-off"]["player-name"]
                            ]);
                        $this->updatePlayer($subs["sub-on"]["player-code"],
                            ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                "last_name"=>$subs["sub-on"]["player-name"],
                                "name"=>$subs["sub-on"]["player-name"]
                            ]);
                        $this->updateEvent($subs,$res["@attributes"]["game-id"],$res["home-team"]["team-id"]);
                    } else {
                        foreach ($res["home-team"]["substitutions"]["substitution"] as $subs) {
                            $this->updatePlayer($subs["sub-off"]["player-code"],
                                ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-off"]["player-name"],
                                    "name"=>$subs["sub-off"]["player-name"]
                                ]);
                            $this->updatePlayer($subs["sub-on"]["player-code"],
                                ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-on"]["player-name"],
                                    "name"=>$subs["sub-on"]["player-name"]
                                ]);
                            $this->updateEvent($subs,$res["@attributes"]["game-id"],$res["home-team"]["team-id"]);
                        }
                    }
                }
                if (isset($res["away-team"]["substitutions"]["substitution"])) {
                    if(isset($res["away-team"]["substitutions"]["substitution"]["sub-off"])){
                        $subs = $res["away-team"]["substitutions"]["substitution"];
                        $this->updatePlayer($subs["sub-off"]["player-code"],
                            ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                "last_name"=>$subs["sub-off"]["player-name"],
                                "name"=>$subs["sub-off"]["player-name"]
                            ]);
                        $this->updatePlayer($subs["sub-on"]["player-code"],
                            ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                "last_name"=>$subs["sub-on"]["player-name"],
                                "name"=>$subs["sub-on"]["player-name"]
                            ]);
                        $this->updateEvent($subs,$res["@attributes"]["game-id"],$res["away-team"]["team-id"]);
                    } else {
                        foreach ($res["away-team"]["substitutions"]["substitution"] as $subs) {
                            $this->updatePlayer($subs["sub-off"]["player-code"],
                                ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-off"]["player-name"],
                                    "name"=>$subs["sub-off"]["player-name"]
                                ]);
                            $this->updatePlayer($subs["sub-on"]["player-code"],
                                ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-on"]["player-name"],
                                    "name"=>$subs["sub-on"]["player-name"]
                                ]);
                            $this->updateEvent($subs,$res["@attributes"]["game-id"],$res["away-team"]["team-id"]);
                        }
                    }
                }
            } else {
                foreach($res as $res2){
                    if (isset($res2["home-team"]["bookings"]["yellow-card"])) {
                        $this->updateBooking($res2["home-team"]["bookings"]["yellow-card"],$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"],"yellow-card");
                    }
                    if (isset($res2["home-team"]["bookings"]["red-card"])) {
                        $this->updateBooking($res2["home-team"]["bookings"]["red-card"],$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"],"red-card");
                    }
                    if (isset($res2["away-team"]["bookings"]["yellow-card"])) {
                        $this->updateBooking($res2["away-team"]["bookings"]["yellow-card"],$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"],"yellow-card");
                    }
                    if (isset($res2["away-team"]["bookings"]["red-card"])) {
                        $this->updateBooking($res2["away-team"]["bookings"]["red-card"],$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"],"red-card");
                    }
                    $this->updateTeam($res2["home-team"]["team-id"], ["name"=>isset($res2["home-team"]["team-name"]) ? $res2["home-team"]["team-name"] : "","code"=>isset($res2["home-team"]["team-code"]) ? $res2["home-team"]["team-code"] : ""]);
                    $this->updateTeam($res2["away-team"]["team-id"], ["name"=>isset($res2["away-team"]["team-name"]) ? $res2["away-team"]["team-name"] : "","code"=>isset($res2["away-team"]["team-code"]) ? $res2["away-team"]["team-code"] : ""]);
                    $options = [];
                    if(isset($res2["@attributes"]["match-status"]))$options["status"] = $res2["@attributes"]["match-status"];
                    if(isset($res2["@attributes"]["period"]))$options["period"] = $res2["@attributes"]["period"];
                    if(isset($res2["home-team"]["score"]))$options["home_score"] = $res2["home-team"]["score"];
                    if(isset($res2["away-team"]["score"]))$options["away_score"] = $res2["away-team"]["score"];
                    $this->updateGame($res2["@attributes"]["game-id"],$options);

                    if (isset($res2["home-team"]["scorers"])) {
                        if (isset($res2["home-team"]["scorers"]["scorer"]["player-code"])) {
                            $scorer = $res2["home-team"]["scorers"]["scorer"];
                            $this->updatePlayer($scorer["player-code"],
                                ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                    "last_name"=>$scorer["player-name"],
                                    "name"=>$scorer["player-name"]
                                ]);
                            $this->updateEvent($scorer,$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"]);
                        } else {
                            foreach ($res2["home-team"]["scorers"]["scorer"] as $scorer) {
                                $this->updatePlayer($scorer["player-code"],
                                    ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                        "last_name"=>$scorer["player-name"],
                                        "name"=>$scorer["player-name"]
                                    ]);
                                $this->updateEvent($scorer,$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"]);
                            }
                        }
                    }
                    if (isset($res2["away-team"]["scorers"])) {
                        if (isset($res2["away-team"]["scorers"]["scorer"]["player-code"])) {
                            $scorer = $res2["away-team"]["scorers"]["scorer"];
                            $this->updatePlayer($scorer["player-code"],
                                ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                    "last_name"=>$scorer["player-name"],
                                    "name"=>$scorer["player-name"]
                                ]);
                            $this->updateEvent($scorer,$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"]);
                        } else {
                            foreach ($res2["away-team"]["scorers"]["scorer"] as $scorer) {
                                $this->updatePlayer($scorer["player-code"],
                                    ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                        "last_name"=>$scorer["player-name"],
                                        "name"=>$scorer["player-name"]
                                    ]);
                                $this->updateEvent($scorer,$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"]);
                            }
                        }
                    }
                    if (isset($res2["home-team"]["substitutions"]["substitution"])) {
                        if(isset($res2["home-team"]["substitutions"]["substitution"]["sub-off"])){
                            $subs = $res2["home-team"]["substitutions"]["substitution"];
                            $this->updatePlayer($subs["sub-off"]["player-code"],
                                ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-off"]["player-name"],
                                    "name"=>$subs["sub-off"]["player-name"]
                                ]);
                            $this->updatePlayer($subs["sub-on"]["player-code"],
                                ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-on"]["player-name"],
                                    "name"=>$subs["sub-on"]["player-name"]
                                ]);
                            $this->updateEvent($subs,$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"]);
                        } else {
                            foreach ($res2["home-team"]["substitutions"]["substitution"] as $subs) {
                                $this->updatePlayer($subs["sub-off"]["player-code"],
                                    ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-off"]["player-name"],
                                        "name"=>$subs["sub-off"]["player-name"]
                                    ]);
                                $this->updatePlayer($subs["sub-on"]["player-code"],
                                    ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-on"]["player-name"],
                                        "name"=>$subs["sub-on"]["player-name"]
                                    ]);
                                $this->updateEvent($subs,$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"]);
                            }
                        }
                    }

                    if (isset($res2["away-team"]["substitutions"]["substitution"])) {
                        if(isset($res2["away-team"]["substitutions"]["substitution"]["sub-off"])){
                            $subs = $res2["away-team"]["substitutions"]["substitution"];
                            $this->updatePlayer($subs["sub-off"]["player-code"],
                                ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-off"]["player-name"],
                                    "name"=>$subs["sub-off"]["player-name"]
                                ]);
                            $this->updatePlayer($subs["sub-on"]["player-code"],
                                ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-on"]["player-name"],
                                    "name"=>$subs["sub-on"]["player-name"]
                                ]);
                            $this->updateEvent($subs,$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"]);
                        } else {
                            foreach ($res2["away-team"]["substitutions"]["substitution"] as $subs) {
                                $this->updatePlayer($subs["sub-off"]["player-code"],
                                    ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-off"]["player-name"],
                                        "name"=>$subs["sub-off"]["player-name"]
                                    ]);
                                $this->updatePlayer($subs["sub-on"]["player-code"],
                                    ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-on"]["player-name"],
                                        "name"=>$subs["sub-on"]["player-name"]
                                    ]);
                                $this->updateEvent($subs,$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"]);
                            }
                        }
                    }
                }
            }
        } else {
            foreach ($content["content.item"]["content.body"]["results"] as $row) {
                $res = $row["result"];
                if (isset($res["home-team"])) {
                    if (isset($res["home-team"]["bookings"]["yellow-card"])) {
                        $this->updateBooking($res["home-team"]["bookings"]["yellow-card"],$res["@attributes"]["game-id"],$res["home-team"]["team-id"],"yellow-card");
                    }
                    if (isset($res["home-team"]["bookings"]["red-card"])) {
                        $this->updateBooking($res["home-team"]["bookings"]["red-card"],$res["@attributes"]["game-id"],$res["home-team"]["team-id"],"red-card");
                    }
                    if (isset($res["away-team"]["bookings"]["yellow-card"])) {
                        $this->updateBooking($res["away-team"]["bookings"]["yellow-card"],$res["@attributes"]["game-id"],$res["away-team"]["team-id"],"yellow-card");
                    }
                    if (isset($res["away-team"]["bookings"]["red-card"])) {
                        $this->updateBooking($res["away-team"]["bookings"]["red-card"],$res["@attributes"]["game-id"],$res["away-team"]["team-id"],"red-card");
                    }
                    $this->updateTeam($res["home-team"]["team-id"], ["name"=>isset($res["home-team"]["team-name"]) ? $res["home-team"]["team-name"] : "","code"=>isset($res["home-team"]["team-code"]) ? $res["home-team"]["team-code"] : ""]);
                    $this->updateTeam($res["away-team"]["team-id"], ["name"=>isset($res["away-team"]["team-name"]) ? $res["away-team"]["team-name"] : "","code"=>isset($res["away-team"]["team-code"]) ? $res["away-team"]["team-code"] : ""]);
                    $options = [];
                    if(isset($res["@attributes"]["match-status"]))$options["status"] = $res["@attributes"]["match-status"];
                    if(isset($res["@attributes"]["period"]))$options["period"] = $res["@attributes"]["period"];
                    if(isset($res["home-team"]["score"]))$options["home_score"] = $res["home-team"]["score"];
                    if(isset($res["away-team"]["score"]))$options["away_score"] = $res["away-team"]["score"];
                    $this->updateGame($res["@attributes"]["game-id"],$options);

                    if (isset($res["home-team"]["scorers"])) {
                        if (isset($res["home-team"]["scorers"]["scorer"]["player-code"])) {
                            $scorer = $res["home-team"]["scorers"]["scorer"];
                            $this->updatePlayer($scorer["player-code"],
                                ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                    "last_name"=>$scorer["player-name"],
                                    "name"=>$scorer["player-name"]
                                ]);
                            $this->updateEvent($scorer,$res["@attributes"]["game-id"],$res["home-team"]["team-id"]);
                        } else {
                            foreach ($res["home-team"]["scorers"]["scorer"] as $scorer) {
                                $this->updatePlayer($scorer["player-code"],
                                    ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                        "last_name"=>$scorer["player-name"],
                                        "name"=>$scorer["player-name"]
                                    ]);
                                $this->updateEvent($scorer,$res["@attributes"]["game-id"],$res["home-team"]["team-id"]);
                            }
                        }
                    }
                    if (isset($res["away-team"]["scorers"])) {
                        if (isset($res["away-team"]["scorers"]["scorer"]["player-code"])) {
                            $scorer = $res["away-team"]["scorers"]["scorer"];
                            $this->updatePlayer($scorer["player-code"],
                                ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                    "last_name"=>$scorer["player-name"],
                                    "name"=>$scorer["player-name"]
                                ]);
                            $this->updateEvent($scorer,$res["@attributes"]["game-id"],$res["away-team"]["team-id"]);
                        } else {
                            foreach ($res["away-team"]["scorers"]["scorer"] as $scorer) {
                                $this->updatePlayer($scorer["player-code"],
                                    ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                        "last_name"=>$scorer["player-name"],
                                        "name"=>$scorer["player-name"]
                                    ]);
                                $this->updateEvent($scorer,$res["@attributes"]["game-id"],$res["away-team"]["team-id"]);
                            }
                        }
                    }
                    if (isset($res["home-team"]["substitutions"]["substitution"])) {
                        if(isset($res["home-team"]["substitutions"]["substitution"]["sub-off"])){
                            $subs = $res["home-team"]["substitutions"]["substitution"];
                            $this->updatePlayer($subs["sub-off"]["player-code"],
                                ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-off"]["player-name"],
                                    "name"=>$subs["sub-off"]["player-name"]
                                ]);
                            $this->updatePlayer($subs["sub-on"]["player-code"],
                                ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-on"]["player-name"],
                                    "name"=>$subs["sub-on"]["player-name"]
                                ]);
                            $this->updateEvent($subs,$res["@attributes"]["game-id"],$res["home-team"]["team-id"]);
                        } else {
                            foreach ($res["home-team"]["substitutions"]["substitution"] as $subs) {
                                $this->updatePlayer($subs["sub-off"]["player-code"],
                                    ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-off"]["player-name"],
                                        "name"=>$subs["sub-off"]["player-name"]
                                    ]);
                                $this->updatePlayer($subs["sub-on"]["player-code"],
                                    ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-on"]["player-name"],
                                        "name"=>$subs["sub-on"]["player-name"]
                                    ]);
                                $this->updateEvent($subs,$res["@attributes"]["game-id"],$res["home-team"]["team-id"]);
                            }
                        }
                    }

                    if (isset($res["away-team"]["substitutions"]["substitution"])) {
                        if(isset($res["away-team"]["substitutions"]["substitution"]["sub-off"])){
                            $subs = $res["away-team"]["substitutions"]["substitution"];
                            $this->updatePlayer($subs["sub-off"]["player-code"],
                                ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-off"]["player-name"],
                                    "name"=>$subs["sub-off"]["player-name"]
                                ]);
                            $this->updatePlayer($subs["sub-on"]["player-code"],
                                ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                    "last_name"=>$subs["sub-on"]["player-name"],
                                    "name"=>$subs["sub-on"]["player-name"]
                                ]);
                            $this->updateEvent($subs,$res["@attributes"]["game-id"],$res["away-team"]["team-id"]);

                        } else {
                            foreach ($res["away-team"]["substitutions"]["substitution"] as $subs) {
                                $this->updatePlayer($subs["sub-off"]["player-code"],
                                    ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-off"]["player-name"],
                                        "name"=>$subs["sub-off"]["player-name"]
                                    ]);
                                $this->updatePlayer($subs["sub-on"]["player-code"],
                                    ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-on"]["player-name"],
                                        "name"=>$subs["sub-on"]["player-name"]
                                    ]);
                                $this->updateEvent($subs,$res["@attributes"]["game-id"],$res["away-team"]["team-id"]);
                            }
                        }
                    }
                } else {
                    foreach($res as $res2){
                        if (isset($res2["home-team"]["bookings"]["yellow-card"])) {
                            $this->updateBooking($res2["home-team"]["bookings"]["yellow-card"],$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"],"yellow-card");
                        }
                        if (isset($res2["home-team"]["bookings"]["red-card"])) {
                            $this->updateBooking($res2["home-team"]["bookings"]["red-card"],$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"],"red-card");
                        }
                        if (isset($res2["away-team"]["bookings"]["yellow-card"])) {
                            $this->updateBooking($res2["away-team"]["bookings"]["yellow-card"],$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"],"yellow-card");
                        }
                        if (isset($res2["away-team"]["bookings"]["red-card"])) {
                            $this->updateBooking($res2["away-team"]["bookings"]["red-card"],$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"],"red-card");
                        }

                        $this->updateTeam($res2["home-team"]["team-id"], ["name"=>isset($res2["home-team"]["team-name"]) ? $res2["home-team"]["team-name"] : "","code"=>isset($res2["home-team"]["team-code"]) ? $res2["home-team"]["team-code"] : ""]);
                        $this->updateTeam($res2["away-team"]["team-id"], ["name"=>isset($res2["away-team"]["team-name"]) ? $res2["away-team"]["team-name"] : "","code"=>isset($res2["away-team"]["team-code"]) ? $res2["away-team"]["team-code"] : ""]);
                        $options = [];
                        if(isset($res2["@attributes"]["match-status"]))$options["status"] = $res2["@attributes"]["match-status"];
                        if(isset($res2["@attributes"]["period"]))$options["period"] = $res2["@attributes"]["period"];
                        if(isset($res2["home-team"]["score"]))$options["home_score"] = $res2["home-team"]["score"];
                        if(isset($res2["away-team"]["score"]))$options["away_score"] = $res2["away-team"]["score"];
                        $this->updateGame($res2["@attributes"]["game-id"],$options);

                        if (isset($res2["home-team"]["scorers"])) {
                            if (isset($res2["home-team"]["scorers"]["scorer"]["player-code"])) {
                                $scorer = $res2["home-team"]["scorers"]["scorer"];
                                $this->updatePlayer($scorer["player-code"],
                                    ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                        "last_name"=>$scorer["player-name"],
                                        "name"=>$scorer["player-name"]
                                    ]);
                                $this->updateEvent($scorer,$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"]);
                            } else {
                                foreach ($res2["home-team"]["scorers"]["scorer"] as $scorer) {
                                    $this->updatePlayer($scorer["player-code"],
                                        ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                            "last_name"=>$scorer["player-name"],
                                            "name"=>$scorer["player-name"]
                                        ]);
                                    $this->updateEvent($scorer,$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"]);
                                }
                            }
                        }
                        if (isset($res2["away-team"]["scorers"])) {
                            if (isset($res2["away-team"]["scorers"]["scorer"]["player-code"])) {
                                $scorer = $res2["away-team"]["scorers"]["scorer"];
                                $this->updatePlayer($scorer["player-code"],
                                    ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                        "last_name"=>$scorer["player-name"],
                                        "name"=>$scorer["player-name"]
                                    ]);
                                $this->updateEvent($scorer,$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"]);
                            } else {
                                foreach ($res2["away-team"]["scorers"]["scorer"] as $scorer) {
                                    $this->updatePlayer($scorer["player-code"],
                                        ["first_name"=>isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : "",
                                            "last_name"=>$scorer["player-name"],
                                            "name"=>$scorer["player-name"]
                                        ]);
                                    $this->updateEvent($scorer,$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"]);
                                }
                            }
                        }
                        if (isset($res2["home-team"]["substitutions"]["substitution"])) {
                            if(isset($res2["home-team"]["substitutions"]["substitution"]["sub-off"])){
                                $subs = $res2["home-team"]["substitutions"]["substitution"];
                                $this->updatePlayer($subs["sub-off"]["player-code"],
                                    ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-off"]["player-name"],
                                        "name"=>$subs["sub-off"]["player-name"]
                                    ]);
                                $this->updatePlayer($subs["sub-on"]["player-code"],
                                    ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-on"]["player-name"],
                                        "name"=>$subs["sub-on"]["player-name"]
                                    ]);
                                $this->updateEvent($subs,$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"]);
                            } else {
                                foreach ($res2["home-team"]["substitutions"]["substitution"] as $subs) {
                                    $this->updatePlayer($subs["sub-off"]["player-code"],
                                        ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                            "last_name"=>$subs["sub-off"]["player-name"],
                                            "name"=>$subs["sub-off"]["player-name"]
                                        ]);
                                    $this->updatePlayer($subs["sub-on"]["player-code"],
                                        ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                            "last_name"=>$subs["sub-on"]["player-name"],
                                            "name"=>$subs["sub-on"]["player-name"]
                                        ]);
                                    $this->updateEvent($subs,$res2["@attributes"]["game-id"],$res2["home-team"]["team-id"]);
                                }
                            }
                        }

                        if (isset($res2["away-team"]["substitutions"]["substitution"])) {
                            if(isset($res2["away-team"]["substitutions"]["substitution"]["sub-off"])){
                                $subs = $res2["away-team"]["substitutions"]["substitution"];
                                $this->updatePlayer($subs["sub-off"]["player-code"],
                                    ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-off"]["player-name"],
                                        "name"=>$subs["sub-off"]["player-name"]
                                    ]);
                                $this->updatePlayer($subs["sub-on"]["player-code"],
                                    ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                        "last_name"=>$subs["sub-on"]["player-name"],
                                        "name"=>$subs["sub-on"]["player-name"]
                                    ]);
                                $this->updateEvent($subs,$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"]);
                            } else {
                                foreach ($res2["away-team"]["substitutions"]["substitution"] as $subs) {
                                    $this->updatePlayer($subs["sub-off"]["player-code"],
                                        ["first_name"=>isset($subs["sub-off"]["player-firstname"]) ? $subs["sub-off"]["player-firstname"] : "",
                                            "last_name"=>$subs["sub-off"]["player-name"],
                                            "name"=>$subs["sub-off"]["player-name"]
                                        ]);
                                    $this->updatePlayer($subs["sub-on"]["player-code"],
                                        ["first_name"=>isset($subs["sub-on"]["player-firstname"]) ? $subs["sub-on"]["player-firstname"] : "",
                                            "last_name"=>$subs["sub-on"]["player-name"],
                                            "name"=>$subs["sub-on"]["player-name"]
                                        ]);
                                    $this->updateEvent($subs,$res2["@attributes"]["game-id"],$res2["away-team"]["team-id"]);
                                }
                            }
                        }
                    }
                }

            }
        }
    }

    public function updateBooking($data=[], $gameid=null, $teamid=null, $type="yellow-card")
    {
        if(isset($data["@attributes"])){
            $eventid = $data["@attributes"]["event_id"];
            $event = Event::findOrNew($eventid);
            if(!$event->id){
                $event->id = $eventid;
                $event->type = $type;
                $event->save();
                Toastr::success($eventid, "Evento Opta Creado");
            }
            $options = [];
            if(isset($data["player-code"]))$playerid = $data["player-code"];
            else if(isset($data["@attributes"]["id"]))$playerid = $data["@attributes"]["id"];
            if(isset($data["player-firstname"]))$options["first_name"] = $data["player-firstname"];
            else if(isset($data["@attributes"]["first"]))$options["first_name"] = $data["@attributes"]["first"];
            if(isset($data["player-name"]))$options["last_name"] = $data["player-name"];
            else if(isset($data["@attributes"]["last"]))$options["last_name"] = $data["@attributes"]["last"];
            $this->updatePlayer($playerid,$options);

            $options = [];
            $options["game_id"] = $gameid;
            $options["team_id"] = $teamid;
            $options["player_id"] = $playerid;
            $options["minute"] =$data["@attributes"]["min"];
            $options["second"] =$data["@attributes"]["sec"];
            $options["time"] =$data["@attributes"]["time"];
            $options["datetime"] =$data["@attributes"]["timestamp"];
            $options["period"] =$data["@attributes"]["period"];
            if($type=="red-card" && isset($data["@attributes"]["type"]))$options["red_card_type"] = $data["@attributes"]["type"];
            $event->update($options);
        } else {
            foreach($data as $res){
                $eventid = $res["@attributes"]["event_id"];
                $event = Event::findOrNew($eventid);
                if(!$event->id){
                    $event->id = $eventid;
                    $event->type = $type;
                    $event->save();
                    Toastr::success($eventid, "Evento Opta Creado");
                }
                $options = [];
                if(isset($res["player-code"]))$playerid = $res["player-code"];
                else if(isset($res["@attributes"]["id"]))$playerid = $res["@attributes"]["id"];
                if(isset($res["player-firstname"]))$options["first_name"] = $res["player-firstname"];
                else if(isset($res["@attributes"]["first"]))$options["first_name"] = $res["@attributes"]["first"];
                if(isset($res["player-name"]))$options["last_name"] = $res["player-name"];
                else if(isset($res["@attributes"]["last"]))$options["last_name"] = $res["@attributes"]["last"];
                $this->updatePlayer($playerid,$options);

                $options = [];
                $options["game_id"] = $gameid;
                $options["team_id"] = $teamid;
                $options["player_id"] = $playerid;
                $options["minute"] =$res["@attributes"]["min"];
                $options["second"] =$res["@attributes"]["sec"];
                $options["time"] =$res["@attributes"]["time"];
                $options["datetime"] =$res["@attributes"]["timestamp"];
                $options["period"] =$res["@attributes"]["period"];
                if($type=="red-card" && isset($res["@attributes"]["type"]))$options["red_card_type"] = $res["@attributes"]["type"];
                $event->update($options);
            }
        }
    }

    public function updateEvent($data,$gameid,$teamid = null)
    {
        if(isset($data["@attributes"]["comment"])){
            $eventid = $data["@attributes"]["id"];
        } else {
            $eventid = $data["@attributes"]["event_id"];
        }

        if(isset($data["@attributes"]["goal-type"])) {
            $type = "goal";
        } else if(isset($data["@attributes"]["sub-timestamp"])) {
            $type = "substitution";
        } else if(isset($data["@attributes"]["comment"])){
            $type = "comment";
        } else {
            $type = "other";
        }
        $event = Event::findOrNew($eventid);
        if(!$event->id){
            $event->id = $eventid;
            $event->type = $type;
            $event->save();
            Toastr::success($eventid, "Evento Opta Creado");
        }
        $update = false;
        if(!$event->game_id && $gameid){
            $event->game_id = $gameid;
            $update = true;
        }
        if(!$event->team_id && $teamid){
            $event->team_id = $teamid;
            $update = true;
        }
        if($type && $event->type!=$type){
            $event->type = $type;
            $update = true;
        }
        if($event->type == "goal"){
            if(isset($data["@attributes"]["goal-type"]) && $event->goal_type != $data["@attributes"]["goal-type"]){
                $event->goal_type = $data["@attributes"]["goal-type"];
                $update = true;
            }
            if(isset($data["@attributes"]["min"]) && $event->minute != $data["@attributes"]["min"]){
                $event->minute = $data["@attributes"]["min"];
                $update = true;
            }
            if(isset($data["@attributes"]["sec"]) && $event->second != $data["@attributes"]["sec"]){
                $event->second = $data["@attributes"]["sec"];
                $update = true;
            }
            if(isset($data["@attributes"]["time"]) && $event->time != $data["@attributes"]["time"]){
                $event->time = $data["@attributes"]["time"];
                $update = true;
            }
            if(isset($data["@attributes"]["goal-timestamp"]) && $event->datetime != $data["@attributes"]["goal-timestamp"]){
                $event->datetime = $data["@attributes"]["goal-timestamp"];
                $update = true;
            }
            if(isset($data["@attributes"]["period"]) && $event->period != $data["@attributes"]["period"]){
                $event->period = $data["@attributes"]["period"];
                $update = true;
            }
            if(isset($data["player-code"]) && $event->player_id != $data["player-code"]){
                $event->player_id = $data["player-code"];
                $update = true;
            }
        } else if($event->type == "substitution"){
            if(isset($data["@attributes"]["min"]) && $event->minute != $data["@attributes"]["min"]){
                $event->minute = $data["@attributes"]["min"];
                $update = true;
            }
            if(isset($data["@attributes"]["sec"]) && $event->second != $data["@attributes"]["sec"]){
                $event->second = $data["@attributes"]["sec"];
                $update = true;
            }
            if(isset($data["@attributes"]["time"]) && $event->time != $data["@attributes"]["time"]){
                $event->time = $data["@attributes"]["time"];
                $update = true;
            }
            if(isset($data["@attributes"]["sub-timestamp"]) && $event->datetime != $data["@attributes"]["sub-timestamp"]){
                $event->datetime = $data["@attributes"]["sub-timestamp"];
                $update = true;
            }
            if(isset($data["@attributes"]["period"]) && $event->period != $data["@attributes"]["period"]){
                $event->period = $data["@attributes"]["period"];
                $update = true;
            }
            if(isset($data["@attributes"]["reason"]) && $event->sub_reason != $data["@attributes"]["reason"]){
                $event->sub_reason = $data["@attributes"]["reason"];
                $update = true;
            }
            if(isset($data["sub-off"]["player-code"]) && $event->player_id != $data["sub-off"]["player-code"]){
                $event->player_id = $data["sub-off"]["player-code"];
                $update = true;
            }
            if(isset($data["sub-on"]["player-code"]) && $event->sub_on_player_id != $data["sub-on"]["player-code"]){
                $event->sub_on_player_id = $data["sub-on"]["player-code"];
                $update = true;
            }
        } else if($event->type == "comment"){
            if(isset($data["@attributes"]["comment"]) && $event->comment != $data["@attributes"]["comment"]){
                $event->comment = $data["@attributes"]["comment"];
                $update = true;
            }
            if(isset($data["@attributes"]["type"]) && $event->comment_type != $data["@attributes"]["type"]){
                $event->comment_type = $data["@attributes"]["type"];
                $update = true;
            }
            //
            if(isset($data["@attributes"]["player_ref1"]) && $event->player_id != $data["@attributes"]["player_ref1"]){
                $event->player_id = $data["@attributes"]["player_ref1"];
                $update = true;
            }
            if(isset($data["@attributes"]["player_ref2"]) && $event->comment_player_ref2 != $data["@attributes"]["player_ref2"]){
                $event->comment_player_ref2 = $data["@attributes"]["player_ref2"];
                $update = true;
            }
            if(isset($data["@attributes"]["minute"]) && $event->minute != $data["@attributes"]["minute"]){
                $event->minute = $data["@attributes"]["minute"];
                $update = true;
            }
            if(isset($data["@attributes"]["second"]) && $event->second != $data["@attributes"]["second"]){
                $event->second = $data["@attributes"]["second"];
                $update = true;
            }
            if(isset($data["@attributes"]["time"]) && $event->time != $data["@attributes"]["time"]){
                $event->time = $data["@attributes"]["time"];
                $update = true;
            }
            if(isset($data["@attributes"]["period"]) && $event->period != $data["@attributes"]["period"]){
                $event->period = $data["@attributes"]["period"];
                $update = true;
            }
        }
        if($update){
            $event->save();
            Toastr::success($eventid, "Evento Opta Actualizado");
        }

    }

    public function updateTeam($teamid, $options = [])
    {
        $tournament = $this->tournament();
        $team = Team::findOrNew($teamid);
        if (!$team->id) {
            $team->id = $teamid;
            $team->save();
            Toastr::success($teamid, "Equipo Opta Creado");
        }
        $team->update($options);
        if ($tournament) {
            $tournament->optateams()->detach($teamid);
            $tournament->optateams()->attach($teamid);
        }

    }

    public function updateCountry($countryid, $options = [])
    {
        $country = Country::findOrNew($countryid);
        if (!$country->id) {
            $country->id = $countryid;
            $country->save();
            Toastr::success($countryid, "Pais Opta Creado");
        }
        $country->update($options);
    }
    public function updateRegion($regionid, $options = [])
    {
        $region = Region::findOrNew($regionid);
        if (!$region->id) {
            $region->id = $regionid;
            $region->save();
            Toastr::success($regionid, "Región Opta Creada");
        }
        $region->update($options);
    }
    public function updateVenue($venueid, $options = [])
    {
        $venue = Venue::findOrNew($venueid);
        if (!$venue->id) {
            $venue->id = $venueid;
            $venue->save();
            Toastr::success($venueid, "Estadio Opta Creado");
        }
        $venue->update($options);
    }

    public function updateGame($gameid,$options = [])
    {
        $tournament = $this->tournament();
        $game = Game::findOrNew($gameid);
        if (!$game->id) {
            $game->id = $gameid;
            Toastr::success($gameid,"Partido Opta Creado");
        }
        $game->update($options);
        if ($tournament) {
            $tournament->optagames()->save($game);
        }

    }

}

