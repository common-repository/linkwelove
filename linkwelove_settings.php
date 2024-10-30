
<div class="wrap wtPanel">
<div id="iconLinkwelove32" class="icon32"><br></div>
<h2><?php print LINKWELOVE_PUGIN_NAME ." <sub>version: ". LINKWELOVE_CURRENT_VERSION."</sub>"; ?></h2>
<form method="post" action="options.php" id="widgetsForm">

<?php
$kw=0;
settings_fields('linkwelove-settings-group');
?>
	
    <div class="input_section" id="sectionWidget">
		<div class="input_title">
			<h3><?php _e( 'List of widgets', 'linkwelove' );?></h3>
	         <span class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></span>
	         <div class="clearfix"></div>
	         <?php if(LINKWELOVE_MULTIPLE_W){?>
	         	<a href="#" id="addWidget"><?php _e( 'New widget', 'linkwelove' );?></a>
	         <?php }?>
         </div>
         <?php 
	         if(is_array(get_option('linkwelove_widgets'))){
		         foreach (get_option('linkwelove_widgets') as $kw => $vw){
			        
		         	$check_cod = true;
		         	if((!preg_match ('/^[0-9a-fA-F]{24}$/' , $vw['cod'] ))and($vw['cod']!='')){
			        	$check_cod = false;
			        }
			       // print '-'.$vw[cod].'-';
		         	?>
			         <div class="option_input" id="w<?php echo $kw;?>">
						<label for="linkwelove_widgets[<?php echo $kw;?>][cod]">
							<?php _e( 'Widget code', 'linkwelove' );?> <?php echo $kw;?>
							
						</label>
						<input type="text" name="linkwelove_widgets[<?php echo $kw;?>][cod]" value="<?php echo($vw['cod']); ?>" <?php if(!$check_cod){echo 'class="error"';}?> />
				    	<?php if(!$check_cod){echo '<div class="error">Codice widget '.$kw.' errato</div>';}?>
				    	<small><?php _e( 'enter the code found in the linkwelove administration panel', 'linkwelove' );?></small>
				    	<div class="optSep"></div>

				    	<label for="linkwelove_widgets[<?php echo $kw;?>][pos]"><?php _e( 'Position', 'linkwelove' );?></label>
				    	<?php if(!isset($vw['pos'])){$vw['pos']=2;}?>
				    	<select name="linkwelove_widgets[<?php echo $kw;?>][pos]">
				    		<option value="1" <?php if($vw['pos']==1){echo 'selected="selected"';}?>><?php _e( 'After content', 'linkwelove' );?></option>
				    		<option value="2" <?php if($vw['pos']==2){echo 'selected="selected"';}?>><?php _e( 'Before content', 'linkwelove' );?></option>
				    		<?php /*<option value="3" <?php if($vw['pos']==3){echo 'selected="selected"';}?>>Come widget</option>*/ ?>
				    	</select>
				    	<div class="optSep"></div>

						
						<label><?php _e( 'Visibility', 'linkwelove' );?></label>
				    	
				    	<div class="radiolwl">
							<p>
								<?php if( !isset( $vw['vis']['pag'] ) ){$vw['vis']['pag']=0;}?>
								<input type="checkbox" name="linkwelove_widgets[<?php echo $kw;?>][vis][pag]" value="1" <?php if(($vw['vis']['pag'])==1){echo 'checked="checked"';}?>>
				    			<?php _e( 'Pages' );?>
				    		</p>
				    		<p>
				    			<?php if( !isset( $vw['vis']['art'] ) ){$vw['vis']['art']=1;}?>
				    			<input type="checkbox" name="linkwelove_widgets[<?php echo $kw;?>][vis][art]" value="1" <?php if(($vw['vis']['art'])==1){echo 'checked="checked"';}?>>
				    			<?php _e( 'Articles' );?>
				    		</p>
				    		<?php /* next release
				    		<p>
				    			<?php if( !isset( $vw['vis']['arc'] ) ){$vw['vis']['arc']=0;}?>
				    			<input type="checkbox" name="linkwelove_widgets[<?php echo $kw;?>][vis][arc]" value="1" <?php if(($vw['vis']['arc'])==1){echo 'checked="checked"';}?>>
				    			Archivi
				    		</p>
				    		*/ ?>
				    	</div>
				    	<div class="optSep"></div>


				    	<?php if( !isset( $vw['act'] ) ){$vw['act']=0;}?>
				    	<label for="linkwelove_widgets[<?php echo $kw;?>][act]"><?php _e( 'Active', 'linkwelove' );?></label>
				    		<div class="radiolwl">
				    			<input class="lwlCheckActive" type="checkbox" name="linkwelove_widgets[<?php echo $kw;?>][act]" value="1" <?php if(($vw['act'])==1){echo 'checked="checked"';}?>>
				   			</div>
				   		<div class="clearfix"></div>

				   		<?php if((LINKWELOVE_MULTIPLE_W)and($kw>0)){?>
				   		<div style=" margin-top: 10px;">
				   			<a href="#" class="deleteW submitdelete" data-del="w<?php echo $kw;?>"><?php _e( 'Delete' );?></a> 
				    	</div>
				    	<?php }?>
				    </div>
			       	<?php 
	         	}
	       }
	        
	     ?>
       
        
	</div>
	
	<?php 
	$lwlCss = get_option('linkwelove_css');

	?>
	<div class="input_section" >
		<div class="input_title">
			<h3><?php _e( 'Settings', 'linkwelove' );?></h3>
	    </div>
		<div class="option_input" id="lwlCss">
				<label for="linkwelove_css">
					<?php _e( 'Css to add', 'linkwelove' );?>
				</label>
				<textarea name="linkwelove_css"><?php echo $lwlCss; ?></textarea>
			<div class="wtclear">
        		<span class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></span>
    		</div>
    	</div>
    </div>
    
	
</form>
</div>

<div id="newWidget" style="display: none">
<?php 
$kw++;
$addW = '
      <div class="option_input">
			<label for="linkwelove_widgets['.$kw.'][cod]">'.__( 'Codice widget', 'linkwelove' ).' widget '.$kw.' </label>
			<input type="text" name="linkwelove_widgets['.$kw.'][cod]" value="">
	    	
	   	';
if((LINKWELOVE_MULTIPLE_W)and($kw>1)){
$addW .= '
	   		<div style="padding-left: 15%; margin-top: 10px;">
	   			<a href="#" class="deleteW submitdelete" data-del="w'.$kw.'">'.__('delete') .'</a> 
	    	</div>
	    ';
}
$addW .= '
	    </div>
       ';
	echo $addW;
?>
</div>

<script>

jQuery(document).ready(function($){

	var addW = jQuery('#newWidget').html();
	
	jQuery('#addWidget').click( function(e) {
		e.preventDefault();
		jQuery('#sectionWidget').append(addW);
		jQuery('#widgetsForm').submit();
		
	});
	<?php 
	
	if($kw==1){?>
	jQuery('#sectionWidget').append(addW);
	<?php }
	?>
	jQuery('.deleteW').click( function(e) {
		e.preventDefault();
		var r=confirm("Sei sicuro di voler eliminare il widget?");
		if (r==true)
		  {
			idDel = $(this).data("del");
			jQuery('#'+idDel+' .lwlCheckActive').attr('checked', false);
			jQuery('#'+idDel).remove();
			jQuery('#widgetsForm').submit();
		  }
	});
	
});
</script>