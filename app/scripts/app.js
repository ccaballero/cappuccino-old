/*Handlebars*/
var App=window.App=Ember.Application.create();

Ember.Handlebars.helper('img',function(str){
    return new Ember.Handlebars.SafeString(
        '<img src="/images/'+str.toLowerCase()+'.svg" />');
});
Ember.Handlebars.helper('s_career',function(str){
    return new Ember.Handlebars.SafeString(
        '<a href="">('+str.code+')'+
        str.name.toUpperCase().slice(15,17)+
        str.name.toLowerCase().slice(17)+'</a>');
});

App.Store=DS.Store.extend();

App.Router.map(function(){
    this.route('schedule',{
        path:'/:f/:g'
    });
});

App.ApplicationController=Ember.ObjectController.extend({
    context: ''
});

App.IndexRoute=Ember.Route.extend({
    model:function(){
        // TODO convert in store
        return Ember.$.getJSON('/data/summary.json');
    }
});

App.ScheduleRoute=Ember.Route.extend({
    setupController:function(controller,model){
        this.controllerFor('application').set('context',this.get('f'));
        controller.set('model',model);
    },
    model:function(params){
        // TODO convert in store
        // TODO need validators
        var f=params.f
          , g=params.g;
        
        this.set('f',f);
        this.set('g',g);

        return Ember.$.getJSON('/data/'+f+'/'+g+'.json');
    }
});

