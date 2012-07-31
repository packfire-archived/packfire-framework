<?php
pload('packfire.io.file.pPath');
pload('packfire.template.moustache.pMoustacheTemplate');

/**
 * pAppGenerator class
 * 
 * Generates a new copy of application
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.generator
 * @since 1.0-sofia
 */
class pAppGenerator {
    
    /**
     * The root folder to install the application
     * @var string
     * @since 1.0-sofia
     */
    private $root;
    
    /**
     * The root folder that contains an installation of
     *          Packfire Framework
     * @var string
     * @since 1.0-sofia
     */
    private $packfire;
    
    /**
     * Create a new pAppGenerator
     * @param string $root The root folder to install a new copy of the 
     *          application
     * @param string $packfire The root folder that contains an installation of
     *          Packfire Framework
     * @since 1.0-sofia
     */
    public function __construct($root, $packfire){
        $this->root = $root;
        $this->packfire = $packfire;
    }
    
    /**
     * Process a file, whether to copy or parse template
     * @param string $template Full path to the skeleton file
     * @param string $file The file to write to
     * @param mixed $parameters (optional) The parameters to set to the
     *                  template. If no parameters were set, the method will
     *                  perform a plain copy of the file to the output.
     * @since 1.0-sofia
     */
    protected function renderFile($template, $file, $parameters = null){
        $path = pPath::path($file);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        
        if(func_num_args() == 3){
            $moustache = new pMoustacheTemplate(file_get_contents($template));
            $moustache->set($parameters);

            file_put_contents($file, $moustache->parse());
        }else{
            copy($template, $file);
        }
    }
    
