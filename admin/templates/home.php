<div ng-controller="home">
    <p>Template vacio, sobre esto hay que buscar o crear estilos. (Toda la carpeta admin es el área en la cual trabajar así que hace los cambios que creas necesarios)</p>
    <p>Te dejo un listado de los comercios que actualmente estan en la DB</p>
    <br>
    <div>Buscar <input type="text" name="" ng-model="busqueda"></div>
    <br>
    <div ng-repeat="comercio in comercios | filter:busqueda">
        {{comercio.id}} // {{comercio.nombre}} // {{comercio.domicilio}}
    </div>
</div>