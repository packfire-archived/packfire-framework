<?php
pload('packfire.application.IApplication');
pload('pSetupController');
pload('packfire.net.http.pHttpMethod');
pload('packfire.routing.pRoute');
pload('packfire.ioc.pServiceBucket');

/**
 * The setup application accessible through CLI and Web GUI interfaces
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.setup
 * @since 1.0-sofia
 */
class pSetupApplication implements IApplication {
    
    /**
     * Handle the exception for the setup application
     * @param Exception $exception The exception to be handled.
     * @since 1.0-sofia
     */
    public function handleException($exception) {
        var_dump($exception);
        die('Exception Occurred');
    }
    
    /**
     * Receive and work on the request
     * @param IAppRequest $request The application request
     * @return IAppResponse Returns the response if the request is through HTTP.
     */
    public function receive($request) {
        $controller = new pSetupController($request, new pHttpResponse());
        $controller->setBucket(new pServiceBucket());
        if($request instanceof pCommandRequest){
            // CLI mode
            $this->loadCliCall($request, $controller);
        }else{
            return $this->loadHttpCall($request, $controller);
        }
    }
    
    /**
     * Command Line Interface call loader
     * @param IAppRequest $request The request for the application
     * @param pController $controller The controller to call
     * @since 1.0-sofia
     */
    protected function loadCliCall($request, $controller){
        $cliParser = new pCommandParser();
        $route = new pRoute('', '', '');
        echo 'Packfire Framework ' . __PACKFIRE_VERSION__ . "\n";
        if($cliParser->isFlagged('-i', '--install')){
            echo "------------------------\n";
            $root = $cliParser->getValue('-i', '--install');
            $controller->params()->add('root', $root);
            if($root){
                echo "Installing Packfire Framework to: \n";
                echo "   "  . $root . "\n\n";
                echo "Copying files... ";
                $controller->run($route, 'installFramework');
                echo "Done!\n\n";
                echo "Installation of Packfire Framework is now complete.\n";
            }else{
                echo "Invalid parameters supplied.\nPackfire Setup will now exit.\n";
            }
        }elseif($cliParser->isFlagged('-c', '--create')){
            echo "------------------------\n";
            $root = $cliParser->getValue('-c', '--create');
            $controller->params()->add('root', $root);
            if($root){
                echo "Creating a new Packfire application to: \n";
                echo "   "  . $root . "\n\n";
                $framework = $cliParser->getValue('-p', '--packfire');

                while($framework == null){
                    echo "Where did you install Packfire Framework?\n";
                    echo "Enter blank to set installation path to '" . __PACKFIRE_ROOT__ . "'\n";
                    echo "> ";
                    $framework = fgets(STDIN);
                    if($framework == ''){
                        $framework = __PACKFIRE_ROOT__;
                    }
                    echo "\n";
                    if(!file_exists(pPath::combine($framework, 'Packfire.php'))){
                        $framework = null;
                        echo "Error: Setup could not locate Packfire Framework installed at that location.\n\n";
                    }
                }

                $controller->params()->add('packfire', $framework);
                echo "Setting Framework to " . $framework . "\n\n";
                echo "Copying files... ";
                $controller->run($route, 'createApplication');
                echo "Done!\n\n";
                echo "Creation of a new Packfire Application is now complete.\n";
            }else{
                echo "Invalid parameters supplied.\nPackfire Setup will now exit.\n";
            }
        }elseif($cliParser->getValue(1) == 'version' || $cliParser->isFlagged('-v', '--version')){
            
        }elseif($cliParser->getValue(1) == 'test' || $cliParser->isFlagged('-t', '--test')){
            echo "------------------------\n";
            echo "Performing unit tests with ";
            system('phpunit --version');
            chdir('test');
            system('phpunit --bootstrap bootstrap.php -c configuration.xml .');
        }else{
                echo "------------------------\n";
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
    
    /**
     * HTTP web call loader
     * @param IAppRequest $request The request for the application
     * @param pController $controller The controller to call
     * @return IAppResponse The response to the HTTP call.
     * @since 1.0-sofia
     */
    protected function loadHttpCall($request, $controller){
        $route = new pRoute('', '', $request->method(), $request->params());
        if($request->get()->count() > 0){
            switch(strtolower($request->get()->keys()->first())){
                case 'install':
                    $controller->run($route, 'installFramework');
                    $controller->render(new pSetupInstallView());
                    break;
                case 'create':
                    $controller->run($route, 'createApplication');
                    $controller->render(new pSetupCreateView());
                    break;
            }
        }else{
            $controller->run($route, 'welcome');
            $controller->render(new pSetupWelcomeView());
        }
        return $controller;
    }
}