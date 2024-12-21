<?php
return [


    /** 
        Whether to cache the translations. If true, the package will use the cache
        store to store the translations. If false, the package will fetch the
        translations directly from the database.
     */
    'cache_translations' => true,


    /**
     * The lifetime of the translations cache in seconds.
     * This option is only used if cache_translations is set to true.
     * 
     * The default value is 3600 seconds (1 hour).
     * 
     */
    'cache_lifetime' => 3600, 




];
