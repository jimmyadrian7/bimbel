(() => {
    "use strict";

    const angularModule = [        
        'app.core',
        'app.layout',
        'app.utils',
        'app.module'
    ];
    
    angular.module('app', angularModule);
})()