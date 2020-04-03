
<div class="et_pb_module et_pb_blog_0 et_pb_posts et_pb_bg_layout_light">
  <div class="et_pb_ajax_pagination_container">

    <?php
    foreach ($this->list as $key => $job) {
    ?>

    <article id="job-<?php echo $job->id;?>" class="et_pb_post clearfix et_pb_no_thumb et_pb_blog_item_0_0 post type-post status-publish format-standard hentry category-uncategorized">
      <div class="job-description">
        <h2 class="entry-title"><a href="?job_id=<?php echo $job->id;?>"><?php echo $job->text;?> - <span class="commitment"><?php echo $job->categories->commitment;?></span></a></h2>
        <p class="post-meta">
          <?php include 'metas.php';?>
        </p>
      </div>
      <div class="post-content">
        <div class="post-content-inner" style="display:none"><?php echo $job->description;?></div>
        <div class="et_pb_button_module_wrapper et_pb_button_0_wrapper  et_pb_module ">
			       <a class="et_pb_button et_pb_button_0 et_pb_bg_layout_dark" href="<?php echo $job->applyUrl;?>" target="_blank">Apply</a>
		    </div>
      </div>
		</article> <!-- .et_pb_post -->

    <?php
    // end foreach.
    }
    ?>

  </div> <!-- .et_pb_posts -->
  <div class="pagination clearfix">
    <div class="alignleft"></div>
    <div class="alignright"></div>
  </div>
</div>
