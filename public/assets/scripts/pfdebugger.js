/**
 * Packfire Debugger 
 * This is the JavaScript file required for the pConsoleDebugOutput to work
 * properly. 
 * 
 * File is part of Packfire Framework
 * Copyright (c) 2010-2012, Sam-Mauris Yong
 * Licensed under http://www.opensource.org/licenses/bsd-license New BSD License
 */

var PfConsoleDebugger = {
    
    showByTab: function(type){
        var lines = document.getElementsByClassName('pfLine');
        for(n in lines){
            line = lines[n];
            try{
                line.style.display = 'none';
            }catch(ex){}
        }
        lines = document.getElementsByClassName(type);
        for(n in lines){
            line = lines[n];
            try{
                line.style.display = 'block';
            }catch(ex){}
        }
    },
    id: function(id){
        return document.getElementById(id);
    }
};

(function(){
    PfConsoleDebugger.id('pfDebuggerTabs').style.display = 'block';
    PfConsoleDebugger.id('pfDebuggerConsole').style.display = 'block';
    PfConsoleDebugger.id('pfDebuggerMinimize').onclick = function(){
        var s = PfConsoleDebugger.id('pfDebuggerConsole');
        if(s.style.display == 'block'){
            s.style.display = 'none';
            s.innerHtml = '+';
        }else{
            s.style.display = 'block';
            s.innerHtml = '_';
        }
        return false;
    }
})();


if (document.getElementsByClassName == undefined) {
	document.getElementsByClassName = function(className){
            var hasClassName = new RegExp("(?:^|\\s)" + className + "(?:$|\\s)");
            var allElements = document.getElementsByTagName("*");
            var results = [];

            var element;
            for (var i = 0; (element = allElements[i]) != null; i++) {
                    var elementClass = element.className;
                    if (elementClass && elementClass.indexOf(className) != -1 && hasClassName.test(elementClass))
                            results.push(element);
            }

            return results;
	}
}