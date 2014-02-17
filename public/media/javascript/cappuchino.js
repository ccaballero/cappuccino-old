var Screen=new(function(){
    this.canvas=null
    this.getCanvas=function(){
        if(!this.canvas){
            this.canvas=document.getElementById('schedule')
        }
        return this.canvas
    }
    this.height=function(){
        if(window.innerHeight){return window.innerHeight}
        else{return document.documentElement.clientHeight}}
    this.width=function(){
        if(window.innerWidth){return window.innerWidth}
        else{return document.documentElement.clientWidth}}
    this.resize=function(){
        Screen.getCanvas().width=Screen.width()-15
        Screen.getCanvas().height=Screen.height()-78
        Schedule.render()
    }
})()

var Schedule=new(function(){
    this.days1=['LUNES','MARTES','MIERCOLES','JUEVES','VIERNES','SABADO']
    this.days2=['L','M','X','J','V','S']

    this.periods=['0645','0730','0815','0900','0945','1030','1115','1200',
        '1245','1330','1415','1500','1545','1630','1715','1800','1845','1930',
        '2015','2100']

    this.colors=[
        '#fec1c0',
        '#ffeec0',
        '#e1ffc1',
        '#c0ffd0',
        '#c1fffe',
        '#c0d1fc',
        '#dec0fe',
        '#ffc0ef',
        '#fec1c0'
    ]
    this.model=new Array()

    this.canvas=null
    this.context=null
    this.init=function(){
        this.canvas=Screen.getCanvas()
        this.context=this.canvas.getContext('2d')
    }
    this.render=function(){
        width=this.canvas.width
        height=this.canvas.height

        rows=this.periods.length
        columns=this.days1.length

        _C=40
        _c=(width-_C)/columns
        _B=20
        _b=(height-_B)/rows

        this.context.textAlign='center'
        this.context.textBaseline='middle'
        this.context.beginPath()

        this.context.moveTo(_C,0)
//        this.context.lineTo(_C,height)

        if(_c>70){
            days=this.days1
        }else{
            days=this.days2
        }

        // cleaning
        this.context.fillStyle='#fff'
        this.context.fillRect(_C,_B,width-_C,height-_B)

        // fills drawings
        e=3
//        this.context.fillStyle='#CDEEB0'
        this.context.fillRect(e,e,width,_B-e)
        this.context.fillRect(e,e,_C-e,height)

        this.context.fillStyle='#000'
        for(i=0;i<columns;i++){
            this.context.fillText(days[i],_C+(_c*(i+0.5)),_B*0.5)
            this.context.moveTo(_C+(_c*i),0)
            //this.context.lineTo(_C+(_c*i),height)
        }

        this.context.moveTo(0,_B)
        this.context.lineTo(width,_B)

        for(j=0;j<rows;j++){
            this.context.fillStyle='#000'
            this.context.fillText(this.periods[j].substring(0,2)+':'+
                this.periods[j].substring(2,4),_C*0.5,_B+(_b*(j+0.5)))
            this.context.moveTo(0,_B+(_b*j))
            this.context.strokeStyle='#a0a0a0'
            this.context.lineTo(width,_B+(_b*j))
        }

        this.context.stroke()

        // drawing colours
        for(var k in this.model){
            coords = k.split(',')
            x=coords[0]
            y=coords[1]
            z=this.model[k].z
            this.context.fillStyle=this.colors[this.model[k].color]
            this.context.fillRect(
                _C+(_c*x),_B+(_b*y),
                _c,(_b*z))
            this.context.fillStyle='#000'
            this.context.fillText(this.model[k].text,
                _C+(_c*x)+(_c*0.5),
                _B+(_b*y)+(_b*z*0.5))
//                _B+(_b*y)+(_b*0.5))
        }
    }

    this.drawSchedule=function(text,x,y,z,color){
        this.model[x+','+y] = {
            'text':text,
            'z':z,
            'color':color
        }
        this.render()
    }
})()

$(document).ready(function(){
    Schedule.init()
    Screen.resize()
})

$(window).resize(Screen.resize)

