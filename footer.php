	<script type="text/javascript">
	   $('dl dd').hide();
       $('dl dt').click(function(){
          if ($(this).hasClass('activo')) {
               $(this).removeClass('activo');
               $(this).next().slideUp();
          } else {
               $('dl dt').removeClass('activo');
               $(this).addClass('activo');
               $('dl dd').slideUp();
               $(this).next().slideDown();
          }
       });
	</script>

</aside></div>
</body>
</html>

<?php
// $DB->cerrarconsulta();
foreach (['DB', 'DB1', 'DB_m', 'DB_m1', 'DB_m2'] as $varName) {
    if (isset($GLOBALS[$varName]) && is_object($GLOBALS[$varName]) && method_exists($GLOBALS[$varName], 'cerrarconsulta')) {
        $GLOBALS[$varName]->cerrarconsulta();
    }
}
?>