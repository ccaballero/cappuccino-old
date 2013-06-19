<!DOCTYPE html PUBLIC
    "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>scesi cappuchino</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-language" content="es" />
        <meta http-equiv="title" content="scesi cappuchino" />
        <meta name="author" content="SCESI" />
        <meta name="distribution" content="global" />
        <meta name="description" content="gestor de horarios en la fcyt" />
        <meta name="keywords" content="scesi,pdf,horarios,fcyt,umss" />
        <meta name="locality" content="Cochabamba, Bolivia" />
        <meta name="organization" content="SCESI | Sociedad científica de estudiantes de sistemas e informática" />
        <meta name="origen" content="SCESI | Sociedad científica de estudiantes de sistemas e informática" />
        <meta name="revisit" content="1 days" />

        <meta name="title" content="scesi cappuchino" />
        <link href="/media/cup.png" rel="icon" type="image/png" />
        <link href="/media/css/style.css" media="screen" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="/media/javascript/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="/media/javascript/cappuchino.js"></script>
    </head>
    <body>
        <div id="header">
            <ul>
                <li><a href="/">Inicio</a></li>
                <li><a href="/carreras">Carreras</a></li>
                <li><a href="/materias">Materias</a></li>
                <li><a href="/grupos">Grupos</a></li>
                <li><a href="/horarios">Horarios</a></li>
            </ul>
            <div class="clear"></div>
        </div>
        <div id="wrapper">
            <div id="content">
                <?php echo $this->content() ?>
            </div>
            <div class="clear"></div>
            <div id="footer">
                <ul>
                    <li><a href="http://www.scesi.org/">SCESI</a></li>
                    <li><a href="http://www.memi.umss.edu.bo/">MEMI</a></li>
                    <li><a href="https://github.com/ccaballero/cappuchino">Código fuente</a></li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
    </body>
</html>
