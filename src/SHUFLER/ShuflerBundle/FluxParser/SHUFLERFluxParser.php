<?php
namespace SHUFLER\ShuflerBundle\FluxParser;

class SHUFLERFluxParser
{
  /**
   * convertit flux xml en \SimpleXMLElement
   *
   * @param XML $file
   * @return \SimpleXMLElement
   */
  public function convertXML($file)
  {
    return @simplexml_load_file($file)->{'channel'}->{'item'};
  }
  
  public function convertXML2($file)
  {
  	return @simplexml_load_file($file)->{'entry'};
  }
}