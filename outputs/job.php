<?php

$job = $this->job;

?>
<div class="et_pb_button_module_wrapper et_pb_button_0_wrapper  et_pb_module back_button">
     <a class="et_pb_button et_pb_button_0 et_pb_bg_layout_dark" href="<?php echo $_SERVER['HTTP_REFERER'] ?>">Back</a>
</div>
<div class="et_pb_module et_pb_post_title et_pb_post_title_0 et_pb_bg_layout_light  et_pb_text_align_left">
  <div class="et_pb_title_container">
  	<h1 class="entry-title"><?php echo $job->text;?> - <span class="commitment"><?php echo $job->categories->commitment;?></span></h1>
    <p class="et_pb_title_meta_container">
      <?php include 'metas.php';?>
    </p>
  </div>
</div>
<div class="et_pb_module et_pb_post_content et_pb_post_content_0_tb_body">
  <div><?php echo $job->description;?></div>
  <div class="lists">
    <?php
    foreach ($job->lists as $key => $list) {
      ?>
        <div class="list">
          <h2 class="list-title"><?php echo $list->text;?></h2>
          <p class="content"><?php echo $list->content;?></p>
        </div>
      <?php
    }
    ?>
  </div>
</div>
<div class="et_pb_button_module_wrapper et_pb_button_0_wrapper  et_pb_module ">
     <a class="et_pb_button et_pb_button_0 et_pb_bg_layout_dark" href="<?php echo $job->applyUrl;?>" target="_blank">Apply</a>
</div>
