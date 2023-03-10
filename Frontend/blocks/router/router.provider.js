(() => {
    "use strict";

    angular.module('blocks.router')
        .provider('routerHelper', routerHelperProvider);

    
    routerHelperProvider.$inject = ['$locationProvider', '$stateProvider', '$urlRouterProvider'];

    function routerHelperProvider($locationProvider, $stateProvider, $urlRouterProvider)
    {
        var config = {
            docTitle: undefined,
            resolveAlways: {}
        };

        if (!(window.history && window.history.pushState))
        {
            window.location.hash = '/';
        }

        // remove hashbang (!#) from url
        // $locationProvider.html5Mode(true);

        this.configure = function(cfg)
        {
            angular.extend(config, cfg);
        };

        this.$get = RouterHelper;
        RouterHelper.$inject = ['$location', '$rootScope', '$state', 'logger'];

        function RouterHelper($location, $rootScope, $state, logger)
        {
            var handlingStateChangeError = false;
            var hasOtherwise = false;
            var stateCounts = {
                errors: 0,
                changes: 0
            };

            let service = {
                configureStates: configureStates,
                getStates: getStates,
                stateCounts: stateCounts
            };

            init();

            return service;


            function configureStates(states, otherwisePath)
            {
                states.forEach((state) => {
                    state.config.resolve = angular.extend(state.config.resolve || {}, config.resolveAlways);
                    $stateProvider.state(state.state, state.config);
                });

                if (otherwisePath && !hasOtherwise)
                {
                    hasOtherwise = true;
                    $urlRouterProvider.otherwise(otherwisePath);
                }
            }

            function handleRoutingErrors()
            {
                $rootScope.$on(
                    '$stateChangeError', 
                    (event, toState, toParams, fromState, fromParams, error) => {
                        if (handlingStateChangeError) {
                            return;
                        }

                        stateCounts.errors++;
                        handlingStateChangeError = true;

                        let destination = toState && 
                            (toState.title || toState.name || toState.loadedTemplateUrl) || 
                            'unknown target';

                        let msg = 'Error routing to ' + destination + '. ' +
                            (error.data || '') + '. <br/>' + (error.statusText || '') +
                            ': ' + (error.status || '');

                        logger.warning(msg, [toState]);
                        $location.path('/');
                    }
                )
            }

            function getStates()
            {
                return $state.get();
            }

            function updateDocTitle()
            {
                $rootScope.$on(
                    '$stateChangeSuccess',
                    (event, toState, toParams, fromState, fromParams) => {
                        stateCounts.changes++;
                        handlingStateChangeError = false;
                        let title = config.docTitle + ' ' + (toState.title || '');
                        $rootScope.title = title;
                    }
                )
            }

            function init()
            {
                handleRoutingErrors();
                updateDocTitle();
            }
        }
    }
})()