var app = angular.module("App", ["ngRoute", "ui.materialize"]);

app.config(["$locationProvider", "$routeProvider", function config($locationProvider, $routeProvider) {
    $locationProvider.hashPrefix("");
    /*
    $locationProvider.html5Mode({
        enabled: true,
        requireBase: false
    });
    */

    var id = document.getElementById("idUser").value;

    $routeProvider.
    when("/home", {
        cache: false,
        templateUrl: "templates/home.php",
        resolve: {
            initialData: ["mngUsers", function(mngUsers) {
                return mngUsers.setID(id);
            }]
        }
    }).
    when("/categorias", {
        cache: false,
        templateUrl: "templates/categorias.php",
        resolve: {
            initialData: ["mngUsers", function(mngUsers) {
                return mngUsers.setID(id);
            }]
        }
    }).
    when("/subcategorias", {
        cache: false,
        templateUrl: "templates/subcategorias.php",
        resolve: {
            initialData: ["mngUsers", function(mngUsers) {
                    return mngUsers.setID(id);
                }]
                /*,
                pathData: ["$window", "$route", function($window, $route, $q) {
                    console.log("nnnnnn");
                    var ok = true;

                    if(!$window.localStorage.getItem("idCategoria") == $route.current.params.idCategoria) {
                        console.log("idCategoria no es igual!");
                        ok = false;
                    }
                    if(!$window.localStorage.getItem("txtDescripcion") == $route.current.params.txtDescripcion) {
                        console.log("txtDescripcion no es igual!");
                        ok = false;
                    }

                    var defer = $q.defer();
                    if(ok) {
                        console.log("Resolve es true!");
                        defer.resolve(true);
                    } else {
                        console.log("Resolve es false!");
                        defer.reject("Error!");
                    }
                    return defer.promise;
                }]*/
        }
    }).
    when("/addsubcategoria", {
        cache: false,
        templateUrl: "templates/addsubcategoria.php",
        resolve: {
            initialData: ["mngUsers", function(mngUsers) {
                return mngUsers.setID(id);
            }]
        }
    }).
    when("/editsubcategoria", {
        cache: false,
        templateUrl: "templates/editsubcategoria.php",
        resolve: {
            initialData: ["mngUsers", function(mngUsers) {
                return mngUsers.setID(id);
            }]
        }
    }).
    when("/listas", {
        cache: false,
        templateUrl: "templates/listas.php"
    }).
    otherwise("/home");
}]);

app.run(function($rootScope, $location, mngUsers) {
    //console.log(mngUsers);
    /*
    var id = document.getElementById("idUser").value;
    mngUsers.setID(id);
    
    $rootScope.$on("$routeChangeStart", function(event, next, current) {
        if($location.path() != "/home" && $location.path() != "") {
            //console.log($location.path());
            if(mngUsers.getData() == null) {
                $location.path("/home");
            }
        }
    });
    */
});