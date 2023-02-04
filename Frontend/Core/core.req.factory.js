(() => {
    "use strict";

    angular.module('app.core')
        .factory('req', req);

    req.$inject = ['$http', 'exception', '$window'];

    function req($http, exception, $window)
    {
        let baseUrl = $window.location.pathname.split('/admin')[0] + "/api";

        return {
            get: get,
            post: post,
            put: put,
            del, del
        };


        function beforeRequest()
        {
            showLoading();
        }

        function afterRequest()
        {
            hideLoading();
        }


        function get(url)
        {
            beforeRequest();
            return $http.get(`${baseUrl}/${url}`).then(success).catch(fail);
        }

        function post(url, data)
        {
            beforeRequest();
            return $http.post(`${baseUrl}/${url}`, data).then(success).catch(fail);
        }
        
        function put(url, data)
        {
            beforeRequest();
            // return $http.put(`${baseUrl}/update/${url}`, data).then(success).catch(fail);
            url = "update/" + url;
            return post(url, data);
        }
        
        function del(url, data)
        {
            beforeRequest();
            url = "delete/" + url;
            return post(url, data);
            // return $http({
            //     method: "DELETE",
            //     url: `${baseUrl}/delete/${url}`,
            //     data: data,
            //     headers: {
            //         'Content-type': 'application/json;charset=utf-8'
            //     }
            // }).then(success).catch(fail);
        }


        function success(response)
        {
            afterRequest();
            return response.data;
        }

        function fail(err)
        {
            afterRequest();
            return exception.catcher('XHR Failed for get data')(err);
        }


        function showLoading()
        {
            let loading = `<div class="loader-container"><div class="lds-dual-ring"></div></div>`;

            if ($('body').find('.loader-container').length == 0)
            {
                $('body').append(loading);
            }
        }

        function hideLoading()
        {
            setTimeout(() => {
                $('body').find('.loader-container').remove();
            }, (500));
        }
    }
})()