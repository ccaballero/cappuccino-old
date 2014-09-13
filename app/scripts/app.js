var App=window.App=Ember.Application.create();

App.Store=DS.Store.extend();

App.Router.map(function(){
    this.route('index',{path:'/'});
});

