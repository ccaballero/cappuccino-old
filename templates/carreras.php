<h1>Lista de carreras</h1>
<p>Escoja por favor, las carreras que se tomarán en cuenta para
la selección de sus horarios:</p>

<form method="post" action="">
    <ul>
    <?php 
        global $LIST;
        foreach ($LIST as $carrera) { ?>
        <li><input type="checkbox" />&nbsp;<?php echo $carrera->get ?></li>
    <?php } ?>
    </ul>

    <div class="start">
        <ul>
            <li><a href="#">&laquo; Volver &nbsp;</a></li>
            <li>
                <input type="hidden" name="paso" value="2" />
                <input type="submit" value="&nbsp; Ir a paso 2 &raquo;" />
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</form>
