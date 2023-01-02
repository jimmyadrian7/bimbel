(() => {
    "use strict";

    angular.module('app.core')
        .factory('session', session);

    function session()
    {
        let data = {};

        return {
            setSession: setSession,
            getSession: getSession,
            isSuperUser: isSuperUser,
            isGuru: isGuru,
            isSiswa: isSiswa
        }

        function setSession(session)
        {
            data = session;
        }
        function getSession()
        {
            return data;
        }

        function isSuperUser()
        {
            return data.super_user;
        }
        function isGuru()
        {
            let result = false;

            if (data.role)
            {
                data.role.forEach(role => {
                    if (role.kode == 'G')
                    {
                        result = true;
                    }
                });
            }

            return result;
        }
        function isSiswa()
        {
            let result = false;

            if (data.role)
            {
                data.role.forEach(role => {
                    if (role.kode == 'S')
                    {
                        result = true;
                    }
                });
            }

            return result;
        }
    }
})()