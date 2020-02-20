<?php
/**
 * AlbumController
 * @package api-music
 * @version 0.0.1
 */

namespace ApiMusic\Controller;

use LibFormatter\Library\Formatter;
use Music\Model\{
    Music,
    MusicAlbum as MAlbum
};

class AlbumController extends \Api\Controller
{
    public function indexAction(){
        if(!$this->app->isAuthorized())
            return $this->resp(401);

        list($page, $rpp) = $this->req->getPager();

        $cond = $this->req->getCond(['q']);

        $albums = MAlbum::get($cond, $rpp, $page, ['id'=>false]) ?? [];
        if($albums)
            $albums = Formatter::formatMany('music-album', $albums);

        $this->resp(0, $albums, null, [
            'meta' => [
                'total' => MAlbum::count($cond),
                'page'  => $page,
                'rpp'   => $rpp
            ]
        ]);
    }

    public function singleAction(){
        if(!$this->app->isAuthorized())
            return $this->resp(401);

        $identity = $this->req->param->identity;
        $album = MAlbum::getOne(['id'=>$identity]);
        if(!$album)
            $album = MAlbum::getOne(['slug'=>$identity]);
        if(!$album)
            return $this->show404();

        $musics = Music::get(['album'=>$album->id]) ?? [];
        if($musics){
            $musics = Formatter::formatMany('music', $musics);
            foreach($musics as &$music){
                unset($music->content);
                unset($music->meta);
            }
            unset($music);
        }

        $album = Formatter::format('music-album', $album, ['user']);

        $album->musics = $musics;

        $this->resp(0, $album);
    }
}