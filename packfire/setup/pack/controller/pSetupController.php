<?php
pload('packfire.controller.pController');
pload('packfire.generator.pAppGenerator');
pload('packfire.io.file.pPath');
pload('view.pSetupInstallView');
pload('view.pSetupCreateView');

/**
 * pSetupController class
 * 
 * Packfire's Setup Controller
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.setup.controller
 * @since 1.0-sofia
 */
class pSetupController extends pController {
    
    private function installFramework(){
        $root = trim($this->params->get('root'));
        if($root && __PACKFIRE_ROOT__ != $root){
            pPath::copy(__PACKFIRE_ROOT__, $root);
        }
        $this->state['complete'] = true;
        $this->state['root'] = $root;
    }
    
    public function postInstallFramework(){
        $this->installFramework();
        $this->render(new pSetupInstallView());
    }
    
    public function cliInstallFramework(){
        $root = trim($this->params->get('root'));
        if($root){
            echo "Installing Packfire Framework to: \n";
            echo "   "  . $root . "\n\n";
            echo "Copying files... ";
            $this->installFramework();
            echo "Done!\n\n";
            echo "Installation of Packfire Framework is now complete.\n";
        }else{
            echo "Invalid parameters supplied.\nPackfire Setup will now exit.\n";
        }
    }
    
    private function createApplication(){
        $root = trim($this->params->get('root'));
        $packfire = trim($this->params->get('packfire'));
        if($root && $packfire){
            $generator = new pAppGenerator($root, $packfire);
            $generator->generate();
            $this->state['complete'] = true;
            $this->state['root'] = $root;
            $this->state['packfire'] = $packfire;
        }else{
            $this->state['fail'] = true;
        }
    }
    
    public function postCreateApplication(){
        $this->createApplication();
        $this->render(new pSetupCreateView());
    }
    
    public function cliCreateApplication(){
        $root = trim($this->params->get('root'));
        echo "Creating a new Packfire application to: \n";
        echo "   "  . $root . "\n\n";
        $framework = $this->params->get('packfire');

        while($framework == null){
            echo "Where did you install Packfire Framework?\n";
            echo "Enter blank to set installation path to '" . __PACKFIRE_ROOT__ . "'\n";
            echo "> ";
            $framework = trim(fgets(STDIN));
            if($framework == ''){
                $framework = __PACKFIRE_ROOT__;
            }
            echo "\n";
            if(!file_exists(pPath::combine($framework, 'Packfire.php'))){
                $framework = null;
                echo "Error: Setup could not locate Packfire Framework installed at that location.\n\n";
            }
        }

        echo "Setting Framework to " . $framework . "\n\n";
        echo "Copying files... ";
        $this->createApplication();
        echo "Done!\n\n";
        echo "Creation of a new Packfire Application is now complete.\n";
    }
    
    public function cliWelcome(){
        echo "-----------------------------\n";
        echo "\nVisit us at http://mauris.sg/packfire\n\n";
        echo "To use the setup:\n";
        echo "   packfire -i=/path/dir\n";
        echo "   packfire --install /path/dir\n";
        echo "   packfire [version|-v|--version]\n";
        echo "   packfire [test|-t|--test]\n";
        echo "   packfire -c=/path/dir\n";
        echo "   packfire --create /path/dir\n";
        echo "   packfire --create /path/dir --packfire /path/dir\n";
        echo "   packfire [-h | --help]\n";
        echo "\nOptions:\n";
        echo "   -i or --install :\tInstall the Framework\n";
        echo "   -c or --create :\tCreate a new Packfire Application\n";
        echo "   -v or --version :\tDisplay the version of Packfire Framework\n";
        echo "   -t or --test :\tRun the tests for Packfire Framework with PHPUnit\n";
        echo "   -p or --packfire :\tSet the Packfire Framework\n          Directory when installing\n";
        echo "   -h or --help :\tShow this help screen\n";
        echo "   root : The root directory to install framework\n          or create new application\n";
        echo "\n";
    }
    
}