(function() {
    'use strict';

    let coreModule = [
        'ui.router', 'ngplus', 'ngAnimate',
        'blocks.exception', 'blocks.logger', 'blocks.router',
    ];
  
    angular.module('app.core', coreModule);
})();
  