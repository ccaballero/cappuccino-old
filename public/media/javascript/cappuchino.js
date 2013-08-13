var url='/horarios/'
var gestion='1-2013'
var url_gestion=url+gestion

var Templates=new(function(){
    this.carrera='<li name="carrera-{0}"><a class="carrera">{2} ({1})</a></li>'
    this.nivel='<li name="nivel-{0}-{1}"><a class="nivel">{2}</a></li>'
    this.materia='<li name="materia-{0}-{1}-{2}"><a class="materia">{3}</a></li>'
    this.grupo='<li name="grupo-{0}-{1}-{2}-{3}"><a class="grupo">Grupo #{4}</a></li>'
})()

var Collections=new(function(){
    this.carreras=[]
})()

var Events=new(function(){
    this.clickCarrera=function(){
        var ul=$(this).parent().has('ul')
        if(ul.length===0){
            var i=$(this).parent().attr('name').substring(8)
            carrera=Collections.carreras[i]
            $.getJSON(url_gestion+'/'+carrera.codigo+'.json',function(json){
                Collections.carreras[i]=json
                Render.renderNiveles(i)
            })
        }else{
            ul.children('ul').fadeToggle()
        }
        return false
    }
    this.clickNivel=function(){
        var ul=$(this).parent().has('ul')
        if(ul.length===0){
            var i=$(this).parent().attr('name').substring(6).split('-')
            Render.renderMaterias(i)
        }else{
            ul.children('ul').fadeToggle()
        }
        return false
    }
    this.clickMateria=function(){
        var ul=$(this).parent().has('ul')
        if(ul.length===0){
            var i=$(this).parent().attr('name').substring(8).split('-')
            Render.renderGrupos(i)
        }else{
            ul.children('ul').fadeToggle()
        }
        return false
    }
    this.clickGrupo=function(){
        var i=$(this).parent().attr('name').substring(6).split('-')
        Render.renderHorarios(i)
    }
})()

var Render=new(function(){
    this.renderCarreras=function(){
        $('#options').append('<ul></ul>')
        for(var i in Collections.carreras){
            $('#options>ul').append(
                Templates.carrera.format(
                    i,
                    Collections.carreras[i].codigo,
                    Collections.carreras[i].nombre))
        }
        $('a.carrera').click(Events.clickCarrera)
    }
    this.renderNiveles=function(index){
        var li=$('li[name="carrera-'+index+'"]')
        carrera=Collections.carreras[index]
        li.append('<ul></ul>')
        for(var i in carrera.niveles){
            li.children('ul').append(
                Templates.nivel.format(index,i,carrera.niveles[i].codigo))
        }
        li.find('a.nivel').click(Events.clickNivel)
    }
    this.renderMaterias=function(index){
        var li=$('li[name="nivel-'+index[0]+'-'+index[1]+'"]')
        nivel=Collections.carreras[index[0]].niveles[index[1]]
        li.append('<ul></ul>')
        for(var i in nivel.materias){
            li.children('ul').append(
                Templates.materia.format(index[0],index[1],i,nivel.materias[i].nombre))
        }
        li.find('a.materia').click(Events.clickMateria)
    }
    this.renderGrupos=function(index){
        var li=$('li[name="materia-'+index[0]+'-'+index[1]+'-'+index[2]+'"]')
        materia=Collections.carreras[index[0]].niveles[index[1]].materias[index[2]]
        li.append('<ul></ul>')
        for(var i in materia.grupos){
            li.children('ul').append(
                Templates.grupo.format(index[0],index[1],index[2],i,materia.grupos[i].codigo))
        }
        li.find('a.grupo').click(Events.clickGrupo)
    }
    this.renderHorarios=function(index){
        var li=$('li[name="grupo-'+index[0]+'-'+index[1]+'-'+index[2]+'-'+index[3]+'"]')
        grupo=Collections.carreras[index[0]].niveles[index[1]].materias[index[2]].grupos[index[3]]
        for(var i in grupo.horarios){
//            this.renderHorario()
        }
    }
    this.renderHorario=function(dia,hora,duracion,texto){
        $('#schedule table tbody tr:nth-child(3) td:nth-child(3)')
            .text(texto)
            .addClass('green')
    }
})()

$(document).ready(function(){
    jQuery.fn.exists=function(){return this.length>0;}

    $.getJSON(url_gestion+'.json',function(json){
        Collections.carreras=json
        Render.renderCarreras()
    })

    $('header h1').append(' :: gestión: '+gestion)

    Render.renderHorario(0,0,2,'asdf')
})
