<div ng-controller="subcategorias" ng-init="getSubCategorias()">
    <div class="container">
        <div class="row">
            <div class="col s3">
                <a href="#categorias" class="waves-effect brown darken-2 waves-light btn"><i class="material-icons">reply</i></a>
            </div>
            <div class="col s6">
                <h5 class="sectionTitle">Subcategorías:</h5>
            </div>
            <div class="col s3">
                <!--
                <a href="#addsubcategoria/{{datosCategoria.id}}/{{datosCategoria.descripcion}}" class="waves-effect brown darken-2 waves-light btn right"><i class="material-icons">add</i></a>
                -->
                <a ng-click="goTo(datosCategoria.id, datosCategoria.descripcion)" class="waves-effect brown darken-2 waves-light btn right"><i class="material-icons">add</i></a>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <h5 class="sectionTitle center-align">{{datosCategoria.descripcion}}</h5>
            </div>
        </div>

        <div class="row">
            <div class="col s12 m12">
                <ul class="collapsible" data-collapsible="accordion">
                    <li ng-repeat="subcategoria in subcategorias">
                        <div class="collapsible-header">
                            <i class="material-icons">done</i> {{subcategoria.descripcion}}
                            <a class="secondary-content" tooltipped data-position="right" data-delay="50" data-tooltip="Editar" ng-click="goToEdit(subcategoria.id, datosCategoria.descripcion)">
                                <i class="material-icons brown-text text-darken-2">edit</i>
                            </a>
                        </div>
                        <div class="collapsible-body" style="padding: 0; margin: 0;">
                            <div class="card brown darken-2 z-depth-2" style="margin: 0;">
                                <div class="card-content white-text">
                                    <span class="card-title">Importe: $ {{subcategoria.costo}}</span>
                                    <p>Se pueden seleccionar hasta {{subcategoria.items}} de las siguientes opciones:</p>
                                    <br>
                                    <div class="valign-wrapper" ng-repeat="opcion in subcategoria.listaitems">
                                        <i class="tiny material-icons left">arrow_forward</i> {{opcion.descripcion}}
                                    </div>
                                    <!-- <p ng-repeat="opcion in subcategoria.listaitems"><i class="material-icons">check_box</i> {{opcion.descripcion}}</p> -->
                                </div>
                                <!--
                                <div class="card-action">
                                    <a href="#">This is a link</a>
                                    <a href="#">This is a link</a>
                                </div>
                                -->
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>