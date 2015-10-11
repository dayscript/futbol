<?php

namespace Dayscore;

use Carbon\Carbon;
use Dayscore\Opta\Game;
use Dayscore\Opta\Player;
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
        'content'
    ];
    /**
     * The attributes that should be treated as Carbon dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * Format date attribute more human friendly
     *
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        Carbon::setLocale('es');
        return Carbon::parse($date);
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

    public function tournament()
    {
        $tournament = Tournament::where('opta_id', $this->competitionId)->where('opta_season', $this->seasonId)->first();
        if(!$tournament){
            $tournament = new Tournament();
            $tournament->opta_id = $this->competitionId;
            $tournament->opta_season = $this->seasonId;
            $url = "http://futbol.dayscript.com/minamin/tournaments/tournamentopta/". $this->competitionId ."/".$this->seasonId;
            $json = json_decode(file_get_contents($url));
            if($json->status == "success"){
                $tournament->id = $json->data->id;
                $tournament->name = $json->data->name;
                Toastr::success("Se ha sincronizado el torneo correctamente!");
            } else {
                Toastr::error("Ha ocurrido un error al sincronizar el torneo.");
            }
            Toastr::success($tournament->name,"Torneo creado");
        }
        $tournament->save();
        return $tournament;
    }

    public function updatePlayer($code, $first = "", $last = "", $known = null)
    {
        $player = Player::findOrNew($code);
        if (!$player->id) {
            $player->id = $code;
            $player->first_name = $first;
            $player->last_name = $last;
            $player->known = $known;
            $player->save();
            Toastr::success($first . " " . $last, "Jugador Creado");
        } else {
            $update = false;
            if ($player->first_name != $first && $first) {
                $player->first_name = $first;
                $update = true;
            }
            if ($player->last_name != $last && $last) {
                $player->last_name = $last;
                $update = true;
            }
            if ($player->known != $known && $known) {
                $player->known = $known;
                $update = true;
            }
            if ($update) {
                $player->save();
                Toastr::success($first . " " . $last, "Jugador Actualizado");
            }
        }
    }


    public function process()
    {
        $tournament = $this->tournament();
        $parser = new Parser();
        $content = $parser->xml($this->content);
        if ($this->feedType == "F1") {
            $this->processF1($tournament, $content["SoccerDocument"]);
        } else if ($this->feedType == "F26") {
            $this->processF26($tournament, $content);
        }
    }

    public function processF1($tournament, $content)
    {
        // Procesar equipos
        foreach ($content["Team"] as $row) {
            $teamid = str_replace("t", "", $row["@attributes"]["uID"]);
            $team = Team::findOrNew($teamid);
            if (!$team->id) {
                $team->id = $teamid;
                $team->name = $row["Name"];
                $team->save();
                Toastr::success($row["Name"], "Equipo Opta Creado");
            }
            if ($tournament) {
                $tournament->optateams()->detach($team->id);
                $tournament->optateams()->attach($team->id);
            }
        }
        // Procesar partidos
        foreach ($content["MatchData"] as $match) {
            $gameid = str_replace("g", "", $match["@attributes"]["uID"]);
            $game = Game::findOrNew($gameid);
            if (!$game->id) {
                $game->id = $gameid;
                Toastr::success("Se ha creado el juego opta: " . $gameid);
            }
            $game->date = $match["MatchInfo"]["Date"];
            $game->timezone = $match["MatchInfo"]["TZ"];
            $game->match_type = $match["MatchInfo"]["@attributes"]["MatchType"];
            $game->match_day = $match["MatchInfo"]["@attributes"]["MatchDay"];
            $game->period = $match["MatchInfo"]["@attributes"]["Period"];
            if (isset($match["Stat"])) {
                $game->city = $match["Stat"][1];
            }
            if (isset($match["MatchInfo"]["@attributes"]["Venue_id"])) {
                $venueid = $match["MatchInfo"]["@attributes"]["Venue_id"];
                $venue = Venue::firstOrCreate(["id" => $venueid]);
                if (!$venue->name && isset($match["Stat"]) && count($match["Stat"]) >= 2) {
                    $venue->name = $match["Stat"][0];
                    $venue->city = $match["Stat"][1];
                    $venue->save();
                }
                $game->venue_id = $venueid;
            }
            if (isset($match["MatchInfo"]["@attributes"]["MatchWinner"]))
                $game->match_winner = str_replace("t", "", $match["MatchInfo"]["@attributes"]["MatchWinner"]);
            if ($match["TeamData"][0]["@attributes"]["Side"] == "Home") {
                $game->home_team = str_replace("t", "", $match["TeamData"][0]["@attributes"]["TeamRef"]);
                $game->home_halfscore = isset($match["TeamData"][0]["@attributes"]["HalfScore"]) ? $match["TeamData"][0]["@attributes"]["HalfScore"] : 0;
                $game->home_score = isset($match["TeamData"][0]["@attributes"]["Score"]) ? $match["TeamData"][0]["@attributes"]["Score"] : 0;
                $game->away_team = str_replace("t", "", $match["TeamData"][1]["@attributes"]["TeamRef"]);
                $game->away_halfscore = isset($match["TeamData"][1]["@attributes"]["HalfScore"]) ? $match["TeamData"][1]["@attributes"]["HalfScore"] : 0;
                $game->away_score = isset($match["TeamData"][1]["@attributes"]["Score"]) ? $match["TeamData"][1]["@attributes"]["Score"] : 0;
            } else {
                $game->home_team = str_replace("t", "", $match["TeamData"][1]["@attributes"]["TeamRef"]);
                $game->home_halfscore = isset($match["TeamData"][1]["@attributes"]["HalfScore"]) ? $match["TeamData"][1]["@attributes"]["HalfScore"] : 0;
                $game->home_score = isset($match["TeamData"][1]["@attributes"]["Score"]) ? $match["TeamData"][1]["@attributes"]["Score"] : 0;
                $game->away_team = str_replace("t", "", $match["TeamData"][0]["@attributes"]["TeamRef"]);
                $game->away_halfscore = isset($match["TeamData"][0]["@attributes"]["HalfScore"]) ? $match["TeamData"][0]["@attributes"]["HalfScore"] : 0;
                $game->away_score = isset($match["TeamData"][0]["@attributes"]["Score"]) ? $match["TeamData"][0]["@attributes"]["Score"] : 0;
            }
            if ($tournament) {
                $tournament->optagames()->save($game);
            }
        }
    }
    public function processF26($tournament, $content){
        if (isset($content["content.item"]["content.body"]["results"]["result"])) {
            $res = $content["content.item"]["content.body"]["results"]["result"];
            $teamid = $res["home-team"]["team-id"];
            $teamname = isset($res["home-team"]["team-name"]) ? $res["home-team"]["team-name"] : "";
            $teamcode = isset($res["home-team"]["team-code"]) ? $res["home-team"]["team-code"] : "";
            $team = Team::findOrNew($teamid);
            if (!$team->id) {
                $team->id = $teamid;
                $team->name = $teamname;
                $team->code = $teamcode;
                $team->save();
                Toastr::success($teamname, "Equipo Opta Creado");
            } else {
                $updated = false;
                if ($team->code != $teamcode && $teamcode != "") {
                    $team->code = $teamcode;
                    $updated = true;
                }
                if ($team->name != $teamname && $teamname != "") {
                    $team->name = $teamname;
                    $updated = true;
                }
                if ($updated) {
                    $team->save();
                    Toastr::success($teamname, "Equipo Opta Actualizado");
                }
            }
            $teamid = $res["away-team"]["team-id"];
            $teamname = isset($res["away-team"]["team-name"]) ? $res["away-team"]["team-name"] : "";
            $teamcode = isset($res["away-team"]["team-code"]) ? $res["away-team"]["team-code"] : "";
            $team = Team::findOrNew($teamid);
            if (!$team->id) {
                $team->id = $teamid;
                $team->name = $teamname;
                $team->code = $teamcode;
                $team->save();
                Toastr::success($teamname, "Equipo Opta Creado");
            } else {
                $updated = false;
                if ($team->code != $teamcode && $teamcode != "") {
                    $team->code = $teamcode;
                    $updated = true;
                }
                if ($team->name != $teamname && $teamname != "") {
                    $team->name = $teamname;
                    $updated = true;
                }
                if ($updated) {
                    $team->save();
                    Toastr::success($teamname, "Equipo Opta Actualizado");
                }
            }
            $gameid = $res["@attributes"]["game-id"];
            $game = Game::findOrNew($gameid);
            if (!$game->id) {
                $game->id = $gameid;
                Toastr::success("Se ha creado el juego opta: " . $gameid);
            }
            if ($tournament) {
                $tournament->optagames()->save($game);
            }
            if (isset($res["home-team"]["scorers"])) {
                if (isset($res["home-team"]["scorers"]["scorer"]["player-code"])) {
                    $scorer = $res["home-team"]["scorers"]["scorer"];
                    $this->updatePlayer($scorer["player-code"], (isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : ""), $scorer["player-name"]);
                } else {
                    foreach ($res["home-team"]["scorers"]["scorer"] as $scorer) {
                        $this->updatePlayer($scorer["player-code"], (isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : ""), $scorer["player-name"]);
                    }
                }
            }
            if (isset($res["away-team"]["scorers"])) {
                if (isset($res["away-team"]["scorers"]["scorer"]["player-code"])) {
                    $scorer = $res["away-team"]["scorers"]["scorer"];
                    $this->updatePlayer($scorer["player-code"], (isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : ""), $scorer["player-name"]);
                } else {
                    foreach ($res["away-team"]["scorers"]["scorer"] as $scorer) {
                        $this->updatePlayer($scorer["player-code"], (isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : ""), $scorer["player-name"]);
                    }
                }
            }
            foreach ($res["home-team"]["substitutions"]["substitution"] as $subs) {
                $this->updatePlayer($subs["sub-off"]["player-code"], (isset($subs["sub-off"]["player-firstname"])) ? $subs["sub-off"]["player-firstname"] : "", $subs["sub-off"]["player-name"]);
                $this->updatePlayer($subs["sub-on"]["player-code"], (isset($subs["sub-on"]["player-firstname"])) ? $subs["sub-on"]["player-firstname"] : "", $subs["sub-on"]["player-name"]);
            }
            foreach ($res["away-team"]["substitutions"]["substitution"] as $subs) {
                $this->updatePlayer($subs["sub-off"]["player-code"], (isset($subs["sub-off"]["player-firstname"])) ? $subs["sub-off"]["player-firstname"] : "", $subs["sub-off"]["player-name"]);
                $this->updatePlayer($subs["sub-on"]["player-code"], (isset($subs["sub-on"]["player-firstname"])) ? $subs["sub-on"]["player-firstname"] : "", $subs["sub-on"]["player-name"]);
            }
        } else {
            foreach ($content["content.item"]["content.body"]["results"] as $row) {
                $res = $row["result"];
                $teamid = $res["home-team"]["team-id"];
                $teamname = isset($res["home-team"]["team-name"]) ? $res["home-team"]["team-name"] : "";
                $teamcode = isset($res["home-team"]["team-code"]) ? $res["home-team"]["team-code"] : "";
                $team = Team::findOrNew($teamid);
                if (!$team->id) {
                    $team->id = $teamid;
                    $team->name = $teamname;
                    $team->code = $teamcode;
                    $team->save();
                    Toastr::success($teamname, "Equipo Opta Creado");
                } else {
                    $updated = false;
                    if ($team->code != $teamcode && $teamcode != "") {
                        $team->code = $teamcode;
                        $updated = true;
                    }
                    if ($team->name != $teamname && $teamname != "") {
                        $team->name = $teamname;
                        $updated = true;
                    }
                    if ($updated) {
                        $team->save();
                        Toastr::success($teamname, "Equipo Opta Actualizado");
                    }
                }

                $teamid = $res["away-team"]["team-id"];
                $teamname = isset($res["away-team"]["team-name"]) ? $res["away-team"]["team-name"] : "";
                $teamcode = isset($res["away-team"]["team-code"]) ? $res["away-team"]["team-code"] : "";
                $team = Team::findOrNew($teamid);
                if (!$team->id) {
                    $team->id = $teamid;
                    $team->name = $teamname;
                    $team->code = $teamcode;
                    $team->save();
                    Toastr::success($teamname, "Equipo Opta Creado");
                } else {
                    $updated = false;
                    if ($team->code != $teamcode && $teamcode != "") {
                        $team->code = $teamcode;
                        $updated = true;
                    }
                    if ($team->name != $teamname && $teamname != "") {
                        $team->name = $teamname;
                        $updated = true;
                    }
                    if ($updated) {
                        $team->save();
                        Toastr::success($teamname, "Equipo Opta Actualizado");
                    }
                }
                $gameid = $res["@attributes"]["game-id"];
                $game = Game::findOrNew($gameid);
                if (!$game->id) {
                    $game->id = $gameid;
                    Toastr::success("Se ha creado el juego opta: " . $gameid);
                }
                if ($tournament) {
                    $tournament->optagames()->save($game);
                }
                if (isset($res["home-team"]["scorers"])) {
                    if (isset($res["home-team"]["scorers"]["scorer"]["player-code"])) {
                        $scorer = $res["home-team"]["scorers"]["scorer"];
                        $this->updatePlayer($scorer["player-code"], (isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : ""), $scorer["player-name"]);
                    } else {
                        foreach ($res["home-team"]["scorers"]["scorer"] as $scorer) {
                            $this->updatePlayer($scorer["player-code"], (isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : ""), $scorer["player-name"]);
                        }
                    }
                }
                if (isset($res["away-team"]["scorers"])) {
                    if (isset($res["away-team"]["scorers"]["scorer"]["player-code"])) {
                        $scorer = $res["away-team"]["scorers"]["scorer"];
                        $this->updatePlayer($scorer["player-code"], (isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : ""), $scorer["player-name"]);
                    } else {
                        foreach ($res["away-team"]["scorers"]["scorer"] as $scorer) {
                            $this->updatePlayer($scorer["player-code"], (isset($scorer["player-firstname"]) ? $scorer["player-firstname"] : ""), $scorer["player-name"]);
                        }
                    }
                }
                foreach ($res["home-team"]["substitutions"]["substitution"] as $subs) {
                    $this->updatePlayer($subs["sub-off"]["player-code"], (isset($subs["sub-off"]["player-firstname"])) ? $subs["sub-off"]["player-firstname"] : "", $subs["sub-off"]["player-name"]);
                    $this->updatePlayer($subs["sub-on"]["player-code"], (isset($subs["sub-on"]["player-firstname"])) ? $subs["sub-on"]["player-firstname"] : "", $subs["sub-on"]["player-name"]);
                }
                foreach ($res["away-team"]["substitutions"]["substitution"] as $subs) {
                    $this->updatePlayer($subs["sub-off"]["player-code"], (isset($subs["sub-off"]["player-firstname"])) ? $subs["sub-off"]["player-firstname"] : "", $subs["sub-off"]["player-name"]);
                    $this->updatePlayer($subs["sub-on"]["player-code"], (isset($subs["sub-on"]["player-firstname"])) ? $subs["sub-on"]["player-firstname"] : "", $subs["sub-on"]["player-name"]);
                }
            }
        }
    }
}
