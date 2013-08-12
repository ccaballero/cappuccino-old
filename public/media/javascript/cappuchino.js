$(document).ready(function(){
    var _carrera='419701'
    var carrrera={}
    $.getJSON('/horarios/1-2013/'+_carrera+'.json',function(json){
        carrera=json
        $('#opciones h1').html('('+carrera.codigo+') '+carrera.nombre)
        for(var i in carrera.niveles){
            var nivel=carrera.niveles[i]
            $('#opciones').append('<h2>'+nivel.codigo+'</h2>')
            for(var j in carrera.niveles[i].materias){
                var materia=carrera.niveles[i].materias[j]
                $('#opciones').append('<h3>'+materia.nombre+'</h3>')
                for(var k in carrera.niveles[i].materias[j].grupos){
                    var grupo=carrera.niveles[i].materias[j].grupos[k]
                    $('#opciones').append('<h4>'+grupo.codigo+'</h4>')
                    for(var b in carrera.niveles[i].materias[j].grupos[k].horarios){
                        var horario=carrera.niveles[i].materias[j].grupos[k].horarios[b]
                        $('#opciones').append('<h5>'+horario.dia+' '+horario.hora+' '+horario.aula+' '+horario.docente+'</h5>')
                    }
                }
            }
        }
    })
})
