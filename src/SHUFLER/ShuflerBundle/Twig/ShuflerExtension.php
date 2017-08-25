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

    public function genreFilter($genre)
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
     * Display Frames
     *
     * @param unknown $lien            
     * @return string
     */
    public function convertFrameFilter($lien)
    {
        $width = '100%';
        $yt1 = "http://www.youtube.com/embed/";
        $yt2 = "//www.youtube.com/embed/";
        $yt3 = "https://www.youtube.com/watch?v=";
        $vid = "http://img.youtube.com/vi/";
        $dm = "http://www.dailymotion.com/embed/video/";
        $dm2 = "//www.dailymotion.com/embed/video/";
        $vid2 = "http://www.dailymotion.com/thumbnail/video/";
        $vim = "http://player.vimeo.com/video/";
        $vim2 = "//player.vimeo.com/video/";
        
        if (mb_substr($lien, 0, strlen($yt1)) == $yt1) {
            $vid .= substr($lien, strlen($yt1));
            $vid .= "/0.jpg";
            $frame = "<img class='embed-responsive-item' src='" . $vid . "' width=" . $width . " />";
        } elseif (mb_substr($lien, 0, strlen($yt2)) == $yt2) {
            $vid .= substr($lien, strlen($yt2));
            $vid .= "/0.jpg";
            $frame = "<img class='embed-responsive-item' src='" . $vid . "' width=" . $width . " />";
        } elseif (mb_substr($lien, 0, strlen($yt3)) == $yt3) {
            $vid .= substr($lien, strlen($yt3));
            $vid .= "/0.jpg";
            $frame = "<img class='embed-responsive-item' src='" . $vid . "' width=" . $width . " />";
        } elseif (mb_substr($lien, 0, strlen($vim)) == $vim) {
            $id = substr($lien, strlen($vim));
            try {
                $data = file_get_contents("http://vimeo.com/api/v2/video/$id.json");
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $data = null;
            }
            if ($data != null && $data = json_decode($data)) {
                $frame = "<img class='embed-responsive-item' src='" . $data[0]->thumbnail_medium . "' width=" . $width . " />";
            } else {
                $frame = "<img class='embed-responsive-item' src='http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033' width=" . $width . " />";
            }
        } elseif (mb_substr($lien, 0, strlen($vim2)) == $vim2) {
            $id = substr($lien, strlen($vim2));
            try {
                if ($id != 112297136) { // Exception sur id (pas le choix) --- #la merde
                    $data = file_get_contents("http://vimeo.com/api/v2/video/$id.json");
                } else {
                    $data = null;
                }
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $data = null;
            }
            if ($data != null && $data = json_decode($data)) {
                $frame = "<img class='embed-responsive-item' src='" . $data[0]->thumbnail_medium . "' width=" . $width . " />";
            } else {
                $frame = "<img class='embed-responsive-item' src='http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033' width=" . $width . " />";
            }
        } elseif (mb_substr($lien, 0, strlen($dm)) == $dm) {
            try {
                $data = file_get_contents('http://www.dailymotion.com/services/oembed?url=' . $lien);
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $data = null;
            }
            if ($data && $data = json_decode($data)) {
                $frame = "<img class='embed-responsive-item' src='" . $data->thumbnail_url . "' width=" . $width . " />";
            } else {
                $frame = "<img class='embed-responsive-item' src='http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033' width=" . $width . " />";
            }
        } elseif (mb_substr($lien, 0, strlen($dm2)) == $dm2) {
            try {
                $data = file_get_contents('http://www.dailymotion.com/services/oembed?url=http:' . $lien);
            } catch (\Exception $e) {
                error_log($e->getMessage());
                $data = null;
            }
            if ($data && $data = json_decode($data)) {
                $frame = "<img class='embed-responsive-item' src='" . $data->thumbnail_url . "' width=" . $width . " />";
            } else {
                $frame = "<img class='embed-responsive-item' src='http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033' width=" . $width . " />";
            }
        } else {
            $frame = "<img class='embed-responsive-item' src='http://s3.amazonaws.com/colorcombos-images/users/1/color-schemes/color-scheme-2-main.png?v=20111009081033' width=" . $width . " />";
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
        $yt1 = "http://www.youtube.com/embed/";
        $yt2 = "//www.youtube.com/embed/";
        $vid = "https://www.youtube.com/watch?v=";
        $dm = "http://www.dailymotion.com/embed/video/";
        $dm2 = "//www.dailymotion.com/embed/video/";
        $vid2 = "http://www.dailymotion.com/video/";
        
        if (mb_substr($lien, 0, strlen($yt1)) == $yt1) {
            $link = $vid . substr($lien, strlen($yt1));
        } elseif (mb_substr($lien, 0, strlen($yt2)) == $yt2) {
            $link = $vid . substr($lien, strlen($yt2));
        } elseif (mb_substr($lien, 0, strlen($dm)) == $dm) {
            $link = $vid2 . substr($lien, strlen($dm));
        } elseif (mb_substr($lien, 0, strlen($dm2)) == $dm2) {
            $link = $vid2 . substr($lien, strlen($dm2));
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
