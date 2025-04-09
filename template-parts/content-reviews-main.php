<?php   global $post;
$comments = get_comments(array(
    'post_id' => 86,
    'status' => 'approve',
    'author__not_in' => array(9,10436,1,6782,26,62,9183,11499,9114,11,10,4490,8)//id адміністранторів та мененджерів магазину
));
if(!empty($comments)) { ?> 
<ul class="reviews-slider">
    <?php
    foreach($comments as $comment) {
        $author = $comment->comment_author;
        if(get_comment_meta($comment->comment_ID,'firstname'))
            $author = get_comment_meta($comment->comment_ID,'firstname')[0];
        if(get_comment_meta($comment->comment_ID,'lastname'))
            $author = $author.' '.get_comment_meta($comment->comment_ID,'lastname')[0];
        ?>
        <li>
            <div class="review-box">
            <?php $rating = get_comment_meta($comment->comment_ID,'rating');?>
            <?php if(!empty($rating)) { ?>
                <div class="stars stars3">
                    <?php for($i=1;$i<=$rating[0];$i++) { ?>
                        <span class="star-full">star</span>
                    <?php   } ?>
                    <?php if($rating[0] < 5) { ?>
                        <?php for($i=$rating[0]+1;$i<=5;$i++) { ?>
                            <span class="star-empty">star</span>
                        <?php   } ?>
                    <?php   } ?>
                </div>
            <?php   } ?>
            <p><?=$comment->comment_content?></p>
            <div class="author"><?=$author?></div>
            <time class="mobile" datetime="<?=date("Y-m-d",strtotime($comment->comment_date))?>"><?=date("d.m.Y",strtotime($comment->comment_date))?></time>
            </div>
        </li>
    <?php   } ?>
</ul>
<?php }

