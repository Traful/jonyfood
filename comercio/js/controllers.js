/*
//Forma correcta si se planea hacer minify del codigo! 
app.controller('MyController', ['$scope', 'greeter', function($scope, greeter) {
  // ...
}]);

//Forma incorrecta, Careful: If you plan to minify your code, your service names will get renamed and break your app.
app.controller('MyController', function($scope, greeter) {
  // ...
});
*/

/*
    Otra forma correcta
    var MyController = function($scope, greeter) {
        // ...
    }
    MyController.$inject = ['$scope', 'greeter'];
    someModule.controller('MyController', MyController);
*/

app
.controller("home", ["$scope", "$http", "mngUsers", "$interval", function($scope, $http, mngUsers, $interval) {
    var map =  null;

    function initialize() {
        var pos = new google.maps.LatLng(-33.6617674, -65.4563466);
        var mapProp = {
            center: pos,
            zoom: 14,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById("googleMap"), mapProp);

        /*
        http://maps.google.com/mapfiles/ms/icons/red-dot.png
        http://maps.google.com/mapfiles/ms/icons/purple-dot.png
        http://maps.google.com/mapfiles/ms/icons/yellow-dot.png
        http://maps.google.com/mapfiles/ms/icons/green-dot.png
        */

        var marker = new google.maps.Marker({
            position: pos,
            map: map,
            title: 'Comercio',
            icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
        });
    }

    var tiempo;
    $scope.refresh = function() {
        if(angular.isDefined(tiempo)) return;
        tiempo = $interval(function() {
            buscar();
        }, 60000);
    };

    $scope.pedidos = null;

    var lastObject = null;

    function buscar() {
        var req = {
            method: "POST",
            dataType: "json",
            url: "php/getpedidos.php",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                id: mngUsers.getData().id //ID del comercio
            }
        };
        $http(req).then(function(response) {
            if (!response.data.err) {
                if(lastObject != response.data.data) {
                    lastObject = response.data.data
                    $scope.pedidos = response.data.data;
                }
            } else {
                var buffer = "";
                for (var i = 0; i < response.data.msg.length; i++) {
                    buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                }
                Materialize.toast(buffer, 4000);
            }
        });
    }

    buscar();
    $scope.refresh();

    $scope.openModal = false;
    $scope.mapexiste = false;
    $scope.openMap = function() {
        if(!$scope.mapexiste) {
            initialize();
            $scope.mapexiste = true;
        }
        $scope.openModal = true;
    }

}])
.controller("categorias", ["$scope", "$http", "mngUsers", "params", "$filter", "$location", function($scope, $http, mngUsers, params, $filter, $location) {
    $scope.openModal = false;

    $scope.categorias = null;

    $scope.delData = {
        id: 0,
        descripcion: ""
    };

    $scope.addData = {
        descripcion: ""
    };

    $scope.eliminar = function(id) {
        var found = $filter("filter")($scope.categorias, { id: id });
        $scope.delData.id = id;
        $scope.delData.descripcion = found[0].descripcion;
        $scope.openModal = true;
    };

    $scope.okeliminar = function() {
        console.log($scope.delData);
    };

    $scope.addCtegoria = function() {
        var req = {
            method: "POST",
            dataType: "json",
            url: "php/addcategoria.php",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                idcomercio: mngUsers.getData().id,
                descripcion: $scope.addData.descripcion
            }
        };
        $http(req).then(function(response) {
            if (!response.data.err) {
                $scope.categorias = response.data.data;
                $scope.addData.descripcion = "";
                $scope.getCategorias();
            } else {
                var buffer = "";
                for (var i = 0; i < response.data.msg.length; i++) {
                    buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                }
                Materialize.toast(buffer, 4000);
            }
        });
    };

    $scope.getCategorias = function() {
        var req = {
            method: "POST",
            dataType: "json",
            url: "php/getcategorias.php",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                id: mngUsers.getData().id
            }
        };
        $http(req).then(function(response) {
            if (!response.data.err) {
                $scope.categorias = response.data.data;
            }
        });
    };

    $scope.goTo =  function(id, descripcion) {
        params.setParams({id: id, descripcion: descripcion});
        //$location.path("/subcategorias").replace();
        $location.path("/subcategorias");
    }
}])
.controller("subcategorias", ["$scope", "$http", "mngUsers", "params", "$location", function($scope, $http, mngUsers, params, $location) {
    $scope.getSubCategorias = function() {
        if(params.existParam("id") && params.existParam("descripcion")) {
            $scope.datosCategoria = {
                id: params.getParams().id,
                descripcion: params.getParams().descripcion
            }
            var req = {
                method: "POST",
                dataType: "json",
                url: "php/getsubcategorias.php",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                data: {
                    idcomercio: mngUsers.getData().id,
                    idcategoria: $scope.datosCategoria.id
                }
            };
            $http(req).then(function(response) {
                if(!response.data.err) {
                    $scope.subcategorias = response.data.data;
                }
            });
        }
    };

    $scope.goTo =  function(id, descripcion) {
        params.setParams({id: id, descripcion: descripcion});
        $location.path("/addsubcategoria");
    }
    $scope.goToEdit =  function(id, descripcion) {
        params.setParams({id: id, idcategoria: params.getParams().id, descripcion: descripcion});
        $location.path("/editsubcategoria");
    }
}])
.controller("addsubcategoria", ["$scope", "$http", "mngUsers", "params", function($scope, $http, mngUsers, params) {
    $scope.additem = "";

    $scope.ListaSelect = [{
        id: 0,
        descripcion: "No hay listas cargadas."
    }];

    $scope.ListaSelectSelected = $scope.ListaSelect[0];

    $scope.addItemBuffer = function() {
        var len = ($scope.additem + "").length;
        if (len == 0) {
            Materialize.toast("La descripción debe tener al menos 1 caracter.", 4000);
        } else {
            $scope.addData.bufferItems.push($scope.additem);
            $scope.additem = "";
        }
    }

    $scope.getListas = function() {
        if(params.existParam("id") && params.existParam("descripcion")) {
            $scope.datosCategoria = {
                id: params.getParams().id,
                descripcion: params.getParams().descripcion
            }
            $scope.addData = {
                idcomercio: mngUsers.getData().id,
                idcategoria: params.getParams().id,
                descripcion: "",
                importe: 0,
                items: 1,
                master: 0,
                bufferItems: [],
                idmaster: 0
            }
            var req = {
                method: "POST",
                dataType: "json",
                url: "php/getlistas.php",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                data: {
                    idcomercio: mngUsers.getData().id
                }
            };
            $http(req).then(function(response) {
                if (!response.data.err) {
                    $scope.ListaSelect = response.data.data;
                    if ($scope.ListaSelect) { //Hay que verificar si no es un array vacio
                        $scope.ListaSelectSelected = $scope.ListaSelect[0];
                    }
                } else {
                    var buffer = "";
                    for (var i = 0; i < response.data.msg.length; i++) {
                        buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                    }
                    Materialize.toast(buffer, 4000);
                }
            });
        }
    };

    $scope.addSubCtegoria = function() {
        var error = false;
        $scope.addData.idmaster = $scope.ListaSelectSelected.id;
        $scope.addData.items = parseInt($scope.addData.items);
        $scope.addData.importe = parseInt($scope.addData.importe);
        //Verificaciones
        //Descripcion
        var len = ($scope.addData.descripcion + "").length;
        if (len == 0) {
            error = true;
            Materialize.toast("La descripción debe tener al menos 1 caracter.", 4000);
        }
        // Importe
        if (!angular.isNumber($scope.addData.importe)) {
            error = true;
            Materialize.toast("Importe no es un número válido.", 4000);
        } else {
            if ($scope.addData.importe < 0) {
                error = true;
                Materialize.toast("Importe debe ser mayor que 0.", 4000);
            }
        }
        //Items
        if (!angular.isNumber($scope.addData.items)) {
            error = true;
            Materialize.toast("Items no es un número válido.", 4000);
        } else {
            if ($scope.addData.items < 1) {
                error = true;
                Materialize.toast("Items no debe ser menor que 1.", 4000);
            }
        }
        //Si no se utiliza una lista, al menos deben existir tantas opciones como items
        if (!$scope.addData.master) { //Si no se utiliza una lista
            var items = parseInt($scope.addData.items);
            var opciones = parseInt($scope.addData.bufferItems.length);
            if (opciones < items) { //Si las opciones que hay son menos que la cantidad de items que puede elegir
                error = true;
                Materialize.toast("Puede elegir " + items + " pero solo se especificaron " + opciones + " opciones.", 4000);
            }
        } else { //Si utiliza una lista pero no tiene listas cargadas
            if ($scope.addData.idmaster == 0) {
                Materialize.toast("Se utilizará una lista pero no hay listas cargadas.", 4000);
            }
        }
        if (!error) {
            var req = {
                method: "POST",
                dataType: "json",
                url: "php/addsubcategoria.php",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                data: $scope.addData
            };
            $http(req).then(function(response) {
                if (response.data.err) {
                    var buffer = "";
                    for (var i = 0; i < response.data.msg.length; i++) {
                        buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                    }
                    Materialize.toast(buffer, 4000);
                }
            });
        }
    }
}])
.controller("editsubcategoria", ["$scope", "$http", "$location", "mngUsers", "params", function($scope, $http, $location, mngUsers, params) {
    $scope.additem = "";

    $scope.ListaSelect = [{
        id: 0,
        descripcion: "No hay listas cargadas."
    }];

    $scope.ListaSelectSelected = $scope.ListaSelect[0];

    $scope.addItemBuffer = function() {
        var len = ($scope.additem + "").length;
        if (len == 0) {
            Materialize.toast("La descripción debe tener al menos 1 caracter.", 4000);
        } else {
            $scope.addData.bufferItems.push($scope.additem);
            $scope.additem = "";
        }
    }

    $scope.getListas = function() {
        if(params.existParam("id") && params.existParam("descripcion")) {
            
            $scope.datosCategoria = {
                id: params.getParams().id, //Id de la SubCategoria
                descripcion: params.getParams().descripcion //Descripción de la categoría
            }

            var req = {
                method: "POST",
                dataType: "json",
                url: "php/getlistas.php",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                data: {
                    idcomercio: mngUsers.getData().id
                }
            };

            $http(req).then(function(response) {
                if (!response.data.err) {
                    $scope.ListaSelect = response.data.data;
                    if ($scope.ListaSelect) { //Hay que verificar si no es un array vacio
                        $scope.ListaSelectSelected = $scope.ListaSelect[0];
                    }
                } else {
                    var buffer = "";
                    for (var i = 0; i < response.data.msg.length; i++) {
                        buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                    }
                    Materialize.toast(buffer, 4000);
                }
            });

            var req = {
                method: "POST",
                dataType: "json",
                url: "php/getsubcategoria.php",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                data: {
                    idcomercio: mngUsers.getData().id,
                    idsubcategoria: params.getParams().id
                }
            };

            $http(req).then(function(response) {
                if (!response.data.err) {
                    $scope.addData = {
                        idcomercio: mngUsers.getData().id,
                        idsubcategoria: params.getParams().id,
                        descripcion: response.data.data.descripcion,
                        importe: parseFloat(response.data.data.costo),
                        items: parseInt(response.data.data.items),
                        master: 0,
                        idmaster: parseInt(response.data.data.idmaster)
                    }
                    if ($scope.addData.idmaster > 0) {
                        $scope.addData.master = 1;
                        $scope.ListaSelectSelected = response.data.data.bufferItems;
                    } else {
                        $scope.addData.bufferItems = response.data.data.bufferItems;
                    }
                } else {
                    var buffer = "";
                    for (var i = 0; i < response.data.msg.length; i++) {
                        buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                    }
                    Materialize.toast(buffer, 4000);
                }
            });
            
        }
    };

    $scope.editSubCtegoria = function() {
        console.log(params.getParams().idcategoria);
        var error = false;
        $scope.addData.idcategoria = params.getParams().idcategoria;
        $scope.addData.idsubcategoria = $scope.datosCategoria.id;
        $scope.addData.idmaster = $scope.ListaSelectSelected.id;
        $scope.addData.items = parseInt($scope.addData.items);
        $scope.addData.importe = parseInt($scope.addData.importe);
        //Verificaciones
        //Descripcion
        var len = ($scope.addData.descripcion + "").length;
        if (len == 0) {
            error = true;
            Materialize.toast("La descripción debe tener al menos 1 caracter.", 4000);
        }
        // Importe
        if (!angular.isNumber($scope.addData.importe)) {
            error = true;
            Materialize.toast("Importe no es un número válido.", 4000);
        } else {
            if ($scope.addData.importe < 0) {
                error = true;
                Materialize.toast("Importe debe ser mayor o igual a 0.", 4000);
            }
        }
        //Items
        if (!angular.isNumber($scope.addData.items)) {
            error = true;
            Materialize.toast("Items no es un número válido.", 4000);
        } else {
            if ($scope.addData.items < 1) {
                error = true;
                Materialize.toast("Items no debe ser menor que 1.", 4000);
            }
        }
        //Si no se utiliza una lista, al menos deben existir tantas opciones como items
        if (!$scope.addData.master) { //Si no se utiliza una lista
            var items = parseInt($scope.addData.items);
            var opciones = parseInt($scope.addData.bufferItems.length);
            if (opciones < items) { //Si las opciones que hay son menos que la cantidad de items que puede elegir
                error = true;
                Materialize.toast("Puede elegir " + items + " pero solo se especificaron " + opciones + " opciones.", 4000);
            }
        } else { //Si utiliza una lista pero no tiene listas cargadas
            if ($scope.addData.idmaster == 0) {
                Materialize.toast("Se utilizará una lista pero no hay listas cargadas.", 4000);
            }
        }
        if (!error) {
            var req = {
                method: "POST",
                dataType: "json",
                url: "php/editsubcategoria.php",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                data: $scope.addData
            };
            $http(req).then(function(response) {
                if (response.data.err) {
                    var buffer = "";
                    for (var i = 0; i < response.data.msg.length; i++) {
                        buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                    }
                    Materialize.toast(buffer, 4000);
                } else {
                    $scope.goTo();
                }
            });
        }
    }

    $scope.goTo =  function() {
        //id de la categoria?
        params.setParams({id: params.getParams().idcategoria, descripcion: params.getParams().descripcion});
        $location.path("/subcategorias");
    }
}])
.controller("listas", ["$scope", "$http", "mngUsers", "$filter", function($scope, $http, mngUsers, $filter) {
    $scope.openModal = false;

    $scope.addData = {
        descripcion: ""
    };

    $scope.addItemData = {
        descripcion: ""
    };

    $scope.delData = {
        id: 0,
        descripcion: "",
        titulo: ""
    };

    $scope.listas = null;
    $scope.itemslista = null;
    $scope.listaSelected = {
        id: 0,
        descripcion: null
    };

    $scope.getListas = function() {
        var req = {
            method: "POST",
            dataType: "json",
            url: "php/getlistas.php",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                idcomercio: mngUsers.getData().id
            }
        };
        $http(req).then(function(response) {
            if (!response.data.err) {
                $scope.listas = response.data.data;
                if ($scope.listas) { //Hay que verificar si no es un array vacio
                    $scope.Listar($scope.listas[0].id, $scope.listas[0].descripcion);
                }
            } else {
                var buffer = "";
                for (var i = 0; i < response.data.msg.length; i++) {
                    buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                }
                Materialize.toast(buffer, 4000);
            }
        });
    };

    $scope.Listar = function(id, descripcion) {
        $scope.listaSelected.id = id;
        $scope.listaSelected.descripcion = descripcion;
        var req = {
            method: "POST",
            dataType: "json",
            url: "php/getitemslista.php",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                idcomercio: mngUsers.getData().id,
                idmaster: id
            }
        };
        $http(req).then(function(response) {
            if (!response.data.err) {
                $scope.itemslista = response.data.data;
            } else {
                var buffer = "";
                for (var i = 0; i < response.data.msg.length; i++) {
                    buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                }
                Materialize.toast(buffer, 4000);
            }
        });
    };

    $scope.addLista = function() {
        var req = {
            method: "POST",
            dataType: "json",
            url: "php/addlista.php",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                idcomercio: mngUsers.getData().id,
                descripcion: $scope.addData.descripcion
            }
        };
        $http(req).then(function(response) {
            if (!response.data.err) {
                $scope.addData.descripcion = "";
                $scope.getListas();
            } else {
                var buffer = "";
                for (var i = 0; i < response.data.msg.length; i++) {
                    buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                }
                Materialize.toast(buffer, 4000);
            }
        });
    };

    $scope.addItemLista = function() {
        //$scope.listaSelected.id
        //Materialize.toast("Ok", 4000);
        var req = {
            method: "POST",
            dataType: "json",
            url: "php/additemlista.php",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                idmaster: $scope.listaSelected.id,
                descripcion: $scope.addItemData.descripcion
            }
        };
        $http(req).then(function(response) {
            if (!response.data.err) {
                $scope.addItemData.descripcion = "";
                $scope.Listar($scope.listaSelected.id, $scope.listaSelected.descripcion);
            } else {
                var buffer = "";
                for (var i = 0; i < response.data.msg.length; i++) {
                    buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                }
                Materialize.toast(buffer, 4000);
            }
        });
    }

    $scope.Stock = function(id) {
        var req = {
            method: "POST",
            dataType: "json",
            url: "php/stockmaster.php",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                id: id
            }
        };
        $http(req).then(function(response) {
            if (response.data.err) {
                var buffer = "";
                for (var i = 0; i < response.data.msg.length; i++) {
                    buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                }
                Materialize.toast(buffer, 4000);
            }
        });
    }

    $scope.eliminarItem = function(id) {
        $scope.delData.id = id;
        $scope.delData.titulo = $scope.listaSelected.descripcion;
        var found = $filter("filter")($scope.itemslista, { id: id });
        $scope.delData.descripcion = found[0].descripcion;
        $scope.openModal = true;
    }

}])
.controller("datapedido", ["$scope", "$http", function($scope, $http) {
    $scope.ListaDemora = [{
            id: 1,
            descripcion: "Entrega en 10 minutos."
        }, {
            id: 2,
            descripcion: "Entrega en 20 minutos."
        }, {
            id: 3,
            descripcion: "Entrega en 30 minutos."
        }, {
            id: 4,
            descripcion: "Entrega en 40 minutos."
        }, {
            id: 5,
            descripcion: "Entrega en 1 hora."
        }
    ];

    $scope.ListaDemoraSelected = $scope.ListaDemora[2];

    $scope.ListaEstado = [{
            id: 1,
            descripcion: "Pendiente"
        }, {
            id: 2,
            descripcion: "En Preparación"
        }, {
            id: 3,
            descripcion: "En Camino"
        }, {
            id: 4,
            descripcion: "Cancelado"
        }, {
            id: 5,
            descripcion: "Cobrado"
        }
    ];

    $scope.ListaEstadoSelected = $scope.ListaEstado[0];

    $scope.coords = {
        lat: 0,
        lon: 0
    }

    $scope.info = null;

    $scope.createMAP = function() {
        var centro = new google.maps.LatLng($scope.coords.lat, $scope.coords.lon);
        var mapProp = {
            center: centro,
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("googleMap_" + $scope.idPedido), mapProp);
        var marker = new google.maps.Marker({
            position: centro,
            map: map,
            title:"Araujo Hans"
        });
    }

    $scope.getDataPedido = function(id) {
        
        var req = {
            method: "POST",
            dataType: "json",
            url: "php/getpedidoinfo.php",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            data: {
                id: id
            }
        };
        $http(req).then(function(response) {
            if(response.data.err) {
                var buffer = "";
                for (var i = 0; i < response.data.msg.length; i++) {
                    buffer = buffer + "<p>" + response.data.msg[i] + "</p>";
                }
                Materialize.toast(buffer, 4000);
            } else {
                $scope.info = response.data.data[0];
            }
        });

        /*
        if(id == 1) {
            $scope.coords.lat = -33.6617674;
            $scope.coords.lon = -65.4563466;
        } else {
            $scope.coords.lat = -33.7000808;
            $scope.coords.lon = -65.4395826;
        }
        */
    }
}]);