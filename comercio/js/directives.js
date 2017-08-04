app.directive("verPedido", function() {
    return {
    	restrict: "E",
    	scope: {
      		idPedido: '=info'
      	},
      	//transclude: true,
        templateUrl: "templates/pedido.html"
    };
});