<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package
 * @copyright
 * @license
 * @version    $Id: index.tpl 1010 2021-08-11 12:53:25Z  $
 * @author     2RAD
 */
?>
<h2>
  <?php echo $this->translate("Anyx Interface Plugin") ?>
</h2>
  <?php
  //var_dump( $this->connectionvar );
  //exit;
  ?>
<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
</div>
<?php endif; ?>

<br />
<div class='settings'>
	<?php echo $this->form->render($this) ?>
</div>

