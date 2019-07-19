<?php
namespace SHUFLER\ShuflerBundle\Entity;

interface ChannelInterface
{
    public function setName($name);

    public function getName();

    public function setImage($image = null);

    public function getImage();

    public function setOldImage($image = null);

    public function getOldImage();

    public function getProviderId();

    public function getChannelClass();
}