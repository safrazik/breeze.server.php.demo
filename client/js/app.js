$(function(){
    var serviceName = '../index.php';
    var dataservice = new DataService(serviceName);
    var vm = new ViewModel(dataservice);
    vm.activate();
    ko.applyBindings(vm);
});