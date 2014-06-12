var DataService = function(serviceName) {
    var self = this;
    self.manager = new breeze.EntityManager(serviceName);
    self.getCategories = function(where, fetchStrategy) {
        fetchStrategy = fetchStrategy || breeze.FetchStrategy.FromServer;
        var query = new breeze.EntityQuery()
                .from('Categories')
                .expand('products');
        if (where) {
            query = query.where(where);
        }

        return query.using(self.manager).using(fetchStrategy).execute();
    }

    self.createCategory = function(category) {
        category = category || {};
        return self.manager.createEntity('Category',
                {name: ko.unwrap(category.name), description: ko.unwrap(category.description)});
    }

    self.createProduct = function(product) {
        product = product || {};
        return self.manager.createEntity('Product',
                {name: ko.unwrap(product.name), description: ko.unwrap(product.description),
                    price: ko.unwrap(product.price), category: ko.unwrap(product.category)});
    }

    self.removeProduct = function(product) {
        product.entityAspect.setDeleted();
    }

    self.removeCategory = function(category) {
        category.entityAspect.setDeleted();
    }

    self.saveChanges = function() {
        return self.manager.saveChanges();
    }

    self.findCategoryById = function(categoryId) {
        var deferred = Q.defer();
        self.getCategories(new breeze.Predicate('id', '==', categoryId),
                breeze.FetchStrategy.FromLocalCache).then(function(data) {
            deferred.resolve(data.results[0]);
        })
        return deferred.promise;
    }
}