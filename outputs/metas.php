<span class="published"><?php $date = new DateTime();$date->setTimestamp($job->createdAt/1000);echo $date->format("Y F jS");?></span>
<span class="divider"></span>
<span class="location"><a href="?location=<?php echo $job->categories->location;?>" title="<?php echo $job->categories->location;?>" rel="location"><?php echo $job->categories->location;?></a></span>
<span class="categories">
  <span class="divider"></span>
  <a href="?department=<?php echo $job->categories->department;?>" rel="tag"><?php echo $job->categories->department;?></a>
  <span class="sub-divider"></span>
  <a href="?team=<?php echo $job->categories->team;?>" rel="tag"><?php echo $job->categories->team;?></a>
<span>
