<div ng-controller="editsubcategoria" ng-init="getListas()">
    <div class="container">
        <div class="row">
            <div class="col s3">
                <a ng-click="goTo()" class="waves-effect brown darken-2 waves-light btn"><i class="material-icons">reply</i></a>
            </div>
            <div class="col s9">
                <h5 class="sectionTitle">Editar Item de {{datosCategoria.descripcion}}</h5>
            </div>
        </div>

        <div class="row">
            <div class="col s12 m12">
                <form class="col s12" ng-submit="editSubCtegoria()" autocomplete="off">
                    <br>
                    <div class="input-field col s12">
                        <i class="material-icons prefix">edit</i>
                        <input name="txtdescripcion" id="txtdescripcion" ng-model="addData.descripcion" type="text" class="validate" value="" maxlength="50">
                        <label for="txtdescripcion">Descripción</label>
                    </div>

                    <div class="input-field col s6">
                        <i class="material-icons prefix">attach_money</i>
                        <input name="txtimporte" id="txtimporte" ng-model="addData.importe" type="number" class="validate" min="0" value="" maxlength="50">
                        <label for="txtimporte">Importe</label>
                    </div>

                    <div class="input-field col s6">
                        <i class="material-icons prefix">list</i>
                        <input name="txtitems" id="txtitems" ng-model="addData.items" type="number" class="validate" min="1" value="" maxlength="50">
                        <label for="txtitems">Selecciona hasta {{addData.items}} item/s</label>
                    </div>

                    <div class="input-field col s6">
                        <p class="center">
                            <input name="group1" type="radio" id="test1" ng-checked="addData.master" ng-model="addData.master" ng-value="0"/>
                            <label for="test1">Especificar Items</label>
                        </p>
                    </div>

                    <div class="input-field col s6">
                        <p class="center">
                            <input name="group1" type="radio" id="test2" ng-checked="addData.master" ng-model="addData.master" ng-value="1"/>
                            <label for="test2">Utilizar Lista</label>
                            <br><br>
                        </p>
                    </div>

                    <!-- Items -->
                    <div class="col s12 card-panel blue-grey darken-4" ng-show="!addData.master">
                        <div class="input-field col s9">
                            <i class="material-icons prefix">edit</i>
                            <input name="txtitemdescripcion" id="txtitemdescripcion" ng-model="additem" type="text" class="validate" value="" maxlength="50">
                            <label for="txtitemdescripcion">Descripción</label>
                        </div>
                        <div class="col s3">
                            <!--
                            <a class="btn waves-effect waves-light red lighten-2 right" ng-click="addData.bufferItems.push(additem); additem = ''" style="margin-top: 18px; margin-bottom: 18px;">
                                <i class="material-icons">arrow_downward</i>
                            </a>
                            -->
                            <a class="btn waves-effect waves-light red lighten-2 right" ng-click="addItemBuffer()" style="margin-top: 18px; margin-bottom: 18px;">
                                <i class="material-icons">arrow_downward</i>
                            </a>
                            <br>
                        </div>
                        <div class="input-field col s12">
                            <ul class="collection">
                                <li class="collection-item" ng-repeat="item in addData.bufferItems">
                                    {{item}}
                                    <a class="secondary-content" style="margin-right: 8px; cursor: pointer;" tooltipped data-position="right" data-delay="50" data-tooltip="Eliminar" ng-click="addData.bufferItems.splice($index, 1)">
                                        <i class="material-icons red-text text-darken-4">delete</i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Lista -->
                    <div class="col s12 card-panel blue-grey darken-4" ng-show="addData.master">
                        <div class="input-field col s12">
                            <i class="material-icons prefix">edit</i>
                            <select ng-options="item as item.descripcion for item in ListaSelect track by item.id" ng-model="ListaSelectSelected" class="validate" material-select></select>
                        </div>
                    </div>

                    <div class="col s12 center-align">
                        <button class="btn waves-effect waves-light red lighten-2" name="action" type="submit" style="margin-top: 18px; margin-bottom: 18px;">
                            <!-- <i class="material-icons">arrow_downward</i> -->
                            Guardar
                        </button>
                        <br>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>