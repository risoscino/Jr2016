app.controller('listController', ['$scope', '$window', 'apartmentsData', function($scope, $window, apartmentsData) {
    $scope.searchTerm = apartmentsData.data.searchTerm;
    $scope.apartments = apartmentsData.data.apartments.sort(function(a, b) {
        if (a.name < b.name){
            return -1;    
        }
        else if (a.name > b.name){
            return 1;    
        }
        else{
            return 0;    
        }     
    });
    
    //PAGE
    $scope.currentPage = 0;
    $scope.pageSize = 5;
    $scope.numberOfPages = function(){
        return Math.ceil($scope.apartments.length/$scope.pageSize);                
    }
    $scope.changePage = function(next){
        if(next){
            $scope.currentPage = $scope.currentPage+1    
        }
        else{
            $scope.currentPage = $scope.currentPage-1    
        }
        $window.scrollTo(0,0);
    }
    
    //SORT RELATED
    $scope.sortingOrder = 'popularity';
    $scope.reverse = true;
    $scope.sort_by = function(newSortingOrder) {
        if ($scope.sortingOrder == newSortingOrder){
            $scope.reverse = !$scope.reverse;    
        }
        else{
            $scope.sortingOrder = newSortingOrder;
            $scope.reverse = true;
        }
        $scope.currentPage = 0;
    }
    
}]);