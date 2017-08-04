app
.service("mngUsers", ["$http", "$q", function($http, $q) {
    var serviceIdComercio = 0;
    var dataUser = false;

    var promesa = function(value) {
        var defered = $q.defer();
        var promise = defered.promise;
        var req = {
            method: "POST",
            dataType: "json",
            url: "php/getcomercio.php",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                id: value
            }
        };
        $http(req).then(function(response) {
            if(!response.data.error) {
                dataUser = response.data.data;
                defered.resolve(true);
            }
        });
        return promise;
    }

    this.setID = function(idcomercio) {
        var defered = $q.defer();
        var promise = defered.promise;
        if(!idcomercio == serviceIdComercio) {
            promesa(idcomercio).then(function() {
                serviceIdComercio = idcomercio;
                defered.resolve(true);
            }, function() {
                defered.resolve(false);
            });
        } else {
            if(dataUser) {
                defered.resolve(true);
            } else {
                promesa(idcomercio).then(function() {
                    serviceIdComercio = idcomercio;
                    defered.resolve(true);
                }, function() {
                    defered.resolve(false);
                });
            }
        }
        return promise;
    };

    this.getData = function() {
        return dataUser;
    }    
}])
.service("params", ["$window", function($window) {
    var buffer_params = null;

    this.setParams = function(params) {
        $window.localStorage.clear();
        //Guardar en el Local Storage
        angular.forEach(params, function(value, key) {
            $window.localStorage.setItem(key, value);
        });
        buffer_params = params;
    };

    this.existParam = function(key) {
        var ret = false;
        if(!angular.isDefined(buffer_params) || buffer_params === null) {
            var existe = $window.localStorage.getItem(key);
            if(existe) {
                buffer_params = {[key]: existe};
                ret = true;
            }
        } else {
            if(buffer_params.hasOwnProperty(key) && angular.isDefined(buffer_params[key])) {
                ret = true;
            } else {
                var existe = $window.localStorage.getItem(key);
                if(existe) {
                    buffer_params[key] = existe;
                    ret = true;
                }
            }
        }
        return ret;
    }

    this.getParams = function() {
        return buffer_params;
    }
}]);