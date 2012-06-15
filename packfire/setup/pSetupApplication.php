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
        echo "\nPackfire Framework\n";
        echo "------------------------\n";
        if($cliParser->isFlagged('-i', '--install')){
                $root = $cliParser->getValue('-i', '--install');
                $controller->params()->add('root', $root);
                if($root){
                    echo "Setup will now install Packfire Framework to: \n";
                    echo "   "  . $root . "\n\n";
                    echo "Copying files... ";
                    $controller->run($route, 'installFramework');
                    echo "Done!\n\n";
                    echo "Installation of Packfire Framework is now complete.\n";
                }else{
                    echo "Invalid parameters supplied.\nPackfire Setup will now exit.\n";
                }
        }elseif($cliParser->isFlagged('-c', '--create')){
                $root = $cliParser->getValue('-c', '--create');
                $controller->params()->add('root', $root);
                if($root){
                    echo "Packfire will now create a new application to: \n";
                    echo "   "  . $root . "\n\n";
                    $framework = $cliParser->getValue('-p', '--packfire');
                    
                    while($framework == null){
                        echo "Where did you install Packfire Framework?\n";
                        echo "> ";
                        $framework = fgets(STDIN);
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
        }else{
                echo "\nVisit us at http://github.com/packfire\n";
                echo "\nFramework Version: " . __PACKFIRE_VERSION__ . "\n\n";
                echo "To use the setup:\n";
                echo "   packfire [-i | --install] [root]\n";
                echo "   packfire [-c | --create] [root]\n";
                echo "   packfire [-c | --create] [root] [-p | --packfire] [packfire]\n";
                echo "   packfire [-h | --help]\n";
                echo "\nOptions:\n";
                echo "   -i or --install : Install the Framework\n";
                echo "   -c or --create : Create a new Packfire Application\n";
                echo "   -p or --packfire : Set the Packfire Framework\n          Directory when installing\n";
                echo "   -h or --help : Show this help screen\n";
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