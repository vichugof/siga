<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?=link_tag('assets/css/bootstrap.min.css');?>
        <script src="<?php echo base_url('assets/js/angular.min.js');?>"></script>
        <script src="<?php echo base_url('assets/js/simulacioncompensacion/simulacioncompensacion.js');?>"></script>
        
    </head>
    <script>
        init('<?php echo site_url('');?>');
    </script>
    <body>
        <div class="container-fluid" id="container" >
            <div class="row" >
            <div class="col-md-12">

            <div id="login" ng-app='angular_post_demo' ng-controller='sign_up'>
                <div class="form-group">
                    <label for="idArbol"> Identificadores de &Aacute;rbol para simular compensaci&oacute;n (Separados por comas (,)): </label>
                    <textarea class="form-control" id="idArbol" cols="88" type="text" size="40" ng-model="idsArbol"></textarea>
                    
                </div>
                <div id="controllers" class="col-md-6">
                    <button class="btn btn-primary" ng-click="check_credentials()">Consultar</button>
                    <button class="btn btn-default btn-lg" ng-click="simular()">Simular</button>
                    <button class="btn btn-default" ng-click="limpiar()">Limpiar</button>
                    <button class="btn btn-default" ng-click="print()" id="imprimir_simulacion" disabled="true">Exportar Simulación</button>
                    <span id="message"><?php echo (isset($message) && $message !== NULL) ? $message : ''; ?></span>
                </div>
                <div id="loading" class="col-md-2" style="display: none;">
                    <img height="34" src="<?php echo base_url('assets/img/loading.gif');?>">
                </div>
                <div class="col-md-12 table-responsive" style="margin-top: 5px;">
                    <table class="table table-condensed">
                        <caption> Informaci&oacute;n General</caption>
                        <thead>
                            <tr>
                                <th>1.2 smdlv</th>
                                <th>Valor Árboles</th>
                            </tr>
                        </thead>
                        <tr class="active">
                            <td>{{<?php echo $smdlv; ?> | currency:"$":0}}</td>
                            <td>{{<?php echo $valorIndiviualArbol; ?>| currency:"$":0}}</td>
                        </tr>
                    </table>
                </div>
                
                <div class="col-md-12 table-responsive">
                    <table class="table table-hover">
                        <caption> Simulaci&oacute;n General</caption>
                        <thead>
                            <tr>
                                <th>Nombre Com&uacute;n</th>
                                <th>N&uacute;mero de &Aacute;rboles</th>
                                <th>Total Nombre Com&uacute;n</th>
                                <th>Simulaci&oacute;n</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tr ng-repeat="simulacion in simulaciones">
                            <th scope="row">{{simulacion.nombrecomun}}</th>
                            <td>{{simulacion.numArboles}}</td>
                            <td>{{simulacion.totalCompensacion | currency:"$":0}}</td>
                            <td>{{numsilumacion}}</td>
                            <td>{{fecha}}</td>

                        </tr>
                        <tr>
                            <th scope="row">Total</th>
                            <td></td>
                            <td>{{ simulaciones | sumFilter | currency }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                    
                </div>
                
                <div class="col-md-12 table-responsive">
                    <table class="table table-hover table-bordered" >
                        <caption> Simulaci&oacute;n Individual</caption>
                        <thead>
                            <tr>
                                <th>ID Arbol</th>
                                <th>Nombre Comn&uacute;n</th>
                                <th>Altura</th>
                                <th>FAT</th>
                                <th>DAP</th>
                                <th>FDAP</th>
                                <th>FIEoP ecol&oacute;gica registrada</th>
                                <th>Factor M</th>
                                <th>L.Registrada</th>
                                <th>Factor M</th>
                                <th>Estado</th>
                                <th>Factor M</th>
                                <th>Riesgo Volcamiento</th>
                                <th>Factor M</th>
                                <th>Infraestructura</th>
                                <th>Factor M</th>
                                <th>Total Compensaci&oacute;n</th>
                                <th>{{ posts.length }}</th>
                            </tr>
                        </thead>
                        <tr ng-repeat="post in posts">
                            <th scope="row">{{post.idarbol}}</th>
                            <td>{{post.nombrecomun}}</td>
                            <td>{{post.alt_total}}</td>
                            <td>{{post.factorAlturaTotal}}</td>
                            <td>{{post.p_base}}</td>
                            <td>{{post.factorDiametro}}</td>
                            <td>{{post.factorfact_imp_ecoNombre}}</td>
                            <td>{{post.factorfact_imp_eco}}</td>
                            <td>{{post.factoremplazamientoNombre}}</td>
                            <td>{{post.factoremplazamiento}}</td>
                            <td>{{post.factorvitalidadNombre}}</td>
                            <td>{{post.factorvitalidad}}</td>
                            <td>{{post.factorinclinacionNombre}}</td>
                            <td>{{post.factorinclinacion}}</td>
                            <td>{{post.factorinfraestructuraNombre}}</td>
                            <td>{{post.factorinfraestructura}}</td>
                            <td>{{post.totalCompensacion | currency:"$":0}}</td>
                            <td><button class="btn btn-default" ng-click="quitarArbol($event, $index )"> Quitar </button></td>

                        </tr>
                    </table>
                    <a class="back-to-top pull-right" href="#top">
                    Volver arriba
                </a>
                </div>
                
            </div>
        </div>
        </div>
        </div>
    </body>
</html>
