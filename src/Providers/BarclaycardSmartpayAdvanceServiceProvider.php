<?php

namespace Cws\BarclaycardSmartpayAdvance\Providers;

use Illuminate\Support\ServiceProvider;

class BarclaycardSmartpayAdvanceServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $packagePath = realpath(__DIR__.'/../../');
        

        // Publish/Merge Config
        $this->mergeConfigFrom("{$packagePath}/config/barclaycard-smartpay-advance.php", 'barclaycard-smartpay-advance');
        $this->publishes(["{$packagePath}/config/" => config_path()], ['config']);
    }
}