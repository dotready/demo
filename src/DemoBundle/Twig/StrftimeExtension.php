<?php

namespace DemoBundle\Twig;

class StrftimeExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('strftime', array($this, 'strftime')),
        );
    }

    /**
     * @todo remove hardcoded setLocale
     *
     * @param \DateTime $dateTime
     * @param $format
     *
     * @return string
     */
    public function strftime(\DateTime $dateTime, $format)
    {
        setLocale(LC_TIME, 'nl_NL');
        return strftime($format, $dateTime->getTimestamp());
    }

    public function getName()
    {
        return 'time_extension';
    }
}