(function () {
	'use strict';
	angular.module("ShoppingListCheckOff",[])
	.controller("ToBuyShoppingController",ToBuyShoppingController)
	.controller("AlreadyBoughtShoppingController",AlreadyBoughtShoppingController)
	.service("ShoppingListCheckOffService",ShoppingListCheckOffService);

	ToBuyShoppingController.$inject = ['ShoppingListCheckOffService'];
	function ToBuyShoppingController(ShoppingListCheckOffService) {
		var toBuy = this;

		toBuy.itemsToBuy = ShoppingListCheckOffService.getItemsToBuy();
		toBuy.itemBought = function (index) {
			ShoppingListCheckOffService.itemBought(index);
		}
	}

	AlreadyBoughtShoppingController.$inject = ['ShoppingListCheckOffService'];
	function AlreadyBoughtShoppingController(ShoppingListCheckOffService) {
		var alreadyBought = this;

		alreadyBought.itemsAlreadyBought = ShoppingListCheckOffService.getItemsAlreadyBought();
	}

	function ShoppingListCheckOffService() {
		var service = this;

		var itemsToBuy = [{name:"Apples",quantity:"6"},{name:"Mangoes",quantity:"6"},
						{name:"Peaches",quantity:"6"},{name:"Milk",quantity:"2 gallons"},
						{name:"Sugar",quantity:"2 lbs"},{name:"Salt",quantity:"1 lb"},
						{name:"Bread",quantity:"1 packet"},{name:"Butter",quantity:"1 lb"},];
		var itemsAlreadyBought = [];

		service.getItemsToBuy = function () {
			return itemsToBuy;
		}

		service.getItemsAlreadyBought = function () {
			return itemsAlreadyBought;
		}

		service.itemBought = function (index) {
			var item = itemsToBuy.splice(index,1)[0];
			itemsAlreadyBought.push(item);
		}


	}

})();