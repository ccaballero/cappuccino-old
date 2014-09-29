//Ember.LOG_BINDINGS = true;

window.App=window.App=Ember.Application.create({
//    LOG_ACTIVE_GENERATION: true
//  , LOG_VIEW_LOOKUPS: true
});

/*Models*/
App.Store=DS.Store.extend();
App.Career=Em.Object.extend({
    id: null
  , name: null
  , loaded: false
  , levels: []
});

/*Handlebars*/
Ember.Handlebars.helper('img',function(str){
    return new Ember.Handlebars.SafeString(
        '<img src="/images/'+str.toLowerCase()+'.svg" />');
});
Ember.Handlebars.helper('render_career',function(str){
    return new Ember.Handlebars.SafeString(
        '('+str.id+')'+
        str.name.toUpperCase().slice(15,17)+
        str.name.toLowerCase().slice(17));
});

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
        return Ember.$.getJSON('/data/summary.json')
    }
});

App.ScheduleRoute=Ember.Route.extend({
    /*setupController:function(controller,model){
        var label=this.get('f')+' ('+this.get('g')+')';
        this.controllerFor('application').set('context',label);
        controller.set('model',model);
    },*/
    model:function(params){
        var r1=/^[A-Za-z]+$/
          , r2=/^[0-9]{4}-[0-9]{2}$/
          , careers=[]

        if(r1.test(params.f) && r2.test(params.g)){
            return Ember.$.getJSON('/data/'+params.f+'/'+params.g+'.json')
                .then(function(data){
                    data.forEach(function(i){
                        careers.push(App.Career.create({
                            id:i.code
                          , name:i.name
                        }));
                    });
                    return careers;
                });
        }else{
            alert('Invalid Parameters');
            this.transitionTo('/');
            return null;
        }
    },
    actions:{
        pick1:function(model){
            model.set('loaded',true);
            //model.set('levels',Ember.$.getJSON('/data/summary.json'));
            console.log(model);
        }
    }
});

