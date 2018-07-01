<?php
namespace SHUFLER\ShuflerBundle\Twig;

use SHUFLER\ShuflerBundle\Entity\Video;

class ShuflerExtension extends \Twig_Extension
{

    const PATTERN_HTTP = '/^(http)?(s)?(:)?(\/\/)?';

    /**
     *
     * {@inheritdoc}
     *
     * @see Twig_Extension::getFilters()
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('categorieDisplay', array(
                $this,
                'categoryFilter'
            )),
            new \Twig_SimpleFilter('genreDisplay', array(
                $this,
                'genreFilter'
            )),
            new \Twig_SimpleFilter('yearDisplay', array(
                $this,
                'yearFilter'
            )),
            new \Twig_SimpleFilter('convertFrame', array(
                $this,
                'convertFrameFilter'
            )),
            new \Twig_SimpleFilter('popUp', array(
                $this,
                'popUpFilter'
            ))
        );
    }

    /**
     * Display category
     *
     * @param unknown $categorie            
     * @return string
     */
    public function categoryFilter($categorie)
    {
        $categories = Video::CATEGORY_LIST;
        
        return isset($categories[$categorie]) ? $categories[$categorie] : 'autre';
    }

    public function genreFilter($genre = -1)
    {
        $genres = Video::GENRE_LIST;
        
        return $genres[$genre];
    }

    /**
     * Display Year
     *
     * @param unknown $annee            
     * @return void|unknown
     */
    public function yearFilter($annee)
    {
        if ($annee != - 1 && $annee != null && $annee != 0) {
            return $annee;
        }
        return;
    }

    /**
     * Sanitize URL terms for regexp
     *
     * @param unknown $terme            
     * @return mixed
     */
    private function sanitize($terme)
    {
        return str_replace('/', '\/', $terme);
    }

    /**
     * 
     * @param String $lien
     * @return string
     */
    public function getPlatform($lien){
        if (preg_match(self::PATTERN_HTTP . $this->sanitize(Video::YOUTUBE_WWW) . '/', $lien)) {
            return Video::YOUTUBE;
        } elseif (preg_match(self::PATTERN_HTTP . $this->sanitize(Video::VIMEO_PLAYER) . '/', $lien)) {
            return Video::VIMEO;
        } elseif (preg_match(self::PATTERN_HTTP . $this->sanitize(Video::DAILYMOTION_WWW) . '/', $lien)) {
            return Video::DAILYMOTION;
        }
    }
    
    /**
     * 
     * @param String $lien
     * @param String $platform
     * @return string
     */
    public function getIdentifer($lien, $platform) {
        $vid = null;
        $vid = mb_strrchr($lien, '/');
        if($platform === Video::YOUTUBE) {
            if (mb_strrchr($lien, '=')) {
                $vid = mb_strrchr($lien, '=');
            }
            $vid = substr($vid, - strlen($vid) + 1);
        } else if($platform === Video::VIMEO){
            $vid = substr($vid, - strlen($vid) + 1);
        } else if($platform === Video::DAILYMOTION) {
            
        }
        
        return $vid;
    }
    
    /**
     * Display Frames
     *
     * @param unknown $lien            
     * @return string
     */
    public function convertFrameFilter($lien)
    {
        $frame_prefix = '<img class="embed-responsive-item" src="';
        $width = '100%';
        $frame = $frame_prefix . Video::VIDEO_UNAVAILABLE . '" width=' . $width . ' />';
        
        $platform = $this->getPlatform($lien);

        $vid = $this->getIdentifer($lien, $platform);
               
        if ($platform === Video::YOUTUBE) {
            $video = Video::YOUTUBE_API . $vid .  '/0.jpg';
            $frame = $frame_prefix . $video . '" width=' . $width . ' />';
            
        } elseif ($platform === Video::VIMEO) {

            try {
                $data = null;
                if ($vid != 112297136) { // Exception sur id (pas le choix) --- #la merde
                    $data = file_get_contents(Video::VIMEO_API . $vid . '.json');
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $data = null;
            }
            if ($data != null && $data = json_decode($data)) {
                $frame = $frame_prefix . $data[0]->thumbnail_medium . '" width=' . $width . ' />';
            }
        } elseif ($platform === Video::DAILYMOTION) {
            try {
                if (strstr($lien, 'http')) {
                    $vid = $lien;
                } elseif (strstr($lien, '//')) {
                    $vid = 'http:' . $lien;
                } else {
                    $vid = 'http://' . $lien;
                }
                
                $data = file_get_contents(Video::DAILYMOTION_API . $vid);
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $data = null;
            }
            
            if ($data && $data = json_decode($data)) {
                $frame = $frame_prefix . $data->thumbnail_url . '" width=' . $width . ' />';
            }
        }
        return $frame;
    }


    /**
     * Display Video Pop-up
     *
     * @param unknown $lien            
     * @return string|unknown
     */
    public function popUpFilter($lien)
    {
        $link = $lien;
        $platform = $this->getPlatform($lien);
        $id = $this->getIdentifer($lien, $platform);
        if ($platform === Video::YOUTUBE) {
            $link = Video::YOUTUBE_WATCH . $id;
        } elseif ($platform === Video::DAILYMOTION) {
            $link = Video::DAILYMOTION_VIDEO . $id;
        }
        
        return $link;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Twig_Extension::getName()
     */
    public function getName()
    {
        return 'shufler_extension';
    }
}
