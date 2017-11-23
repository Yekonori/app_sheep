<svg width="400px" height="200px" class="center">
	    <?php foreach ($userSpended as $uSpend) :
	        // prix de l'uilisateur / le total * 100
	        $pourcentage = ($uSpend['price'] / $allUserSpended * 500);
	    ?>
		<rect x="<?php echo floatval($diff); ?>" y="-200" width="25" fill="<?php echo htmlentities($colors[$i]); ?>" transform="scale(1,-1)">
			<animate attributeName="height" attributeType="XML"
					fill="freeze"
					from="0" to="<?php echo floatval($pourcentage); ?>"
					begin="0s" dur="3s"/>
		</rect>
		<?php $diff += 30; $i++?>
		<?php endforeach;?>
</svg>