<div ng-controller="categorias" ng-init="getCategorias()">
    <div class="container">
        <div class="row">
            <div class="col s3">
                <a href="#" class="waves-effect brown darken-2 waves-light btn"><i class="material-icons">home</i></a>
            </div> 
            <div class="col s9">
                <h5 class="sectionTitle">Categorías:</h5>
            </div> 
        </div>

        <div class="row">
            <div class="col s12">
                <form class="col s12" ng-submit="addCtegoria()" autocomplete="off">
                    <br>
                    <div class="input-field col s9">
                        <i class="material-icons prefix">edit</i>
                        <input name="txtdescripcion" id="txtdescripcion" ng-model="addData.descripcion" type="text" class="validate" value="" maxlength="50">
                        <label for="txtdescripcion">Descripción</label>
                    </div>
                    <div class="col s3 right">
                        <button class="btn waves-effect waves-light red lighten-2 right" name="action" type="submit" style="margin-top: 18px;">
                            <i class="material-icons">arrow_downward</i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <ul class="collection">
                	<li class="collection-item" ng-repeat="categoria in categorias">
                        <div>
                            {{categoria.descripcion}} ({{categoria.items}})
                            <!--
                            <a href="#subcategorias/{{categoria.id}}/{{categoria.descripcion}}" class="secondary-content" tooltipped data-position="right" data-delay="50" data-tooltip="Listar Subcategorias">
                                <i class="material-icons brown-text text-darken-2">view_list</i>
                            </a>
                            -->
                            <a ng-click="goTo(categoria.id, categoria.descripcion)" class="secondary-content" style="margin-right: 8px; cursor: pointer;" tooltipped data-position="right" data-delay="50" data-tooltip="Listar Subcategorias">
                                <i class="material-icons brown-text text-darken-2">view_list</i>
                            </a>
                            <a class="secondary-content" style="margin-right: 8px; cursor: pointer;" tooltipped data-position="right" data-delay="50" data-tooltip="Eliminar Categoria" ng-click="eliminar(categoria.id)" data-target="deleteModal" modal>
                                <i class="material-icons red-text text-darken-4">delete</i>
                            </a>
                        </div>
                    </li>
                </ul>
            </div> 
        </div>
    </div>
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h4>{{delData.descripcion}}</h4>
            <p>Seguro de eliminar?</p>
            <p>Tambien será eliminado todo el contenido de la categoría.</p>
        </div>
        <div class="modal-footer">
            <a style="cursor: pointer;" class="modal-action modal-close waves-effect waves-green btn-flat">No</a>
            <a ng-click="okeliminar()" style="cursor: pointer;" class="modal-action modal-close waves-effect waves-green btn-flat">Si</a>
        </div>
    </div>
</div>