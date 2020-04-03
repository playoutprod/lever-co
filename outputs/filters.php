<?php

?>
<div class="filters <?php echo ($this->url !='' ? 'search' : '');?>">
  <?php
  if($this->url ==''){
    echo '<span>Filter by : </span>';
  }
  ?>
  <ul>
  <?php
  foreach ($this->filters as $filter => $filter_obj) {
    ?>
    <li class="filter <?php echo $filter;?>">
    <p class="et_pb_contact_field et_pb_contact_field_3" data-id="<?php echo $filter;?>" data-type="select">
			<label for="et_pb_contact_select_filters_<?php echo $filter;?>" class="et_pb_contact_form_label"><?php echo $filter;?></label>
			<select id="et_pb_contact_select_<?php echo $filter;?>" class="et_pb_contact_select input" name="et_pb_contact_select_<?php echo $filter;?>" data-field_type="select" data-original_id="<?php echo $filter;?>">
					<option value=""><b><?php echo ($filter == 'commitment' ? 'Work type' : $filter);?></b></option>
          <?php
            foreach($filter_obj->values as $key => $value){
              $selected = null;
              if(isset($_GET[$filter])){
                 $selected = $_GET[$filter];
              }
              $display = true;
              if($filter == 'team' && isset($_GET['department'])){
                $display = false;
                $index = array_search($_GET['department'],$this->filters->department->values);
                $teams = $this->filters->department->teams[$index];
                foreach ($teams as $t => $team) {
                  if($value == $team){
                    $display = true;
                    break;
                  }
                }
              }
              if($display == true){
                ?>
                  <option value="<?php echo urlencode ($value);?>" <?php echo ($value == $selected ? 'selected' : '');?>><?php echo $value;?></option>
                <?php
              }
            }
          ?>
			</select>
		</p>
    </li>
    <?
  }
?>
</ul>
<?php
if($this->url !=''){
  ?>
  <div class="et_pb_button_module_wrapper et_pb_button_0_wrapper et_pb_button_alignment_left et_pb_module ">
    <a class="et_pb_button et_pb_button_0 et_pb_bg_layout_dark" href="<?php echo $this->url;?>">search</a>
  </div>
  <?php
}
?>
</div>
