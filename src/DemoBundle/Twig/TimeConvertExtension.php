<?php

namespace DemoBundle\Twig;

class TimeConvertExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('timeconvert', array($this, 'timeconvert')),
        );
    }

    /**
     * @todo remove hardcoded setLocale
     *
     * @param int $seconds
     *
     * @return string
     */
    public function timeconvert($seconds)
    {
        $minutes = 0;

        if ($seconds > 60) {
            $minutes = $seconds / 60;
            $seconds = $seconds % 60;
        }

        return sprintf("%02d", $minutes) . ':' . sprintf("%02d", $seconds);
    }

    public function getName()
    {
        return 'timeconvert_extension';
    }
}
