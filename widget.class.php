<?php  
class linkwelove_widget extends WP_Widget {  
  
    function __construct() {  
        parent::__construct(false, 'LinkWeLove Widget', array('description'=>'Display widgets created with linkwelove.com'));  
    }  
  
    function widget($args, $instance) {  
        
        if (isset($instance['selectedLWLwidget'])){
        	$args['selectedLWLwidget'] = $instance['selectedLWLwidget'];
        	showLWLWidget($args);
        }else{
        	$instance['selectedLWLwidget'] = 0;
        	echo 'Seleziona il widget da mostrare dall\'area di amministrazione';
        }
        
          
    }  
  
    function update($new_instance, $old_instance) {  
       	$instance = array();
		
		$instance['selectedLWLwidget'] = ( !empty( $new_instance['selectedLWLwidget'] ) ) ? strip_tags( $new_instance['selectedLWLwidget'] ) : '';

		return $instance;
    }  
  
    function old_form($instance) {  
       	$num_ist = 0;
    	if(is_array(get_option('linkwelove_widgets'))){
			if (isset($instance['selectedLWLwidget'])){
				$selectedLWLwidget = esc_attr($instance['selectedLWLwidget']);
			}else{
				$selectedLWLwidget = 0;
			}
		?> 
		<p>
		<label for="<?php echo $this->get_field_id('selectedLWLwidget'); ?>">Widget: 
		<select class="widefat" id="<?php echo $this->get_field_id('selectedLWLwidget'); ?>" name="<?php echo $this->get_field_name('selectedLWLwidget'); ?>">
			<option value="">--seleziona il widget --</option>
		<?php foreach (get_option('linkwelove_widgets') as $kw => $vw){
			if(($vw['pos']==3)and($vw['act']==1)){
			?>
			<option value="<?php echo $vw['cod'];?>" <?php if($selectedLWLwidget==$vw['cod']){echo 'selected=selected'; }?>><?php echo $vw['cod'];?></option>
		<?php 
			$num_ist++;
			}
		}?>
		</select>
		</label>
		</p>
<?php  
    	}
    	if($num_ist==0){
    		?>
    		<div style="border:1px solid red; background-color: #ffebe8; padding: 4px;">Verifica le impostazioni del plugin linkwelove</div>
    		<?php 
    	}
    }  
    function form($instance) {  
       	$check_cod = true;
		if (isset($instance['selectedLWLwidget'])){
			$selectedLWLwidget = esc_attr($instance['selectedLWLwidget']);
			if((!preg_match ('/^[0-9a-fA-F]{24}$/' , $selectedLWLwidget ))and($selectedLWLwidget!='')){
	        	$check_cod = false;
	        }
		}else{
			$selectedLWLwidget = '';
		}
		?> 
		<p>
			<label for="<?php echo $this->get_field_id('selectedLWLwidget'); ?>">Codice Widget LinkWeLove: 
				<input type="text"  id="<?php echo $this->get_field_id('selectedLWLwidget'); ?>" name="<?php echo $this->get_field_name('selectedLWLwidget'); ?>" value="<?php echo $selectedLWLwidget; ?>">
					
			</label>
			<?php if(!$check_cod){echo '<div style="color:red">Codice errato</div>';}?>
		</p>
<?php  
    	
    }  
 }
//------------------  
function showLWLWidget($args) {  

	 echo $args['before_widget'];  
?>  
  		
  		<div class="clearfix">
  			
			<?php 
					
			if (isset($args['selectedLWLwidget'])and($args['selectedLWLwidget']!='')) {  

				echo LinkWeLove::createCodeLwl($args['selectedLWLwidget']);
			}
			?>
  		</div>
<?php  
        echo $args['after_widget'];  
    //}  
}  

?>
