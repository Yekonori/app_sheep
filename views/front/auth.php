<?php ob_start() ; ?>
<div class="sheep__child">
    <?php if( hasFlashMessage() ): ?> <p><?php echo getFlashMessage(); ?></p> <?php endif ; ?>
    <form action="/auth" method="post" class="w100 center">
        <p class="w80 center mt2 biggest" >Email : <input type="text" name="email" value="<?php echo $_SESSION['email']?? ''; ?>"></p>
        <p class="w80 center mt2 biggest" >Password : <input type="password" name="password"></p>
        <input type="hidden" name="token" value="<?php echo md5( date('Y-m-d h:i:00')  . SALT ) ?>">
        <p class="w80 center mt2 biggest" ><input type="submit" name="Ok" value="Ok"></p>
    </form>
</div>
<?php $content = ob_get_clean() ; ?>

<?php include __DIR__ . '/../layouts/master.php' ?>