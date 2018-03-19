<?php
namespace SHUFLER\ShuflerBundle\Twig;

use SHUFLER\ShuflerBundle\Entity\Video;

class ShuflerExtension extends \Twig_Extension
{

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
    protected function sanitize($terme)
    {
        return str_replace('/', '\/', $terme);
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
        $frame_unavailable = $frame_prefix . Video::VIDEO_UNAVAILABLE . '" width=' . $width . ' />';
        
        if (preg_match('/^(http:)?(\/\/)?' . $this->sanitize(Video::YOUTUBE_EMBED) . '/', $lien)) {
            $vid = Video::YOUTUBE_API;
            if (mb_strrchr($lien, '=')) {
                $vid .= mb_strrchr($lien, '=');
            } else {
                $vid .= mb_strrchr($lien, '/');
            }
            $vid .= '/0.jpg';
            $frame = $frame_prefix . $vid . '" width=' . $width . ' />';
        } elseif (preg_match('/^(http:)?(\/\/)?' . $this->sanitize(Video::VIMEO_PLAYER) . '/', $lien)) {
            $id = mb_strrchr($lien, '/');
            try {
                $data = null;
                if ($id != 112297136) { // Exception sur id (pas le choix) --- #la merde
                    $data = file_get_contents(Video::VIMEO_API . $id . '.json');
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $data = null;
            }
            if ($data != null && $data = json_decode($data)) {
                $frame = $frame_prefix . $data[0]->thumbnail_medium . '" width=' . $width . ' />';
            } else {
                $frame = $frame_unavailable;
            }
        } elseif (preg_match('/^(http:)?(\/\/)?' . $this->sanitize(Video::DAILYMOTION_EMBED) . '/', $lien)) {
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
            } else {
                $frame = $frame_unavailable;
            }
        } else {
            $frame = $frame_unavailable;
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
        if (preg_match('/^(http:)?(\/\/)?' . $this->sanitize(Video::YOUTUBE_EMBED) . '/', $lien)) {
            if (mb_strrchr($lien, '=')) {
                $id = mb_strrchr($lien, '=');
            } else {
                $id = mb_strrchr($lien, '/');
            }
            $link = Video::YOUTUBE_WATCH . $id;
        } elseif (preg_match('/^(http:)?(\/\/)?' . $this->sanitize(Video::DAILYMOTION_EMBED) . '/', $lien)) {
            $id = mb_strrchr($lien, '/');
            $link = Video::DAILYMOTION_VIDEO . $id;
        } else {
            $link = $lien;
        }
        
        return $link;
    }

    public function getName()
    {
        return 'shufler_extension';
    }
}
