var app = angular.module("App", ["ngRoute"]);

app.config(["$locationProvider", "$routeProvider", function config($locationProvider, $routeProvider) {
        $locationProvider.hashPrefix("");
        /*
        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });
        */
        $routeProvider.
        when("/home", {
            cache: false,
            templateUrl: "templates/home.php"
        }).
        otherwise("/home");
    }
]);