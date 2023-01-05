(() => {
    "use strict";

    angular.module('app.core')
        .factory('req', req);

    req.$inject = ['$http', 'exception', '$window'];

    function req($http, exception, $window)
    {
        let baseUrl = "/" + $window.location.pathname.split('/')[1] + "/api";

        return {
            get: get,
            post: post,
            put: put,
            del, del
        };


        function get(url)
        {
            return $http.get(`${baseUrl}/${url}`).then(success).catch(fail);
        }

        function post(url, data)
        {
            return $http.post(`${baseUrl}/${url}`, data).then(success).catch(fail);
        }
        
        function put(url, data)
        {
            return $http.put(`${baseUrl}/${url}`, data).then(success).catch(fail);
        }
        
        function del(url, data)
        {
            return $http({
                method: "DELETE",
                url: `${baseUrl}/${url}`,
                data: data,
                headers: {
                    'Content-type': 'application/json;charset=utf-8'
                }
            }).then(success).catch(fail);
        }


        function success(response)
        {
            return response.data;
        }

        function fail(err)
        {
            return exception.catcher('XHR Failed for get data')(err);
        }
    }
})()