/*Handlebars*/
var App=window.App=Ember.Application.create();

Ember.Handlebars.helper('img',function(str){
    return new Ember.Handlebars.SafeString(
        '<img src="/images/'+str.toLowerCase()+'.svg" />');
});

App.Store=DS.Store.extend();

App.Router.map(function(){
    this.route('index',{
        path:'/'
    });
    this.route('schedule',{
        path:'/:f/:g'
    });
});

App.IndexRoute=Ember.Route.extend({
    model:function(){
        return Ember.$.getJSON('/data/summary.json');
    }
});

App.ScheduleRoute=Ember.Route.extend({
    model:function(params){
        // TODO need validators
        var f=params.f
          , g=params.g;
        
        return Ember.$.getJSON('/data/'+f+'/'+g+'.json');
    }
});

