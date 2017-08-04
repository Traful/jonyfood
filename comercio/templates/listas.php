<div ng-controller="listas" ng-init="getListas();">
    <div class="container">
        <div class="row">
            <div class="col s3">
                <a href="#" class="waves-effect brown darken-2 waves-light btn"><i class="material-icons">home</i></a>
            </div> 
            <div class="col s9">
                <h5 class="sectionTitle">Listas:</h5>
            </div> 
        </div>
        <!-- Seccion Agregar Lista -->
        <div class="row">
            <div class="col s12">
                <form class="col s12" ng-submit="addLista()" autocomplete="off">
                    <br>
                    <div class="input-field col s9">
                        <i class="material-icons prefix">edit</i>
                        <input name="txtdescripcion" id="txtdescripcion" ng-model="addData.descripcion" type="text" class="validate" value="" maxlength="50">
                        <label for="txtdescripcion">Descripción de la nueva Lista</label>
                    </div>
                    <div class="col s3 right">
                        <button class="btn waves-effect waves-light red lighten-2 right" name="action" type="submit" style="margin-top: 18px;">
                            <i class="material-icons">arrow_downward</i>
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
        <!-- Fin Seccion Agregar Lista -->

        <div ng-if="listas">
            <!-- Seccion Seleccionar Lista -->
            <div class="row">
                <div class="col s12">
                    <a class="dropdown-button btn" href="javascript:void(0);" data-activates="listaDropdown" dropdown data-hover="false" data-constrainWidth="true">
                        {{listaSelected.descripcion}} <i class="material-icons right">keyboard_arrow_down</i>
                    </a>
                    <ul id="listaDropdown" class="dropdown-content">
                        <li ng-repeat="lista in listas"><a href="javascript:void(0);" ng-click="Listar(lista.id, lista.descripcion)">{{lista.descripcion}}</a></li>
                    </ul>
                </div>
            </div>
            <!-- Fin Seccion Seleccionar Lista -->

            <!-- Seccion Agregar a la Lista -->
            <div class="row">
                <div class="col s12">
                    <form class="col s12" ng-submit="addItemLista()" autocomplete="off">
                        <br>
                        <div class="input-field col s9">
                            <i class="material-icons prefix">edit</i>
                            <input name="txtdescripcion" id="txtdescripcion" ng-model="addItemData.descripcion" type="text" class="validate" value="" maxlength="50">
                            <label for="txtdescripcion">Agregar a {{listaSelected.descripcion}}</label>
                        </div>
                        <div class="col s3 right">
                            <button class="btn waves-effect waves-light red lighten-2 right" name="action" type="submit" style="margin-top: 18px;">
                                <i class="material-icons">arrow_downward</i>
                            </button>
                        </div>
                    </form>
                    
                </div>
            </div>
            <!-- Fin Seccion Agregar a la Lista -->

            <!-- Seccion Detalle Lista -->
            <div class="row">
                <div class="col s12">
                    <ul class="collection">
                    	<li class="collection-item" ng-repeat="items in itemslista">
                            <div>
                                {{items.descripcion}}
                                <div class="switch secondary-content">
                                    <label>
                                        No
                                        <input type="checkbox" ng-checked="items.stock == 1" ng-click="Stock(items.id)">
                                        <span class="lever"></span>
                                        Si
                                    </label>
                                </div>
                                <a class="secondary-content" style="margin-right: 8px; cursor: pointer;" tooltipped data-position="right" data-delay="50" data-tooltip="Eliminar Item" ng-click="eliminarItem(items.id)" data-target="deleteModal" modal>
                                    <i class="material-icons red-text text-darken-4">delete</i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div> 
            </div>
            <!-- Fin Seccion Detalle Lista -->
        </div>

    </div>
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h4>{{delData.titulo}}</h4>
            <p>Seguro de eliminar el item {{delData.descripcion}}?.</p>
        </div>
        <div class="modal-footer">
            <a style="cursor: pointer;" class="modal-action modal-close waves-effect waves-green btn-flat">No</a>
            <a style="cursor: pointer;" class="modal-action modal-close waves-effect waves-green btn-flat">Si</a>
        </div>
    </div>
</div>