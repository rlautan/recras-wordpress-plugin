<?php
namespace Recras;

class Analytics
{
    public static function useAnalytics()
    {
        return get_option('recras_enable_analytics');
    }
}
