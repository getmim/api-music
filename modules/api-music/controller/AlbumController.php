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

        $cond = [];

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

        $id = $this->req->param->id;
        $album = MAlbum::getOne(['id'=>$id]);
        if(!$album)
            return $this->show404();

        $musics = Music::get(['album'=>$album->id]) ?? [];
        if($musics)
            $musics = Formatter::formatMany('music', $musics, ['user']);

        $album = Formatter::format('music-album', $album);

        $album->musics = $musics;

        $this->resp(0, $album);
    }
}