

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
    .controller('sign_up', function ($scope, $http) {
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

            });
            request.error(function (data) {
                document.getElementById("message").textContent = data.text;
                document.getElementById('loading').style.display = 'none';
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
        },
        $scope.print = function () {
            document.getElementById('loading').style.display = 'block';
            var request = $http({
                method: "get",
                url: url+'/imprimir/simulacion/'+$scope.numsilumacion,
//                data: {
//                    codigo: data.silumacion,
//                },
                headers: {'Content-Type': 'application/x-www-form-urlencoded', responseType: 'arraybuffer'},
                
            });
            request.success(function (data) {
                document.getElementById("message").textContent = data.text;
//                console.log(data.response);
                var file = new Blob([data], { type: 'application/pdf' });
                var fileURL = URL.createObjectURL(file);
                window.open(fileURL);
                document.getElementById('loading').style.display = 'none';

            });
            request.error(function (data) {
                document.getElementById("message").textContent = data.text;
                document.getElementById('loading').style.display = 'none';
            });
        }
    });
}