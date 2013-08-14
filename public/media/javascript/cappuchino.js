var Config=new(function(){
    this.url='/horarios/'
    this.gestion='2-2013'
    this.url_gestion=this.url+this.gestion

    this.current_color=0
    this.carreras=[]
})()

var Templates=new(function(){
    this.carrera='<li name="carrera-{0}"><a class="carrera">{2}</a></li>'
    this.nivel='<li name="nivel-{0}-{1}"><a class="nivel">Nivel {2}</a></li>'
    this.materia='<li name="materia-{0}-{1}-{2}"><a class="materia">{3}</a></li>'
    this.grupo='<li name="grupo-{0}-{1}-{2}-{3}"><input type="checkbox" /><a class="grupo">Grupo #{4} ({5})</a></li>'
})()

var Events=new(function(){
    this.clickCarrera=function(){
        var ul=$(this).parent().has('ul')
        if(ul.length===0){
            var i=$(this).parent().attr('name').substring(8)
            carrera=Config.carreras[i]
            $.getJSON(Config.url_gestion+'/'+carrera.codigo+'.json',function(json){
                if(typeof carrera.niveles==='undefined'){
                    Config.carreras[i]=json
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
            Horario.addGrupo(i)
            Tablero.repaint()
            $(this).next('a.grupo').addClass('selected')
        }else{
            Horario.removeGrupo(i)
            Tablero.repaint()
            $(this).next('a.grupo').removeClass('selected')
        }
    }
})()

var Horario=new(function(){
    this.horarios=new Array();
    this.addGrupo=function(index){
        var li=$('li[name="grupo-'+index[0]+'-'+index[1]+'-'+index[2]+'-'+index[3]+'"]')
        materia=Config.carreras[index[0]].niveles[index[1]].materias[index[2]]
        grupo=materia.grupos[index[3]]

        this.horarios.push([materia, grupo])
    }
    this.removeGrupo=function(index){
        var li=$('li[name="grupo-'+index[0]+'-'+index[1]+'-'+index[2]+'-'+index[3]+'"]')
        materia=Config.carreras[index[0]].niveles[index[1]].materias[index[2]]
        grupo=materia.grupos[index[3]]

        for(i=0;i<this.horarios.length;i++){
            var element=this.horarios[i]
            if (element[0].codigo===materia.codigo&&element[1].codigo===grupo.codigo){
                this.horarios.splice(i,1)
            }
        }
    }
})()

var Tablero=new(function(){
    this.repaint=function(){   
        $('td').removeClass().text('')
        Config.current_color=(Config.current_color+1)%9;
        for(var i in Horario.horarios){
            materia=Horario.horarios[i][0]
            grupo=Horario.horarios[i][1]
            color='color'+((i+1)%9)
            this.renderHorarios(materia,grupo,color)
        }
        
    }
    this.renderHorarios=function(materia,grupo,color){
        for(var i in grupo.horarios){
            this.renderHorario(
                grupo.horarios[i].dia,
                grupo.horarios[i].hora,
                grupo.horarios[i].duracion,
                materia.nombre+' ('+grupo.horarios[i].aula+')',
                color
            )
        }
    }
    this.renderHorario=function(dia,hora,duracion,texto,color){
        var dias={'LU':3,'MA':4,'MI':5,'JU':6,'VI':7,'SA':8}
        var periodos={'645':2,'730':3,'815':4,'900':5,'945':6,'1030':7,'1115':8,'1200':9,'1245':10,'1330':11,'1415':12,'1500':13,'1545':14,'1630':15,'1715':16,'1800':17,'1845':18,'1930':19,'2015':20,'2100':21}
        for (var i = 0; i< duracion;i++) {
            var celda=$('#schedule table tbody tr:nth-child('+(periodos[hora]+i)+') td:nth-child('+dias[dia]+')')
            if(celda.text()!=='') {
                celda.removeClass().addClass('collision');
            }else{
                celda.addClass(color)
                
            } 
            celda.append(texto+'<br />')
        }
        
    }
})

var Render=new(function(){
    this.renderCarreras=function(){
        $('#options').append('<ul></ul>')
        for(var i in Config.carreras){
            $('#options>ul').append(
                Templates.carrera.format(
                    i,
                    Config.carreras[i].codigo,
                    Config.carreras[i].nombre))
        }
        $('a.carrera').click(Events.clickCarrera)
    }
    this.renderNiveles=function(index){
        var li=$('li[name="carrera-'+index+'"]')
        carrera=Config.carreras[index]
        li.append('<ul></ul>')
        for(var i in carrera.niveles){
            li.children('ul').append(
                Templates.nivel.format(index,i,carrera.niveles[i].codigo))
        }
        li.find('a.nivel').click(Events.clickNivel)
    }
    this.renderMaterias=function(index){
        var li=$('li[name="nivel-'+index[0]+'-'+index[1]+'"]')
        nivel=Config.carreras[index[0]].niveles[index[1]]
        li.append('<ul></ul>')
        for(var i in nivel.materias){
            li.children('ul').append(
                Templates.materia.format(index[0],index[1],i,nivel.materias[i].nombre))
        }
        li.find('a.materia').click(Events.clickMateria)
    }
    this.renderGrupos=function(index){
        var li=$('li[name="materia-'+index[0]+'-'+index[1]+'-'+index[2]+'"]')
        materia=Config.carreras[index[0]].niveles[index[1]].materias[index[2]]
        li.append('<ul></ul>')
        for(var i in materia.grupos){
            li.children('ul').append(
                Templates.grupo.format(index[0],index[1],index[2],i,materia.grupos[i].codigo,materia.grupos[i].docente))
        }
        li.find('a.grupo').click(Events.clickGrupo)
        li.find('input[type="checkbox"]').change(Events.checkGrupo)
    }
})()

$(document).ready(function(){
    jQuery.fn.exists=function(){return this.length>0;}

    $.getJSON(Config.url_gestion+'.json',function(json){
        Config.carreras=json
        Render.renderCarreras()
    })

    $('header h1').append(' :: gestión: '+Config.gestion)
})
