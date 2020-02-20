<?php
/**
 * MusicController
 * @package api-music
 * @version 0.0.1
 */

namespace ApiMusic\Controller;

use LibFormatter\Library\Formatter;
use Music\Model\Music;

class MusicController extends \Api\Controller
{
    public function indexAction(){
        if(!$this->app->isAuthorized())
            return $this->resp(401);

        list($page, $rpp) = $this->req->getPager();

        $cond = $this->req->getCond(['q']);

        $musics = Music::get($cond, $rpp, $page, ['id'=>false]) ?? [];
        if($musics)
            $musics = Formatter::formatMany('music', $musics, ['user', 'album']);

        foreach($musics as &$music){
            unset($music->meta);
            unset($music->content);
            if($music->album){
                unset($music->album->content);
                unset($music->album->meta);
            }
        }
        unset($music);

        $this->resp(0, $musics, null, [
            'meta' => [
                'total' => Music::count($cond),
                'page'  => $page,
                'rpp'   => $rpp
            ]
        ]);
    }

    public function singleAction(){
        if(!$this->app->isAuthorized())
            return $this->resp(401);

        $identity = $this->req->param->identity;
        $music = Music::getOne(['id'=>$identity]);
        if(!$music)
            $music = Music::getOne(['slug'=>$identity]);
        if(!$music)
            return $this->show404();

        $music = Formatter::format('music', $music, ['user','album']);
        unset($music->meta);

        $this->resp(0, $music);
    }
}