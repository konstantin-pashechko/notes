<?php foreach ($list as $item): ?>
	<div id="<?php echo $item['id']; ?>" class="box <?php if(!$i){echo 'top';} ?>">
		<div class="content heading <?php if($i){echo 'firstcoll';} ?>" contenteditable="true" spellcheck="false"><?php echo $item['section']; ?></div>
		<div class="task" contenteditable="true" spellcheck="false"><?php echo $item['task']; ?></div>
		<div class="progress" contenteditable="true" spellcheck="false"><?php echo $item['progress']; ?></div>
	</div>	
<?php $i=1; endforeach; ?>

	<div id="add">+</div>




