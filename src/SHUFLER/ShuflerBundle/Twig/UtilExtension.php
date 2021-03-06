<?php
namespace SHUFLER\ShuflerBundle\Twig;

class UtilExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('dateFlux', array(
                $this,
                'dateFluxFilter'
            )),
            new \Twig_SimpleFilter('imageSubs', array(
                $this,
                'imageSubsFilter'
            )),
            new \Twig_SimpleFilter('stripAccents', array(
                $this,
                'stripAccentsFilter'
            ))
        );
    }

    public function dateFluxFilter($dateRaw)
    {
        $d = \DateTime::createFromFormat("D, d M Y H:i:s T", $dateRaw);
        if ($d) {
            $date = $d->format('d M Y H:i:s');
            return $date;
        }
        return $dateRaw;
    }

    public function imageSubsFilter($url)
    {
        $pos = strpos($url, '?');
        $image = substr($url, 0, $pos);
        return $image;
    }

    public function stripAccentsFilter($string) {
        return strtr(utf8_decode($string),utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'),
            'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
    }
    
    public function getName()
    {
        return 'util_extension';
    }
}