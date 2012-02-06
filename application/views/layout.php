<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title><?=$this->title?></title>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="<?=$this->description?>" />
		<meta name="keywords" content="<?=$this->keywords?>" />

		<link rel="stylesheet" media="screen,projection" href="<?=baseUrl()?>css/default.css" type="text/css" />		
	<?	if ( $this->css ) : ?>
		<link rel="stylesheet" media="screen,projection" href="<?=baseUrl()?>css/<?=$this->css?>" type="text/css" />
	<?	endif; ?>

		<script src="<?=baseUrl()?>js/jquery-1.4.4.min.js" type="text/javascript"></script>
		<script src="<?=baseUrl()?>js/main.js" type="text/javascript"></script>
	<?	if ( $this->js ) :?>
		/<script src="<?=baseUrl()?>js/<?=$this->js?>" type="text/javascript"></script>
	<?	endif; ?>
	</head>
		
	<body>
		<div class="container">  
			<?=$this->render('elements/title')?>
			<div id="ajaxPanel"><? $this->show('home/ajax/mvc')?></div>
			<?=$this->render('elements/menu')?>		
			<div class="clear">&nbsp;</div>
   			<?=$content_for_layout?>
		</div>
    </body>
</html>
     