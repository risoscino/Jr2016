var app = angular.module('MyApp', ['ui.router']);

app.config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
    $stateProvider.state('list', {
        url: '/',
        templateUrl: './views/list.html',
        controller: 'listController',
        resolve: {
            apartmentsData : function($http){
                return $http.get('./files/apartment.json').then(function(response) {
                    return response;
                });
            }
        }
    });
    
    $stateProvider.state('detail', {
        url: '/detail/{name}',
        templateUrl: './views/detail.html',
        controller: 'detailController',
        resolve: {
            apartmentData: function($http, $stateParams) {
                return $http.get('./files/apartment.json').then(function(response) {
                    var apartments = response.data.apartments;
                    for(var i=0; i<apartments.length; i++){
                        if(apartments[i].name === $stateParams.name){
                            
                            return apartments[i];
                        }
                    }
                });
            }
        }
    })

    $urlRouterProvider.otherwise('/');
}]);

app.filter('startFrom', function() {
    return function(input, start) {
        start = +start;
        return input.slice(start);
    }
});

app.run(function ($rootScope, $window) {
    $rootScope.$on('$stateChangeSuccess',function(){ 
        $window.scrollTo(0,0);
    }); 
});



