<?php
    
    /** \defgroup QR_DEFCONFIGS Global Config
    Global config file (contains global configuration-releted constants).
    Before version 2.0.0 only way to configure all calls. From version 2.0.0 values
    used here are treated as __defaults__ but culd be overwriten by additional config. 
    parrameters passed to functions.
    * @{ 
    */
    
    /** Mask cache switch. 
    __Boolean__ Speciffies does mask ant template caching is enabled. 
    - __true__ - disk cache is used, more disk reads are performed but less CPU power is required,
    - __false__ - mask and format templates are calculated each time in memory */
    define('QR_CACHEABLE', true);

    /** Cache dir path.
    __String__ Used when QR_CACHEABLE === true. Specifies absolute server path
    for masks and format templates cache dir  */
    define('QR_CACHE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR);
    
    /** Default error logs dir.
    __String__ Absolute server path for log directory. */
    define('QR_LOG_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);
    
    /** If best mask is found.
    __Boolean__ Speciffies mask searching strategy:
    - __true__ - estimates best mask (as QR-Code spec recomends by default) but may be extremally slow
    - __false__ - check only one mask (specified by __QR_DEFAULT_MASK__), gives significant performance boost but (propably) results in worst quality codes
    */
    define('QR_FIND_BEST_MASK', true);
    
    /** Configure random mask checking.
    Specifies algorithm for mask selection when __QR_FIND_BEST_MASK__ is set to __true__.
    - if Boolean __false__ - checks all masks available
    - if Integer __1..7__ - value tells count of randomly selected masks need to be checked
    */
    define('QR_FIND_FROM_RANDOM', false);
    
    /** Default an only mask to apply.
    __Integer__ Specifies mask no (1..8) to be aplied every time, used when __QR_FIND_BEST_MASK__ is set to __false__.
    */
    define('QR_DEFAULT_MASK', 2);
    
    /** Maximum allowed png image width (in pixels). 
    __Integer__ Maximal width/height of generated raster image.
    Tune to make sure GD and PHP can handle such big images.
    */
    define('QR_PNG_MAXIMUM_SIZE',  1024);                                                       

    /** @}*/