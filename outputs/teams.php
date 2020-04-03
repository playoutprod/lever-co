<?php


foreach ($this->filters->department->values as $d => $department) {
  if($this->filters->department->counts->$department>0){
  ?>
    <div class="department">
      <h2><?php echo $department;?></h2>
      <?php
        foreach ($this->filters->department->teams[$d] as $t => $team) {
          if($this->filters->team->counts->$team>0){
          ?>
            <div class="team">
              <h3><?php echo $team;?></h3>

              <div class="et_pb_module et_pb_blog_0 et_pb_posts et_pb_bg_layout_light">
                  <?php
                  foreach ($this->list as $j => $job) {
                    if($job->categories->department == $department && $job->categories->team == $team){
                  ?>
                  <article id="job-<?php echo $job->id;?>" class="et_pb_post et_pb_no_thumb et_pb_blog_item_0_0 post type-post status-publish format-standard hentry category-uncategorized">
                    <div class="job-description">
                      <h4 class="entry-title"><a href="?job_id=<?php echo $job->id;?>"><?php echo $job->text;?> - <span class="commitment"><?php echo $job->categories->commitment;?></span></a></h4>
                      <p class="post-meta">
                        <span class="published"><?php $date = new DateTime();$date->setTimestamp($job->createdAt/1000);echo $date->format("Y F jS");?></span>
                        <span class="divider"></span>
                        <span class="location"><a href="?location=<?php echo $job->categories->location;?>" title="<?php echo $job->categories->location;?>" rel="location"><?php echo $job->categories->location;?></a></span>
                      </p>
                    </div>
                    <div class="post-content">
                      <div class="post-content-inner" style="display:none"><?php echo $job->description;?></div>
                      <div class="et_pb_button_module_wrapper et_pb_button_0_wrapper et_pb_module">
                           <a class="et_pb_button et_pb_button_0 et_pb_bg_layout_dark" href="<?php echo $job->applyUrl;?>" target="_blank">Apply</a>
                      </div>
                    </div>
                  </article> <!-- .et_pb_post -->
                  <?php
                    }// end if.
                  }// end foreach.
                  ?>
              </div><!-- .et_pb_posts -->
            </div><!-- team -->
          <?php
          }
        }
      ?>
    </div><!-- department -->
  <?php
  }
}


?>
