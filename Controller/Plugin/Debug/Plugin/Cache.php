<?php

class Leek_Controller_Plugin_Debug_Plugin_Cache extends ZFDebug_Controller_Plugin_Debug_Plugin_Cache
{
    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
        $panel = '';

        # Support for APC
        if (function_exists('apc_sma_info') && ini_get('apc.enabled')) {
            $mem   = apc_sma_info();
            $cache = apc_cache_info();

            if (!empty($mem) && !empty($cache)) {
                $mem_size = $mem['num_seg']*$mem['seg_size'];
                $mem_avail = $mem['avail_mem'];
                $mem_used = $mem_size-$mem_avail;

                $cache = apc_cache_info();

                $panel .= '<h4>APC '.phpversion('apc').' Enabled</h4>';
                $panel .= round($mem_avail/1024/1024, 1).'M available, '.round($mem_used/1024/1024, 1).'M used<br />'
                        . $cache['num_entries'].' Files cached ('.round($cache['mem_size']/1024/1024, 1).'M)<br />'
                        . $cache['num_hits'].' Hits ('.round($cache['num_hits'] * 100 / ($cache['num_hits']+$cache['num_misses']), 1).'%)<br />'
                        . $cache['expunges'].' Expunges (cache full count)';
            }

        }

        foreach ($this->_cacheBackends as $name => $backend) {
            $fillingPercentage = $backend->getFillingPercentage();
            $ids = $backend->getIds();

            # Print full class name, backends might be custom
            $panel .= '<h4>Cache '.$name.' ('.get_class($backend).')</h4>';
            $panel .= count($ids).' Entr'.(count($ids)>1?'ies':'y').'<br />'
                    . 'Filling Percentage: '.$backend->getFillingPercentage().'%<br />';

            $cacheSize = 0;
            foreach ($ids as $id)
            {
                # Calculate valid cache size
                $mem_pre = memory_get_usage();
                if ($cached = $backend->load($id)) {
                    $mem_post = memory_get_usage();
                    $cacheSize += $mem_post-$mem_pre;
                    unset($cached);
                }
            }
            $panel .= 'Valid Cache Size: '.round($cacheSize/1024, 1). 'K';
        }
        return $panel;
    }
}