    /**
     * Perform the generation
     * @since 1.0-sofia 
     */
    public function generate(){
        $path = dirname(__FILE__);
        $this->renderFile($path . '/resource/application/favicon.ico',
                pPath::combine($this->root, 'favicon.ico'));
        $this->renderFile($path . '/resource/application/.htaccess',
                pPath::combine($this->root, '.htaccess'));
        $this->renderFile($path . '/resource/application/index.php',
                pPath::combine($this->root, 'index.php'));
        
        $this->renderFile($path . '/resource/application/pack/constants.php',
                pPath::combine($this->root, 'pack/constants.php'),
                array('packfirePath' => addslashes($this->packfire)));
        $this->renderFile($path . '/resource/application/pack/torch.php',
                pPath::combine($this->root, 'pack/torch.php'));
        $this->renderFile($path . '/resource/application/pack/torch',
                pPath::combine($this->root, 'pack/torch'));
        $this->renderFile($path . '/resource/application/pack/torch.bat',
                pPath::combine($this->root, 'pack/torch.bat'));
        $this->renderFile($path . '/resource/application/pack/readme.md',
                pPath::combine($this->root, 'pack/readme.md'));
        
        $this->renderFile($path . '/resource/application/assets/images/readme.md',
                pPath::combine($this->root, 'assets/images/readme.md'));
        $this->renderFile($path . '/resource/application/assets/images/packfire.biglogo.dark.png',
                pPath::combine($this->root, 'assets/images/packfire.biglogo.dark.png'));
        $this->renderFile($path . '/resource/application/assets/images/packfire.biglogo.light.png',
                pPath::combine($this->root, 'assets/images/packfire.biglogo.light.png'));
        
        $this->renderFile($path . '/resource/application/assets/scripts/readme.md',
                pPath::combine($this->root, 'assets/scripts/readme.md'));
        $this->renderFile($path . '/resource/application/assets/scripts/packfire.js',
                pPath::combine($this->root, 'assets/scripts/packfire.js'));
        $this->renderFile($path . '/resource/application/assets/scripts/pfdebugger.js',
                pPath::combine($this->root, 'assets/scripts/pfdebugger.js'));
        
        $this->renderFile($path . '/resource/application/assets/styles/readme.md',
                pPath::combine($this->root, 'assets/styles/readme.md'));
        $this->renderFile($path . '/resource/application/assets/styles/dark.css',
                pPath::combine($this->root, 'assets/styles/dark.css'));
        $this->renderFile($path . '/resource/application/assets/styles/global.css',
                pPath::combine($this->root, 'assets/styles/global.css'));
        $this->renderFile($path . '/resource/application/assets/styles/light.css',
                pPath::combine($this->root, 'assets/styles/light.css'));
        
        $this->renderFile($path . '/resource/application/pack/app/AppController.php',
                pPath::combine($this->root, 'pack/app/AppController.php'));
        $this->renderFile($path . '/resource/application/pack/app/AppModel.php',
                pPath::combine($this->root, 'pack/app/AppModel.php'));
        $this->renderFile($path . '/resource/application/pack/app/AppSecurityModule.php',
                pPath::combine($this->root, 'pack/app/AppSecurityModule.php'));
        $this->renderFile($path . '/resource/application/pack/app/AppTemplate.php',
                pPath::combine($this->root, 'pack/app/AppTemplate.php'));
        $this->renderFile($path . '/resource/application/pack/app/AppTheme.php',
                pPath::combine($this->root, 'pack/app/AppTheme.php'));
        $this->renderFile($path . '/resource/application/pack/app/AppView.php',
                pPath::combine($this->root, 'pack/app/AppView.php'));
        $this->renderFile($path . '/resource/application/pack/app/Application.php',
                pPath::combine($this->root, 'pack/app/Application.php'));
        $this->renderFile($path . '/resource/application/pack/app/readme.md',
                pPath::combine($this->root, 'pack/app/readme.md'));
        
        $this->renderFile($path . '/resource/application/pack/config/app.local.yml',
                pPath::combine($this->root, 'pack/config/app.local.yml'));
        $this->renderFile($path . '/resource/application/pack/config/app.yml',
                pPath::combine($this->root, 'pack/config/app.yml'));
        $this->renderFile($path . '/resource/application/pack/config/ioc.yml',
                pPath::combine($this->root, 'pack/config/ioc.yml'));
        $this->renderFile($path . '/resource/application/pack/config/routing.yml',
                pPath::combine($this->root, 'pack/config/routing.yml'));
        $this->renderFile($path . '/resource/application/pack/config/clirouting.yml',
                pPath::combine($this->root, 'pack/config/clirouting.yml'));
        $this->renderFile($path . '/resource/application/pack/config/readme.md',
                pPath::combine($this->root, 'pack/config/readme.md'));
        
        $this->renderFile($path . '/resource/application/pack/controller/readme.md',
                pPath::combine($this->root, 'pack/controller/readme.md'));
        
        $this->renderFile($path . '/resource/application/pack/library/readme.md',
                pPath::combine($this->root, 'pack/library/readme.md'));
        $this->renderFile($path . '/resource/application/pack/model/readme.md',
                pPath::combine($this->root, 'pack/model/readme.md'));
        
        $this->renderFile($path . '/resource/application/pack/test/readme.md',
                pPath::combine($this->root, 'pack/test/readme.md'));
        $this->renderFile($path . '/resource/application/pack/test/bootstrap.php',
                pPath::combine($this->root, 'pack/test/bootstrap.php'),
                array('packfirePath' => addslashes($this->packfire)));
        $this->renderFile($path . '/resource/application/pack/test/configuration.xml',
                pPath::combine($this->root, 'pack/test/configuration.xml'));
        
        $this->renderFile($path . '/resource/application/pack/storage/cache/readme.md',
                pPath::combine($this->root, 'pack/storage/cache/readme.md'));
        $this->renderFile($path . '/resource/application/pack/storage/log/readme.md',
                pPath::combine($this->root, 'pack/storage/log/readme.md'));
        $this->renderFile($path . '/resource/application/pack/storage/temp/readme.md',
                pPath::combine($this->root, 'pack/storage/temp/readme.md'));
        
        $this->renderFile($path . '/resource/application/pack/template/readme.md',
                pPath::combine($this->root, 'pack/template/readme.md'));
        
        $this->renderFile($path . '/resource/application/pack/theme/readme.md',
                pPath::combine($this->root, 'pack/theme/readme.md'));
        
        $this->renderFile($path . '/resource/application/pack/view/readme.md',
                pPath::combine($this->root, 'pack/view/readme.md'));
        
        $storagePath = new pPath(pPath::combine($this->root, 'pack/storage'));
        $storagePath->permission(0755);
    }
    
}