<?php if(have_rows('navigation','options')): ?>
<nav class="navigation">
    <ul>
    <?php 
    while(have_rows('navigation','options')) : 
        the_row(); 
        $layout = get_row_layout();
        ?>

        <?php if($layout == 'simple_link'): ?>
        <li class="simple">
            <?php saos_output_link(get_sub_field('link')); ?>
        </li>
        <?php elseif($layout == 'dropdown_menu'): ?>
            <li class="dropdown">

                <?php
                $type = get_sub_field('menu_item');
                if($type == 'link'):
                    saos_output_link(get_sub_field('link'), 'dropdown-target');
                else: ?>
                <a href="#" class="dropdown-target"><?php the_sub_field('menu_item_text'); ?></a>
                <?php endif; ?>
                
                <?php if(have_rows('dropdown_menu')) : ?>
                <div class="dropdown-wrapper">
                    <ul>
                        <?php while(have_rows('dropdown_menu')) : the_row(); ?>
                        <li>
                            <?php saos_output_link(get_sub_field('link')); ?>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
                <?php endif; ?>

            </li>
        <?php endif; ?>
    <?php endwhile; ?>
    </ul>
</nav>
<?php endif; ?>
