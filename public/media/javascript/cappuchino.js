var url='/horarios/'
var gestion='2-2013'
var url_gestion=url+gestion

var Templates=new(function(){
    this.carrera='<li name="carrera-{0}"><a class="carrera">{2} ({1})</a></li>'
    this.nivel='<li name="nivel-{0}-{1}"><a class="nivel">Nivel {2}</a></li>'
    this.materia='<li name="materia-{0}-{1}-{2}"><a class="materia">{3}</a></li>'
    this.grupo='<li name="grupo-{0}-{1}-{2}-{3}"><input type="checkbox" /><a class="grupo">Grupo #{4}</a><span>{5}</span></li>'
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
                if(typeof carrera.niveles==='undefined'){
                    Collections.carreras[i]=json
                    Render.renderNiveles(i)
                }
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
        $(this).parent().children('input[type="checkbox"]').trigger('click')
    }
    this.checkGrupo=function(){
        var i=$(this).parent().attr('name').substring(6).split('-')
        if($(this).is(':checked')){
            Render.renderHorarios(i)
        }else{
            Render.eliminarHorarios(i)
        }
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
                Templates.grupo.format(index[0],index[1],index[2],i,materia.grupos[i].codigo,materia.grupos[i].docente))
        }
        li.find('a.grupo').click(Events.clickGrupo)
        li.find('input[type="checkbox"]').change(Events.checkGrupo)
    }
    this.renderHorarios=function(index){
        var li=$('li[name="grupo-'+index[0]+'-'+index[1]+'-'+index[2]+'-'+index[3]+'"]')
        materia=Collections.carreras[index[0]].niveles[index[1]].materias[index[2]]
        grupo=materia.grupos[index[3]]
        for(var i in grupo.horarios){
            Render.renderHorario(
                grupo.horarios[i].dia,
                grupo.horarios[i].hora,
                grupo.horarios[i].duracion,
                materia.nombre+' ('+grupo.horarios[i].aula+')'
            )
        }
    }
    this.renderHorario=function(dia,hora,duracion,texto){
        var dias={'LU':3,'MA':4,'MI':5,'JU':6,'VI':7,'SA':8}
        var periodos={'645':2,'730':3,'815':4,'900':5,'945':6,'1030':7,'1115':8,'1200':9,'1245':10,'1330':11,'1415':12,'1500':13,'1545':14,'1630':15,'1715':16,'1800':17,'1845':18,'1930':19,'2015':20,'2100':21}
        for (var i = 0; i< duracion;i++) { 
            $('#schedule table tbody tr:nth-child('+(periodos[hora]+i)+') td:nth-child('+dias[dia]+')')
                .text(texto)
                .addClass('green')
        }
    }
    this.eliminarHorarios=function(index) {
        var li=$('li[name="grupo-'+index[0]+'-'+index[1]+'-'+index[2]+'-'+index[3]+'"]')
        materia=Collections.carreras[index[0]].niveles[index[1]].materias[index[2]]
        grupo=materia.grupos[index[3]]
        for(var i in grupo.horarios){
            Render.eliminarHorario(
                grupo.horarios[i].dia,
                grupo.horarios[i].hora,
                grupo.horarios[i].duracion
            )
        }
        
    }
    this.eliminarHorario=function(dia,hora,duracion) {
        var dias={'LU':3,'MA':4,'MI':5,'JU':6,'VI':7,'SA':8}
        var periodos={'645':2,'730':3,'815':4,'900':5,'945':6,'1030':7,'1115':8,'1200':9,'1245':10,'1330':11,'1415':12,'1500':13,'1545':14,'1630':15,'1715':16,'1800':17,'1845':18,'1930':19,'2015':20,'2100':21}
        for (var i = 0; i< duracion;i++) { 
            $('#schedule table tbody tr:nth-child('+(periodos[hora]+i)+') td:nth-child('+dias[dia]+')')
                .text(' ')
                .removeClass('green')
        }
    }
})()

$(document).ready(function(){
    jQuery.fn.exists=function(){return this.length>0;}

    $.getJSON(url_gestion+'.json',function(json){
        Collections.carreras=json
        Render.renderCarreras()
    })

    $('header h1').append(' :: gestión: '+gestion)
})
