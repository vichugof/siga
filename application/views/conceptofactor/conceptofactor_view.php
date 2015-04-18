<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php 
if(isset($css_files)):
    foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php 
    endforeach;
endif; 
?>
<?php 
if(isset($css_files)):
    foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php 
    endforeach; 
endif;
?>
<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>
</head>
<body>
	<div>
		<a href='<?php echo site_url('conceptofactor/concepto', 'http')?>'>Concepto Factor</a> |
		<!--<a href='<?php // echo site_url('conceptofactor/nombrecomun')?>'>Cobcepto Factor por Nombre Común Árbol</a> |-->
		<a href='<?php echo site_url('conceptofactor/grupoconceptofactor', 'http')?>'>Grupo Concepto Factor</a> |
                <a href='<?php echo site_url('simulacioncompensacion', 'http')?>'>Simulaci&oacute;n</a> |
                <a href='<?php echo site_url('conceptofactor/simulacion', 'http')?>'>Simulaci&oacute;n Registrada</a> |
		
	</div>
	<div style='height:20px;'></div>  
    <div>
        <?php 
            if(isset($output) && $output !== NULL){
                echo $output; 
            }
        ?>
    </div>
</body>
</html>
