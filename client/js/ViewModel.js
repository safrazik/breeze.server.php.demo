var ViewModel = function(dataservice) {
    var self = this;
    self.categories = ko.observableArray([]);
    self.activeCategory = ko.observable();
    self.newCategory = {
        name: ko.observable(),
        description: ko.observable()
    };

    self.activateCategory = function(categoryId) {
        if (!categoryId) {
            self.activeCategory(null);
            return;
        }
        dataservice.findCategoryById(categoryId).then(function(category) {
            self.activeCategory(category);
        });
    }

    self.isActiveCategory = function(category) {
        return category == self.activeCategory();
    }

    self.fetchCategories = function() {
        dataservice.getCategories().then(function(data) {
            self.categories(data.results);
        });
    }

    self.addProduct = function() {
        dataservice.createProduct({category: self.activeCategory});
    }

    self.removeProduct = function(product) {
        dataservice.removeProduct(product);
    }

    self.removeActiveCategory = function() {
        if(!confirm('Are you sure to remove the category?')){
            return;
        }
        var category = self.activeCategory();
        dataservice.removeCategory(category);
        self.categories.remove(category);
        self.activeCategory(null);
    }

    self.saveChanges = function() {
        if (ko.unwrap(self.newCategory.name)) {
            var category = dataservice.createCategory(self.newCategory);
            self.newCategory.name('');
            self.newCategory.description('');
            self.categories.push(category);
        }
        dataservice.saveChanges().then(function(data) {
            alert('success');
        }, function(error) {
            console.log(error);
            console.log(error.stack);
            if(error.entityErrors){
                for(var i = 0; i < error.entityErrors.length; i++){
                    var e = error.entityErrors[i];
                    alert((e.isServerError ? '(SERVER ERROR)' : '(CLIENT ERROR)') 
                           + ' ' + e.errorMessage);
                }
            }
            else {
                alert('Save failed: ' + error);
            }
        });
    }

    self.activate = function(categoryId) {
        self.activateCategory(categoryId);
    }

    self.fetchCategories();

}