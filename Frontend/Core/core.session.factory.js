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
            getMenu: getMenu,
            getGroupMenu: getGroupMenu,
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


        function getMenu(menuKode, parent=false)
        {
            let menus = data.menu;
            let menu = false;

            menus.forEach(value => {
                if (value.kode == menuKode && !parent && !value.parent)
                {
                    menu = value;
                }
                
                if (parent && value.parent == parent && value.kode == menuKode)
                {
                    menu = value;
                }
            });

            return menu;
        }

        function getGroupMenu(parent)
        {
            let menu = data.menu.filter(value => value.parent == parent);
            return menu;
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