<div ng-controller="home" ng-init="">
    <div class="container">
        <div class="row">
            <div class="col s12">
                <h5 class="sectionTitle"><?php echo(date("d/m/Y")); ?> - Pedidos:</h5>
                <button type="button" class="btn" ng-click="openMap()" data-target="mapModal" modal>Map</button>
            </div> 
        </div>

        {{conteo}}

        <div class="row">
            <div class="col s12 m12">
                <div class="col s12 m6 l4" ng-repeat="pedido in pedidos">
                    <ver-pedido info="1"></ver-pedido>
                </div>
            </div>
        </div>
    </div>
    <div id="mapModal" class="modal">
        <div class="modal-content">
            <div id="googleMap" style="width: calc(100% - 100px); height: 300px; margin: auto;"></div>
        </div>
        <div class="modal-footer">
            <a style="cursor: pointer;" class="modal-action modal-close waves-effect waves-green btn-flat">Cerrar</a>
        </div>
    </div>
</div>