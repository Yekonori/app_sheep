<?php ob_start() ; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<section class="sheep_main dashboard">
    <section class="sheep_graph grid-1"> 
 
 		<?php include  __DIR__ . '/../partials/graphic.php'; ?>
    
    </section>

    <section class="sheep_spending grid-1"> 
        <?php if( $lastDepenses != false ) : ?>
       	<ul>
        	<?php foreach ($lastDepenses as $data) : ?>
            	<li>Nom(s) <?php echo htmlentities($data['names']); ?>, Prix : <?php echo htmlentities($data['price']); ?>, date : <?php echo htmlentities($data['pay_date']); ?></li>
            <?php endforeach; ?>
        </ul>
       <?php else : ?>
       	<p>Pas de dépenses pour l'instant </p>
       <?php endif; ?>
	
	<ul>
		<a href="/history">
			Toutes les dépenses.
		</a>
	</ul>

    </section>

</section>
