<?php
    $term  = get_queried_object();
    $ch_terms = get_term_children($term->term_id, 'product_cat');
?>
<div class="filter-options">
    <div class="filter-options-block">
        <?php if(!empty($ch_terms)&&false) { ?>
        <div class="filter-options-item">
            <p class="expanded-opener">Групи товарів</p>
            <div class="expanded">
                <?php foreach($ch_terms as $ch_term) { ?>
                <label class="checkbox-container"><?=get_term($ch_term, 'product_cat')->name?>
                    <input type="checkbox" name = "term">
                    <span class="checkmark"></span>
                </label>
                <?php   } ?>
            </div>
        </div>
        <?php   } ?>
        <?php dynamic_sidebar('filter'); ?>
    </div>
</div>