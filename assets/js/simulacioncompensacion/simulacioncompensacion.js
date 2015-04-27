

//function init(url_simindividual, url_simular){
function init(url){
    
    var app = angular.module('angular_post_demo', []);
    
    app.filter('sumFilter', function() {
        return function(simulaciones) {
            var totalCompensacion = 0;
            var arr = Object.keys(simulaciones).map(function (key) {return simulaciones[key]});
            for (i=0; i<arr.length; i++) {
                totalCompensacion = totalCompensacion + arr[i].totalCompensacion;    
            };

            return totalCompensacion;
        };
    })
    .controller('sign_up', function ($scope, $http, $log, $sce) {
        /*
        * This method will be called on click event of button.
        * Here we will read the email and password value and call our PHP file.
        */
        $scope.numsilumacion = 0;
        $scope.fecha = null;
        $scope.simulaciones = []
        
        $scope.check_credentials = function () {
            document.getElementById('loading').style.display = 'block';
            document.getElementById("message").textContent = "";

            var request = $http({
                method: "post",
//                url: url_simindividual,
                url: url+'/simindividual',
                data: {

                    idsArbol:$scope.idsArbol,
                },
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            });

            /* Check whether the HTTP Request is successful or not. */
            request.success(function (data) {
                document.getElementById("message").textContent = data.text;
                $scope.posts = data.response;
                document.getElementById('loading').style.display = 'none';
                
            });
            request.error(function (data) {
                document.getElementById("message").textContent = data.text;
                document.getElementById('loading').style.display = 'none';
                $scope.posts = [];
            });
        },

        $scope.simular = function () {
            document.getElementById('loading').style.display = 'block';
            var request = $http({
                method: "post",
//                url: url_simular,
                url: url+'/simular',
                data: {
                    arboles:$scope.posts,
                },
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            });

            /* Check whether the HTTP Request is successful or not. */
            request.success(function (data) {
                document.getElementById("message").textContent = data.text;
//                console.log(data.response);
                $scope.simulaciones = data.response;
                $scope.numsilumacion = data.silumacion;
                $scope.fecha = data.fecha;
                document.getElementById('loading').style.display = 'none';
                document.getElementById('imprimir_simulacion').disabled = false;

            });
            request.error(function (data) {
                document.getElementById("message").textContent = data.text;
                document.getElementById('loading').style.display = 'none';
                document.getElementById('imprimir_simulacion').disabled = true;
                $scope.simulaciones = [];
            });
        },
        $scope.quitarArbol = function ($event, $index) {
            $scope.posts.splice($index, 1)
        },
        $scope.limpiar = function () {
            $scope.simulaciones = [];
            $scope.posts = [];
            document.getElementById("message").textContent = '';
            document.getElementById('loading').style.display = 'none';
            document.getElementById('imprimir_simulacion').disabled = true;
        },
        $scope.print = function () {
            window.open(url+'/imprimir/simulacion/'+$scope.numsilumacion, "_self", "width=200, height=100", false);
            
            
//            var request = $http({
//                method: "post",
//                url: url+'/imprimir/simulacion/'+$scope.numsilumacion,
//                headers: {'Content-Type': 'application/x-www-form-urlencoded', responseType: 'arraybuffer'},
//                
//            });
//            request.success(function (data) {
//                var file = new Blob([data], { type: 'application/pdf' });
//                var fileURL = URL.createObjectURL(file);
//                window.open(fileURL);
//                document.getElementById('loading').style.display = 'none';
//            });
//            request.error(function (data) {
//                document.getElementById("message").textContent = data.text;
//                document.getElementById('loading').style.display = 'none';
//            });
        }
    });
}