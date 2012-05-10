<?php
pload('packfire.application.IApplication');
pload('pSetupController');
pload('packfire.net.http.pHttpMethod');
pload('packfire.routing.pRoute');
pload('packfire.ioc.pServiceBucket');

/**
 * pSetupApplication Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.setup
 * @since 1.0-sofia
 */
class pSetupApplication implements IApplication {
    
    public function handleException($exception) {
        var_dump($exception);
        die('Exception Occurred');
    }
    
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
    
    protected function loadCliCall($request, $controller){
        $instruction = '';
        if($request->params()->count() > 1){
            $instruction = $request->params()->get(1);
            if(substr($instruction, 0, 2) == '--'){
                $instruction = substr($instruction, 2);
            }elseif(substr($instruction, 0, 1) == '-' && strlen($instruction) == 2){
                switch(strtolower($instruction)){
                    case '-i':
                        $instruction = 'install';
                        break;
                    case '-c':
                        $instruction = 'create';
                        break;
                }
            }
        }
        $route = new pRoute('', '', '');
        echo "\nPackfire Framework Setup\n";
        echo "------------------------\n";
        switch(strtolower($instruction)){
            case 'install':
                $root = $request->params()->get(2);
                if($root){
                    echo "Setup will now install Packfire Framework to: \n";
                    echo "   "  . $root . "\n\n";
                    $route = new pRoute('', '', '', array('root' => $request->params()->get(2)));
                    echo "Copying files... ";
                    $controller->run($route, 'installFramework');
                    echo "Done!\n\n";
                    echo "Installation of Packfire Framework is now complete.\n";
                }else{
                    echo "Invalid parameters supplied.\nPackfire Setup will now exit.\n";
                }
                break;
            case 'create':
                $root = $request->params()->get(2);
                if($root){
                    echo "Packfire will now create a new application to: \n";
                    echo "   "  . $root . "\n\n";
                    $framework = null;
                    while($framework == null){
                        echo "Where did you install Packfire Framework?\n";
                        echo "> ";
                        $framework = fgets(STDIN);
                        echo "\n";
                        echo "Setting Framework to " . $framework . "\n\n";
                        if(!file_exists(pPath::combine($framework, 'Packfire.php'))){
                            $framework = null;
                            echo "Error: Setup could not locate Packfire Framework installed at that location.\n\n";
                        }
                    }
                    echo "Copying files... ";
                    $route = new pRoute('', '', '', array(
                            'root' => $request->params()->get(2),
                            'packfire' => trim($framework)
                        ));
                    $controller->run($route, 'createApplication');
                    echo "Done!\n\n";
                    echo "Creation of a new Packfire Application is now complete.\n";
                }else{
                    echo "Invalid parameters supplied.\nPackfire Setup will now exit.\n";
                }
                break;
            default:
                echo "\nVisit us at http://github.com/packfire\n";
                echo "\nFramework Version: " . __PACKFIRE_VERSION__ . "\n\n";
                echo "To use the setup:\n";
                echo "   setup [-i | --install | install] [root]\n";
                echo "   setup [-c | --create | create] [root]\n";
                echo "   setup [-h | --help]\n";
                echo "\nOptions:\n";
                echo "   -i or --install : Install the Framework\n";
                echo "   -c or --create : Create a new Packfire Application\n";
                echo "   -h or --help : Show this help screen\n";
                echo "   root : The root directory to install framework\n          or create new application\n";
                break;
        }
    }
    
    protected function loadHttpCall($request, $controller){
        $route = new pRoute('', '', $request->method(), $request->params());
        if($request->get()->count() > 0){
            switch(strtolower($request->get()->first())){
                case 'install':
                    $controller->run($route, 'installFramework');
                    break;
                case 'create':
                    $controller->run($route, 'createApplication');
                    break;
            }
        }else{
            $controller->run($route, 'welcome');
            $controller->render(new pSetupWelcomeView());
        }
        return $controller;
    }
}